<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2019-10-05
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

function theStandards($input) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('odp', 'http://opendiscovery.org/rdf/Principle/');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/TheStandards/');
    $graph->parseFile($input);
    $a=array();
    $res = $graph->allOfType('od:StandardSolution');
    foreach ($res as $concept) {
        $uri=str_replace('http://opendiscovery.org/rdf/Standard/','',$concept->getURI());
        $out="<h3> Standard Solution ".$uri."</h3>";
        $out.="<p>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</p>";
        if ($concept->all("skos:broader")) {
            $noout.="<p> Belongs to Class "
                .str_replace('http://opendiscovery.org/rdf/Standard/','',$concept->get("skos:broader"))."</p>";
        }
        if ($concept->all("skos:example")) {
            $out.="<h4>Example</h4>".showLanguage($concept->all("skos:example"),"<br/>");
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The 76 Standard Solutions</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return htmlEnv($out);
}

function main() {
    return theStandards("rdf/StandardSolutions.rdf");    
}

echo main();

?>
