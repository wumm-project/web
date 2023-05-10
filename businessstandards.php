<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2021-09-21
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theBusinessStandards($input) 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a ?b
from <http://opendiscovery.org/rdf/BusinessStandards/>
where { ?a a od:TRIZ_Standard . ?b a od:TRIZ_StandardGroup .}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('od:TRIZ_Standard') as $concept) {
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessStandard/','',$concept->getURI());
        $out="<h3> Standard ".$uri."</h3>";
        $out.="<p>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</p>";
        if ($concept->all("skos:narrower")) {
            $out.="<p> Belongs to Group <em>"
                .$concept->get("skos:narrower")->get("skos:prefLabel")."</em></p>";
        }
        if ($concept->all("skos:example")) {
            $out.="<h4>Example</h4>".showLanguage($concept->all("skos:example"),"<br/>");
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The Business Standards proposed by Valeri Souchkov</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theBusinessStandards("rdf/BusinessStandards.rdf"));

?>
