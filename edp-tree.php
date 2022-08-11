<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-07-29

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theEDPTree() {
    setNamespaces();

    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a 
from <http://opendiscovery.org/rdf/EDP-Tree/>
where { ?a a od:EDPNode .}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('od:EDPNode') as $node) {
        $uri=str_replace('http://opendiscovery.org/rdf/','',$node->getURI());
        if ($node->get("od:hasLevel")=="Level_0") {
            $a[$uri]=displayNode($node);
        }
    }
    ksort($a);
    $out='<h2>The EDP Tree proposed by Davide Rosso and Christian Spreafico</h2>

<p>The following tree of EcoDesignPrinciples was proposed by Davide Rosso and
Christian Spreafico</p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayNode($node) {
    $children=array();
    foreach($node->all("skos:broader") as $child) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$child->getURI());
        $children[$uri]=displayNode($child);
    }
    ksort($children);
    $out='';
    if(!empty($children)) { $out='<ul>'.join("\n",$children).'</ul>'; }
    return '<p>'.displayEntry($node).$out."</p>";
}

function displayEntry($node) {
    $uri=str_replace('http://opendiscovery.org/rdf/','',$node->getURI());
    $advantages=$node->get("od:Advantages");
    $disadvantages=$node->get("od:Disadvantages");
    $goal=$node->get("od:Goal");
    $id=$node->get("od:Id");
    $out="<h3>".$node->get("skos:prefLabel")."</h3>\n";
    if (!empty($id)) {
        $out.="<p>Id: $id </p>\n";
    }
    if (!empty($goal)) {
        $out.="<p>Goal: $goal </p>\n";
    }
    if (!empty($advantages)) {
        $out.="<p>Advantages: $advantages </p>\n";
    }
    if (!empty($disadvantages)) {
        $out.="<p>Disadvantages: $disadvantages </p>\n";
    }
    return "<li>\n$out\n</li>\n";
}

echo showpage(theEDPTree());

?>
