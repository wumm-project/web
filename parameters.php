<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-02-27
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
        $name=str_replace("http://opendiscovery.org/rdf/Concept/","",$concept->getUri());
        $label=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $description=showLanguage($concept->all("skos:definition"),"<br/>");
        $nr=$concept->get("od:hasParameterNumber");
        $out="<h3> Parameter $nr: $name </h3>";
        $out.="<h4> $label </h4>";
        if ($description) { $out.="<h5> $description </h5>"; }
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
