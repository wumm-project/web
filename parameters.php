<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-09-21
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theParameters() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a ?b
from <http://opendiscovery.org/rdf/Parameters/>
where { ?a a od:Parameter . ?b a od:ParameterClass .}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    $res = $graph->allOfType('od:Parameter');
    foreach ($res as $concept) {
        $name=str_replace("http://opendiscovery.org/rdf/Concept/","",$concept->getUri());
        $label=showLanguage($concept->all("skos:prefLabel"),"<br/>");
        $altlabel=showLanguage($concept->all("skos:altLabel"),"<br/>");
        $description=showLanguage($concept->all("skos:definition"),"<br/>");
        $nr84=$concept->get("od:hasAltshuller84Id");
        $nr03=$concept->get("od:hasMatrix2003Id");
        $out="<h3> Parameter $name </h3>";
        if (!empty($nr84)) {$out.="Parameter $nr84 in Matrix-84 <br/>"; }
        if (!empty($nr03)) {$out.="Parameter $nr03 in Matrix-2003 <br/>"; }
        $out.="<h4> $label </h4>";
        if ($altlabel) { $out.="<p><strong>Alternative Label:</strong> <br/> $altlabel</p>"; }
        if ($description) { $out.="<p><strong>Description:</strong> <br/> $description </p>"; }
        $a["$nr03"]=$out;
        }
    ksort($a);
    $out='<h2>The TRIZ Parameters in Different Matrices</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theParameters());

?>
