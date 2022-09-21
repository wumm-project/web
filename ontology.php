<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-09-04
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theTitle() {
    return '<h2>The WUMM Ontology Project</h2>';
}

// ------ helper ---- 

function displayThesaurus() {
    /* $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
    $graph->parseFile("rdf/Thesaurus.rdf"); 
    $graph->parseFile("rdf/Souchkov-Glossary.rdf"); 
    $graph->parseFile("rdf/TOP-Glossary.rdf"); 
    $graph->parseFile("rdf/VDI-Glossary.rdf"); // add more    */

    setNamespaces();
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/Thesaurus/>
from <http://opendiscovery.org/rdf/Souchkov-Glossary/>
from <http://opendiscovery.org/rdf/TOP-Glossary/>
from <http://opendiscovery.org/rdf/VDI-Glossary/>
where { ?a a skos:Concept . }';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }

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

function displayGlossary() {
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a
where { ?a a skos:Concept . }

LIMIT 10000
';
    #Nevertheless not all attributes are displayed
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    $res = $graph->allOfType("skos:Concept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
        $u=showLanguage($concept->all("od:TOPExplanation"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>TOP Explanation:</strong></h5>'.$u.'</p>'; }
        $u=showLanguage($concept->all("od:SouchkovDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Souchkov\'s Definition:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:MatvienkoDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Matvienko\'s Definition:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:VDIGlossaryDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Definition in the VDI Glossary:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:hasLippertNote"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Note in Lippert/Cloutier:</strong></h5> '.$u.'</p>'; }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out=theTitle().'
<h3>A Combined TRIZ Glossary</h2>

<p>It contains glossaries from Valeri Souchkov, from the VDI norm, the GSA thesaurus,
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayVDIGlossary() {
    global $sparql;
    $query=' 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a
from <http://opendiscovery.org/rdf/VDI-Glossary/>
where { ?a a skos:Concept . }';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    $res = $graph->allOfType("skos:Concept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
        $u=showLanguage($concept->all("od:TOPExplanation"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>TOP Explanation:</strong></h5>'.$u.'</p>'; }
        $u=showLanguage($concept->all("od:SouchkovDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Souchkov\'s Definition:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:MatvienkoDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Matvienko\'s Definition:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:VDIGlossaryDefinition"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Definition in the VDI Glossary:</strong></h5> '.$u.'</p>'; }
        $u=showLanguage($concept->all("od:hasLippertNote"),"<br/>");
        if (!empty($u)) {$out.='<h5><strong>Note in Lippert/Cloutier:</strong></h5> '.$u.'</p>'; }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out=theTitle().'
<h3>The VDI TRIZ Glossary </h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

// ------ the top level concepts

function TopLevel() {
    /* $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Ontology/');
       $graph->parseFile("rdf/TopLevel.rdf"); */  
    setNamespaces();
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/TopLevel/>
where { ?a a od:TopLevelConcept .}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    $res = $graph->allOfType("od:TopLevelConcept");
    foreach ($res as $concept) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$concept->getURI());
        $types=join("<br/> ",$concept->all("rdf:type"));
        $preflabel=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $parts=$concept->all("od:includes");
        $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
            .'<h5><strong>Types</strong></h5>'.$types
            .'<h5><strong>Preferred Label</strong></h5>'.$preflabel;
        if(!empty($parts)) {
            $out.='<h5><strong>Has SubConcepts</strong></h5>'.displaySubconcepts($parts);
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

function displaySubconcepts($s) {
    $a=array();
    foreach ($s as $key => $entry) {
        $a[]='<li><a href="displayuri.php?uri='.$entry.'">'.$entry.'</a><li>';
    }
    return '<ul>'.join("\n",$a).'</ul>';
}


// ------ the ontocards

function OntoCards() {
    // extract the OntoCards
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

construct { ?a ?b ?c . ?e od:hasSubConcept ?d . }
from <http://opendiscovery.org/rdf/OntoCards/>
where { { ?a a od:OntoCard ; ?b ?c . }
union { ?d od:hasSuperConcept ?e . } 
}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }

    $out=theTitle().'
<h3>The OntoCards</h2>
<div class="concept">
'.displayOntoCard($graph,"http://opendiscovery.org/rdf/Concept/TRIZ").'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayOntoCard ($graph,$s) {
    $node=$graph->resource($s);
    $a=array();
    $uri=str_replace("http://opendiscovery.org/rdf/","",$node->getURI());
    $preflabel=showLanguage($node->all("skos:prefLabel"),"<br/>");
    $web=$node->get("od:hasWebPage"); 
    $out='<h4><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></h4>'
        .$preflabel;
    if(!empty($web)) {
        $out.='<div>'.createLink($web,'The Web Page').'</div>';
    }
    $b=array();
    foreach($node->all("od:hasSubConcept") as $v) {
            #echo $v->{"a"}."\n====\n";
        $b[]='<li>'.displayOntoCard($graph,$v->getURI()).'</li>' ;
    }
    if (!empty($b)) { $out.='<ul>'.join("\n",$b).'</ul>'; }
    return "<div>\n$out\n</div>\n";
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
<li><a href="ontology.php?rdf=sparql">A combined Glossary</a> </li>
<li><a href="ontology.php?rdf=VDI">The VDI Glossary</a> </li>
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
    #echo $rdf;
    if ($rdf=='thesaurus') { $out=displayThesaurus(); }
    else if ($rdf=='sparql') { $out=displayGlossary(); }
    else if ($rdf=='TopLevel') { $out=TopLevel(); }
    else if ($rdf=='OntoCards') { $out=OntoCards(); }
    else if ($rdf=='VDI') { $out=displayVDIGlossary(); }
    else { $out=generalOntologyInfo(); }
    return '<div class="container">'.$out.'</div>';
}


$rdf=$_GET["rdf"]; // (thesaurus | sparql | TopLevel | OntoCards | VDI )
#$rdf="VDI"; // (thesaurus | sparql | TopLevel | OntoCards | VDI )
echo showpage(theOntologyPage($rdf));

#echo displayThesaurus();
#echo displayVDIGlossary();
#echo displayGlossary();
#echo OntoCards();


?>
