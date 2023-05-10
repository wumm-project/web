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
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/StandardSolutions/>
where { ?a a od:StandardSolution . }';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('od:StandardSolution') as $concept) {
        $uri=str_replace('http://opendiscovery.org/rdf/Standard/','',$concept->getURI());
        $out="<h3> Standard Solution ".$uri."</h3>";
        //echo $concept->get("skos:prefLabel");
        $out.="<p>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</p>";
        if ($concept->all("skos:broader")) {
            $out.="<p> Belongs to Class "
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
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theStandards("rdf/StandardSolutions.rdf"));

?>
