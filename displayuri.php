<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2022-02-11

 * Display the properties and inverse properties of an URI exploring the WUMM
 * SPARQL Endpoint

 * 2021-03-26: URIs containing "Model#" fixed, since # has special
 * interpretation by GET.

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function displayURI($uri) {
    setNamespaces();
    global $sparql;
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
                     "href='displayuri.php?uri=",
                     $out);
    $out=str_replace('Model#', '', $out);
    $prefix='<p><a href="http://wumm.uni-leipzig.de">Home</a></p>';
    return $prefix.$out;
}

$uri=$_GET["uri"]; // e.g.
//$uri='http://opendiscovery.org/rdf/Concept/EngineeringProblem';
//$uri='Concept/theory';
echo displayURI($uri);

?>
