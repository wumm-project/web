<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-01-04
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function thePrinciples($input) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('odp', 'http://opendiscovery.org/rdf/Principle/');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/ThePrinciples/');
    $graph->parseFile($input);
    $a=array();
    $res = $graph->allOfType('od:Principle');
    foreach ($res as $concept) {
        $out="<h3> Principle ".$concept->get("od:hasPrincipleNumber")."</h3>";
        $out.="<h4>".showLanguage($concept->all("rdfs:label"),"<br/>")."</h4>";
        $out.="<h4>Description</h4>".showLanguage($concept->all("od:description"),"<br/>");
        if ($concept->all("skos:note")) {
            $out.="<h4>Notes</h4>".showLanguage($concept->all("od:note"),"<br/>");
        }
        if ($concept->all("rdfs:comment")) {
            $out.="<h4>Comment</h4>".showLanguage($concept->all("rdfs:comment"),"<br/>");
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The 40 TRIZ Principles</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(thePrinciples("rdf/Principles.rdf"));

?>
