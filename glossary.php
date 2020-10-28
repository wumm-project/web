<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-10-28
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function theParts($a) {
    $b=array();
    foreach($a as $v) {
        $b[]="<li>".showLanguage($v->all("skos:prefLabel"),", ")."</li>";
    }
    return "<ul>".join("\n",$b)."</ul>";
}

function displayGraph($graph) {
    $a=array();
    $res = $graph->allOfType("skos:Concept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $out="<h3>$uri</h3><h4>Types</h4>$types<h4>preferredLabel</h4>$preflabel";
        if ($concept->all("od:hasPart")) {
            $out.="<h4>Has Parts</h4>".theParts($concept->all("od:hasPart"));
        }
        if ($concept->all("od:includes")) {
            $out.="<h4>Includes</h4>".theParts($concept->all("od:includes"));
        }
        if ($concept->all("od:hasPartialCase")) {
            $out.="<h4>Partial Case</h4>".theParts($concept->all("od:hasPartialCase"));
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<a href="glossary.php">Home</a>
<h2>The Combined TRIZ Glossary Details Page</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayEntry($graph,$entry) {
}

function generalOntologyInfo() {
    $out='<h2>The Combined TRIZ Glossary Entry Page</h2>

<p>This Combined Glossary joins concepts from dífferent sources:
<ul>
  <li>Thesaurus from the <a href="https://www.altshuller.ru/thesaur/thesaur.asp">GSA website</a></li>
  <li>VDI Glossary</li>
</ul>

The concepts from the different glossaries are tagged with different rdf:type,
that all are subtypes of skos:Concept.
</p>

<p><a href="glossary.php?rdf=show">Show the combined glossary</a></p>

';
    return '<div class="container">'.$out.'</div>';
}

function theGlossary($rdf=null,$entry=null) {
    setNamespaces();
    if (empty($rdf)) { $out=generalOntologyInfo(); }
    else { 
        // parse all information into one RDF graph
        // different glossaries are tagges with different class names
        $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Ontology/');
        $graph->parseFile("rdf/Thesaurus.rdf"); // add more 
        $graph->parseFile("rdf/VDI-Glossary.rdf"); // add more 
        if (empty($entry)) { $out=displayGraph($graph,$rdf); }
        else { $out=displayEntry($graph,$entry); }
    }
    return '<div class="container">'.$out.'</div>';
}


$rdf=$_GET["rdf"]; // e.g. tc:GSAThesaurusEntry
$entry=$_GET["entry"];
echo showpage(theGlossary($rdf,$entry));

?>
