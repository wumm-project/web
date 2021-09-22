<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-09-21
 */

require 'vendor/autoload.php';

function setNamespaces() {
    \EasyRdf\RdfNamespace::set('bibo', 'http://purl.org/ontology/bibo/');
    \EasyRdf\RdfNamespace::set('dc', 'http://purl.org/dc/elements/1.1/');
    \EasyRdf\RdfNamespace::set('dcterms', 'http://purl.org/dc/terms/');
    \EasyRdf\RdfNamespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    \EasyRdf\RdfNamespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
    \EasyRdf\RdfNamespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    \EasyRdf\RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    \EasyRdf\RdfNamespace::set('swc', 'http://data.semanticweb.org/ns/swc/ontology#');
    \EasyRdf\RdfNamespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    \EasyRdf\RdfNamespace::set('odp', 'http://opendiscovery.org/rdf/Person/');
    \EasyRdf\RdfNamespace::set('tc', 'http://opendiscovery.org/rdf/Concept/');
}

setNamespaces();
$graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/TheStandards/');
$graph->parseFile("rdf/StandardSolutions.rdf");
$res = $graph->allOfType('skos:Concept');
//$res = $graph->allOfType('od:StandardSolution');
$concept=$res[2];
echo $concept->dump("text");
$uri=str_replace('http://opendiscovery.org/rdf/Standard/','',$concept->getURI());
$out="<h3> Standard Solution ".$uri."</h3>";
$u=$concept->all('skos:prefLabel');
echo join(",",$u);
$out.="<p>".join($concept->all("skos:prefLabel"),"<br/>")."</p>";
echo $out;



?>
