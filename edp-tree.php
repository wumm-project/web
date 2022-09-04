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
    $out='<h2>The EDP Tree proposed by Davide Russo and Christian Spreafico</h2>

<p>The following tree of EcoDesignPrinciples was proposed by Davide Russo and
Christian Spreafico. The tree consists of 4 subtrees corresponding to the life
cycle phases <em>Pre-Manufacturing</em>, <em>Manufacturing</em>, <em>Product
Use</em> and <em>End of Life</em> of a product.  The RDF variant was generated
from a JSON dump provided by the authors.  An exact reference is still to be
added.</p>

<p>This tree serves only as proof of concept to demonstrate the potential of
the RDF backed WUMM database.  The links in the table point to a relevant part
of the complete RDF information in the database. </p>

<div class="concept">
<ul style="list-style: none;">
'.join("\n", $a).'
</ul></div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayNode($node) {
    $children=array();
    foreach($node->all("skos:narrower") as $child) {
        $uri=str_replace("http://opendiscovery.org/rdf/","",$child->getURI());
        $children[$uri]=displayNode($child);
    }
    ksort($children);
    $out='';
    if(!empty($children)) {
        $out='<ul style="list-style: none;">'.join("\n",$children).'</ul>';
    }
    return '<p>'.displayEntry($node).$out."</p>";
}

function displayEntry($node) {
    $uri=str_replace('http://opendiscovery.org/rdf/','',$node->getURI());
    $advantages=$node->get("od:Advantages");
    $disadvantages=$node->get("od:Disadvantages");
    $goal=$node->get("od:Goal");
    $id=$node->get("od:Id");
    $suggestion=$node->get("od:Suggestion");
    $examples=$node->all("skos:example");
    $out='<h3><a href="displayuri.php?uri='.$uri.'">'
        .$node->get("skos:prefLabel")."</a></h3>\n";
    //if (!empty($id)) { $out.="<br/>Id: $id\n"; }
    if (!empty($goal)) {
        $out.="<br/>Goal: $goal\n";
    }
    if (!empty($advantages)) {
        $out.="<br/>Advantages: $advantages\n";
    }
    if (!empty($disadvantages)) {
        $out.="<br/>Disadvantages: $disadvantages\n";
    }
    if (!empty($suggestion)) {
        $out.="<br/>Suggestion: $suggestion\n";
    }
    if (!empty($examples)) {
        $out.="<p>Examples: ".join("<br/>",$examples)." </p>\n";
    }
    return "<li>\n$out\n</li>\n";
}

echo showpage(theEDPTree());

?>
