<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2019-07-09
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

function thePeople($in) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/People/');
    $graph->parseFile($in);
    $a=array();
    $res = $graph->allOfType('foaf:Person');
    foreach ($res as $autor) {
        $b=array();
        foreach ($autor->all("foaf:name") as $e) {
            $b[]='<span itemprop="name" class="foaf:name">'
                .$e->getValue().'</span>';
        }
        foreach ($autor->all("foaf:affil") as $e) {
            $b[]='<span itemprop="affiliation" class="foaf:affil">'
                .$e->getValue().'</span>';
        }
        foreach ($autor->all("foaf:homepage") as $e) {
            $b[]=createLink($e,$e);
        }
        $a[$autor->getUri()]=
            '<div itemscope itemtype="http://schema.org/Person" class="creator">'
            .join('<br/>',$b).'</p></div>';
    }
    ksort($a);
    $out='<h3>People in the TRIZ Social Network</h3>
<div class="people">
'.join("\n", $a).'
</div> <!-- end class people -->';
    return htmlEnv($out);
}

function main() {
    // $in=$_GET["people"];
    return thePeople("rdf/People.rdf");
    
}

echo genericLink().main();

?>
