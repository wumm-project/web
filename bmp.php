<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-05-20
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theBusinessModelPatterns() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a
from <http://opendiscovery.org/rdf/BusinessModelPatterns/>
where { ?a a od:BusinessModelPattern . }';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('od:BusinessModelPattern') as $bmp) {
        $comment=$bmp->get("rdfs:comment");
        $note=$bmp->all("skos:note");
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessModelPattern/'
                         ,'',$bmp->getURI());
        $out="<h3>".$uri."</h3>";
        $out.="<p>".showLanguage($bmp->all("skos:prefLabel"),"<br/>")."</p>";
        if (!empty($note)) {
            $out.="<h4>Note</h4><p>".showLanguage($note,"<br/>")."</p>";
        }
        if (!empty($comment)) {
            $out.="<h4>Comment</h4><p>".$comment."</p>";
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>Business Model Patterns</h2>

<p>The following Business Model Patterns are derived from the St. Gallen
Business Model Navigator and partly enriched from other sources.</p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theBusinessModelPatterns());

?>
