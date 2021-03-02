<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-03-02x
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
    $graph->parseFile("rdf/Thesaurus.rdf"); 
    $graph->parseFile("rdf/Souchkov-Glossary.rdf"); 
    $graph->parseFile("rdf/TOP-Glossary.rdf"); 
    $graph->parseFile("rdf/VDI-Glossary.rdf"); 
    $graph->parseFile("rdf/OntoCards.rdf"); 
    $graph->parseFile("rdf/TopLevel.rdf"); // add more 
    $a=array();
    $res = $graph->allOfType("skos:Concept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
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

// ------ the top level concepts

function TopLevel() {
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
    $graph->parseFile("rdf/TopLevel.rdf");  
    $a=array();
    $res = $graph->allOfType("od:TopLevelConcept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $parts=join($concept->all("od:hasPart"),"<br/>");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
        if(!empty($parts)) {
            $out.='<h5><strong>Has Parts</strong></h5>'.$parts;
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out=theTitle().'
<h3>The Top Level Concepts</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

// ------ the ontocards

function OntoCards() {
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
    $graph->parseFile("rdf/OntoCards.rdf");  
    $a=array();
    $res = $graph->allOfType("od:OntoCard");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $web=$concept->get("od:hasWebPage");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
        if(!empty($web)) {
            $out.='<div>'.createLink($web,'The Web Page').'</div>';
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out=theTitle().'
<h3>The Top Level Concepts</h2>
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
<li><a href="ontology.php?rdf=thesaurus">A combined Thesaurus</a> </li>
<li><a href="ontology.php?rdf=TopLevel">Top Level Concepts</a> from the TRIZ Ontology Project</li> 
  <li><a href="ontology.php?rdf=OntoCards">OntoCards</a> from the TRIZ Ontology Project</li>
</ul> 
The concepts from the different sources are tagged with different rdf:type,
that all are subtypes of skos:Concept to follow up their provenience.  Some
more efforts are required to unify the URIs of the concepts between the
different sources.</p>

<p>There is a link attached to each such concept that leads to the full
information about that topic extracted from our <a
href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL Endpoint</a> including
all direct successors (i.e. objects, where the given concept is the subject)
and predecessors (i.e. subjects, where the given concept is the object).  This
allows for a first navigation through the full WUMM RDF Data stored so far.
</p>

<p>You can follow up secondary links in such a presentation to different
concepts in the WUMM RDF database to get a similar representation for those
concepts.  In particular you can get listed all instances of a given RDF type,
calling the listing of that type, since the instances are predecessors of the
type for the predicate <tt>rdf:type</tt>.</p>

<p>Links to sources outside the WUMM RDF database call the target page
directly. For the moment this mainly concerns links to web pages (in Russian)
of the <a href="https://triz-summit.ru/onto_triz">TRIZ Ontology
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


$rdf=$_GET["rdf"]; // (thesaurus | TopLevel | OntoCards )
echo showpage(theOntologyPage($rdf));

?>
