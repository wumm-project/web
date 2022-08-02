<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function thePeople() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/People/>
from <http://opendiscovery.org/rdf/MATRIZ-Certificates/>
where { ?a a foaf:Person .}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach ($graph->allOfType('foaf:Person') as $autor) {
        $c=array();        
        foreach ($autor->all("od:hasCertificate") as $v) {
            $v=str_replace("http://opendiscovery.org/rdf/Certificate/","Level ",$v);
            $v=str_replace("_"," no. ",$v);
            $c[]=$v;
        }
        $b=array();                
        foreach ($autor->all("foaf:name") as $e) {
            $url='http://wumm.uni-leipzig.de/displayuri.php?uri='.$autor->getURI();
            $value='<strong><span itemprop="name" class="foaf:name">'
                  .$e->getValue().'</span></strong>';
            $b[]=createLink($url,$value);
        }
        foreach ($autor->all("foaf:affil") as $e) {
            $b[]='<span itemprop="affiliation" class="foaf:affil">'
                .$e->getValue().'</span>';
        }
        foreach ($autor->all("foaf:homepage") as $e) {
            $b[]=createLink($e,$e);
        }
        $f=$autor->all("dbo:deathDate");
        if (!empty($f)) { $b[]='Died on '.join(", ",$f) ; }
        if (!empty($c)) { $b[]='MATRIZ Certificates: '.join(", ",$c); }
        $d=$autor->all("od:hasMATRIZAward");
        if (!empty($d)) { $b[]='MATRIZ Awards: '.join(", ",$d); }
        $a[$autor->getUri()]=
            '<div itemscope itemtype="http://schema.org/Person" class="creator">'
            .join('<br/>',$b).'</p></div>';
    }
    ksort($a);
    $out='<h3>People in the TRIZ Social Network</h3>
<div class="people">
'.join("\n", $a).'
</div> <!-- end class people -->';
    return '<div class="container">'.$out.'</div>';
}

function getCertificate($graph,$author) {
    $s=array();
    foreach ($graph->allOfType('od:CertificateLevel4') as $c) {
        if (strcmp($c->get("od:owner"),$author)==0) {
            $s[]=str_replace("http://opendiscovery.org/rdf/Certificate/","",
            $c->getURI());
        }
    }
    foreach ($graph->allOfType('od:CertificateLevel5') as $c) {
        if (strcmp($c->get("od:owner"),$author)==0) {
            $s[]=str_replace("http://opendiscovery.org/rdf/Certificate/","",
            $c->getURI());
        }
    }
    return join(", ",$s);
}


echo showpage(thePeople());
    
?>
