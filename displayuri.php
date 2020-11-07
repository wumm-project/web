<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2020-11-07

 * Display the properties and inverse properties of an URI exploring the WUMM
 * SPARQL Endpoint

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function displayURI($uri) {
    setNamespaces();
    $sparql = new \EasyRdf\Sparql\Client('http://wumm.uni-leipzig.de:8891/sparql');
    $query = '
construct { ?s ?p ?o . ?s1 ?p1 ?o1 . } 
where 
{ {?s ?p ?o . filter regex(?s,"'.$uri.'") }
UNION
  { ?s1 ?p1 ?o1 . filter regex(?o1,"'.$uri.'") }
}
LIMIT 100';
    $result=$sparql->query($query);
    $out=$result->dump("html");
    $out=str_replace("href='http://opendiscovery.org/rdf/",
                     "href='displayuri.php?uri=http://opendiscovery.org/rdf/",
                     $out);
    return $out;
}

$uri=$_GET["uri"]; // e.g.
//$uri='http://opendiscovery.org/rdf/Concept/EngineeringProblem';
//$uri='Concept/theory';
echo displayURI($uri);

?>
