<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-08-15
 * Lyubomirsky's Flow Development Pattern

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theFlowDevelopmentPatterns() {
    setNamespaces();

    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a 
from <http://opendiscovery.org/rdf/FlowDevelopmentPattern/>
where { ?a a tc:FlowDevelopmentPattern .}
';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('tc:FlowDevelopmentPattern') as $node) {
        $uri=str_replace('http://opendiscovery.org/rdf/','',$node->getURI());
        $a[$uri]=displayNode($node);
    }
    $out='<h2>The Flow Development Patterns proposed by Alex Lyubomirsky (2006)</h2>

<div class="concept">
<ul style="list-style: none;">
'.$a["FDP/P_Core"].'
</ul>
</div> <!-- end concept list -->';
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
    $definition=$node->get("skos:definition");
    $notes=join("; ",$node->all("skos:note"));
    $examples=join("<br/>",$node->all("skos:example"));
    $out="<h3>".$uri.": ".$node->get("skos:prefLabel")."</h3>\n";
    if(!empty($definition)) {
        $out.="<p><strong>Definition:</strong> $definition </p>";
    }
    if(!empty($notes)) {
        $out.="<p><strong>Notes:</strong> $notes </p>";
    }
    if(!empty($examples)) {
        $out.="<p><strong>Examples:</strong> $examples </p>";
    }
    return "<li>\n$out\n</li>\n";
}

echo showpage(theFlowDevelopmentPatterns());

?>
