<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-08-14
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function tbk1_tree() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a ?b
from <http://opendiscovery.org/rdf/TBK1-Concepts/>
from <http://opendiscovery.org/rdf/TRIZ-References/>
where { 
?a a skos:Concept .
?b a od:Reference .
}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX dcterms: <http://purl.org/dc/terms/> 

construct { ?a od:references ?u . } 
from <http://opendiscovery.org/rdf/TRIZ-References/>
where { ?u a od:Reference ; od:hatVerweis ?a .}'; try { $references = $sparql->query($query); } catch (Exception $e) { print
    "<div class='error'>".$e->getMessage()."</div>\n"; } $a=array(); foreach
    ($graph->allOfType('skos:Concept') as $c) {
    $uri=str_replace("http://opendiscovery.org/rdf/","",$c->getURI());
    $a[$uri]=displayNode($c,$references); }

    return '
<div class="container"> 

<h2>The TRIZ Body of Knowledge, English version of 2012</h2>

<p> This displays the <a
  href="https://www.aitriz.org/triz/triz-body-of-knowledge">TRIZ Body of
  Knowledge</a> as developed jointly by Simon Litvin, Vladimir Petrov, Mikhail
  Rubin (International TRIZ Association MA TRIZ) and Victor Fey (Altshuller
  Institute for TRIZ Studies) and published in an English version of 2012.</p>

<ul style="list-style: none;">
'.$a["TBK1/Concept.Root"].'
</ul>
</div>
';
}

function displayNode($n,$references) {
    $children=array();
    foreach($n->all("skos:narrower") as $child) {
        $uri=str_replace("http://opendiscovery.org/rdf/TBK1/","",$child->getURI());
        $children[$uri]=displayNode($child,$references);
    }
    ksort($children);
    $out='';
    if(!empty($children)) {
        $out='<ul style="list-style: none;">'.join("\n",$children).'</ul>';
    }
    return '<p>'.displayEntry($n,$references).$out."</p>";
}

function displayEntry ($n,$references) {
    $uri=str_replace("http://opendiscovery.org/rdf/","",$n->getURI());
    $title=showLanguage($n->all("skos:prefLabel"),"<br/>");
    $links=array();
    foreach($n->all("od:hatVerweis") as $v) {
        $links[]=getVerweis($v,$references);
    }
    $out='';
    if (!empty($links)) { $out='<p>Links:<br/> '.join("<br/>\n",$links).'</p>'; }
    return '<li>'.$uri.'<br/>'.$title.$out.'</li>';
}

function getVerweis ($v,$references) {
    $u=$references->resource($v);
    $links=array();
    foreach($u->all("od:references") as $ref) {
        $uri=$ref->getURI();
        $links[]='<a href="displayprop.php?uri='.$uri.'">'.$uri.'</a>';
    }
    $out=join("<br/>\n",$links);
    //$out=showLanguage($u->all("od:withTitle"),"<br/>");
    return $out ;
}

echo showpage(tbk1_tree());

?>
