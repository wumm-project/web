<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function test() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/TBK1-Concepts/>
where { ?a a skos:Concept .}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

construct { ?a od:references ?u; od:withTitle ?t . } 
from <http://opendiscovery.org/rdf/TRIZ-References/>
where { ?u a od:Reference ; dcterms:title ?t ; od:hatVerweis ?a .}';
    try {
        $texts = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach ($graph->allOfType('skos:Concept') as $c) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$c->getURI());
        $a[$uri]=displayNode($c,$texts);
    }
    return '
<div class="container">
'.$a["TBK1/Concept.Root"].'
</div>
';
}

function displayNode($n,$texts) {
    $children=array();
    foreach($n->all("skos:broader") as $child) {
        $uri=str_replace("http://opendiscovery.org/rdf/TBK1/","",$child->getURI());
        $children[$uri]=displayNode($child,$texts);
    }
    ksort($children);
    $out='';
    if(!empty($children)) { $out='<ul>'.join("\n",$children).'</ul>'; }
    return '<p>'.displayEntry($n,$texts).$out."</p>";
}

function displayEntry ($n,$texts) {
    $uri=str_replace("http://opendiscovery.org/rdf/","",$n->getURI());
    $title=showLanguage($n->all("skos:prefLabel"),"<br/>");
    $links=array();
    foreach($n->all("od:hatVerweis") as $v) {
        $links[]=getVerweis($v,$texts);
    }  
    return '<li>'.$uri.'<br/>'.$title.'<ul>Links: '.join("<br/>\n",$links).'</ul></li>';
}

function getVerweis ($v,$texts) {
    $u=$texts->resource($v);
    $uri=$u->get("od:references");
    $out=showLanguage($u->all("od:withTitle"),"<br/>");
    return "<li>".$uri."<br/>".$out."</li>" ;
}

echo showpage(test());

?>
