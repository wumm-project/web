<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2022-08-14

 * Display only the properties of an URI exploring the WUMM SPARQL Endpoint.

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function displayProp($uri) {
    setNamespaces();
    global $sparql;
    $query = '
construct { ?s ?p ?o . } 
where 
{?s ?p ?o . filter regex(?s,"'.$uri.'") }
LIMIT 100';
    $result=$sparql->query($query);
    $out=$result->dump("html");
    $out=str_replace("href='http://opendiscovery.org/rdf/",
                     "href='displayprop.php?uri=",
                     $out);
    $out=str_replace('Model#', '', $out);
    $prefix='<p><a href="http://wumm.uni-leipzig.de">Home</a></p>';
    return $prefix.$out;
}

$uri=$_GET["uri"]; // e.g.
//$uri='http://opendiscovery.org/rdf/Concept/EngineeringProblem';
//$uri='Concept/theory';
echo displayProp($uri);

?>
