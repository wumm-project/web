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
    $graph->parseFile("rdf/OntoCards.rdf"); // add more 
    $graph->parseFile("rdf/TopLevel.rdf"); // add more 
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

// ------ the thesaurus

function TopLevel() {
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
    $graph->parseFile("rdf/TopLevel.rdf");  
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

<p>For the moment we compiled <a href="ontology.php?rdf=thesaurus">A combined
Thesaurus</a> joining concepts from dífferent sources:
<ul>
  <li>Thesaurus from the <a
  href="https://www.altshuller.ru/thesaur/thesaur.asp" >GSA website</a></li>
  <li>VDI Glossary</li>
  <li>OntoCards and Top Level Concepts from the TRIZ Ontology Project</li>
</ul> 
The concepts from the different thesauri are tagged with different rdf:type,
that all are subtypes of skos:Concept.</p> 

<p>Concepts from different sources are tagged by different RDF types to follow
up their provenience.  Some more efforts are required to unify the URIs of the
concepts between the different sources.</p>

<p>There is a link attached to each such concept that leads to the full
information about that topic extracted from our <a
href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL Endpoint</a> including
all direct successors (i.e. objects, where the given concept ist the subject)
and predecessors (i.e. subjects, where the given concept ist the object".
This allows for a first navigation through the full RDF Data stored so far.
</p>

<p>You can follow up secondary links in such a presentation to different
concepts in the WUMM RDF database to get a similar representation for those
concepts.  In particular you can get listed all instances of a given RDF type,
calling the listing of that type, since the instances are predecessors of the
type for the predicate <tt>rdf:type</tt>.</p>

<p>Links to sources outside the WUMM RDF database issue show directly the
target page. For the moment this mainly concerns links to web pages (in
Russian) of the <a href="https://triz-summit.ru/onto_triz">TRIZ Ontology
Project</a>.</p>

';
    return '<div class="container">'.$out.'</div>';
}

function theOntologyPage($rdf) {
    setNamespaces();
    if ($rdf=='thesaurus') { $out=theThesaurus(); }
    else if ($rdf=='TopLevel') { $out=TopLevel(); }
    else if ($rdf=='OntoCards') { $out=OntoCards(); }
    else { $out=generalOntologyInfo(); }
    return '<div class="container">'.$out.'</div>';
}


$rdf=$_GET["rdf"]; // (thesaurus | toplevel | ontocards )
echo showpage(theOntologyPage($rdf));

?>
