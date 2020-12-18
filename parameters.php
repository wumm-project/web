<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-12-18
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theParameters() 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/ThePrinciples/');
    $graph->parseFile("rdf/Parameters.rdf");
    //echo $graph->dump("html");
    $a=array();
    $res = $graph->allOfType('od:Parameter');
    foreach ($res as $concept) {
        $name=str_replace("http://opendiscovery.org/rdf/Parameter/","",$concept->getUri());
        $label=showLanguage($concept->all("rdfs:label"),"<br/>");
        $description=showLanguage($concept->all("od:description"),"<br/>");
        $nr=$concept->get("od:hasParameterNumber");
        $out="<h3> Parameter $nr: $name </h3>";
        $out.="<h4> $label </h4>";
        if ($description) { $out.="<h4> $description </h4>"; }
        $a["$nr"]=$out;
        }
    ksort($a);
    $out='<h2>The 39 TRIZ Parameters</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theParameters());

?>
