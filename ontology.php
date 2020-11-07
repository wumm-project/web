<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-07
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theTitle() {
    return '<a href="ontology.php">Home</a>
    <h2>The TRIZ Ontology Project Companion</h2>';
}

// ------ the thesaurus

function theThesaurus() {
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
    $graph->parseFile("rdf/Thesaurus.rdf"); // add more 
    $graph->parseFile("rdf/VDI-Glossary.rdf"); // add more 
    $a=array();
    $res = $graph->allOfType("skos:Concept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5>Types</h5>'.$types
            .'<h5>preferredLabel</h5>'.$preflabel;
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out=theTitle().'
<h3>The Combined TRIZ Thesaurus</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

// ------ the main info page

function generalOntologyInfo() {
    $out=theTitle().'
<p>We proudly present the reengineering along established semantic web
concepts of a small part of the <a
href="https://wumm-project.github.io/Ontology.html" >TRIZ Ontology
Project</a>. It\'s a first hack, more detailed explanations will be comiled
<a href="https://wumm-project.github.io/OntologyDetails">elsewhere</a>.</p>

<p>For the moment we compiled
<ul>
<li> <a href="ontology.php?rdf=thesaurus">A combined Thesaurus</a> joining
concepts from dífferent sources:
<ul>
  <li>Thesaurus from the <a href="https://www.altshuller.ru/thesaur/thesaur.asp">GSA website</a></li>
  <li>VDI Glossary</li>
</ul> 
The concepts from the different thesauri are tagged with different rdf:type,
that all are subtypes of skos:Concept.</li>

<li> The <a href="ontology.php?rdf=toplevel">Top Level "TRIZ Overview
Ontology"</a> (to be implemented) </li>

<li> The <a href="ontology.php?rdf=ontocards"> Ontology Ontocards Atlas</a>
(to be implemented)</li>

</ul>

';
    return '<div class="container">'.$out.'</div>';
}

function theOntologyPage($rdf) {
    setNamespaces();
    if ($rdf=='thesaurus') { $out=theThesaurus(); }
    else { $out=generalOntologyInfo(); }
    return '<div class="container">'.$out.'</div>';
}


$rdf=$_GET["rdf"]; // (thesaurus | toplevel | ontocards )
echo showpage(theOntologyPage($rdf));

?>
