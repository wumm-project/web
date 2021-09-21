<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-09-21
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theStandards($input) 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/TheStandards/');
    $graph->parseFile($input);
    $a=array();
    $res = $graph->allOfType('od:StandardSolution');
    foreach ($res as $concept) {
        $uri=str_replace('http://opendiscovery.org/rdf/Standard/','',$concept->getURI());
        $out="<h3> Standard Solution ".$uri."</h3>";
        $out.="<p>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</p>";
        if ($concept->all("skos:broader")) {
            $noout.="<p> Belongs to Class "
                .str_replace('http://opendiscovery.org/rdf/Standard/','',
                             $concept->get("skos:broader"))."</p>";
        }
        if ($concept->all("skos:example")) {
            $out.="<h4>Example</h4>".showLanguage($concept->all("skos:example"),"<br/>");
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The 76 Inventive Standards</h2>
<p> Strange enough, code does not work as required, no idea why. </p> 
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theStandards("rdf/StandardSolutions.rdf"));

?>
