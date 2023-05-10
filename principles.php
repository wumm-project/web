<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2023-05-10  
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function thePrinciples() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a ?b
from <http://opendiscovery.org/rdf/Principles/>
where { ?a a od:Principle . ?b a od:Recommendation .}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    $res = $graph->allOfType('od:Principle'); 
    foreach ($res as $concept) {
        $name=str_replace("http://opendiscovery.org/rdf/Concept/","",$concept->getUri());
        $out="<h3> $name </h3>";
        $nr73=$concept->get("od:hasAltshuller73Id");
        $nr=$concept->get("od:hasAltshuller84Id");
        $nr03=$concept->get("od:Matrix2003Id");
        if (!empty($nr73)) {$out.="Principle $nr in Matrix-73 <br/>"; }
        if (!empty($nr)) {$out.="Principle $nr in Matrix-84 <br/>"; }
        if (!empty($nr03)) {$out.="Principle $nr in Matrix-2003 <br/>"; }
        $out.="<h4>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</h4>";
        $out.="<h5>".showLanguage($concept->all("skos:altLabel"),"<br/>")."</h5>";
        $b=array();
        foreach ($concept->all("od:hasRecommendation") as $v) {
            $b[]=showLanguage($v->all("od:description"),"<br/>");
        }
        $out.="<h4>Recommendations</h4><p>".join("</p><p>",$b)."</p>";
        $b=array();
        foreach ($concept->all("od:LippertGlossaryNote") as $v) {
            $b[]=$v;
        }
        if (!empty($b)) {
            $out.="<h4>Lippert's Recommendations</h4><ul><li>"
                .join("</li>\n<li>",$b)."</li></ul>";
        }
        if ($nr>0) { $a["$nr"]="<div>\n$out\n</div>\n"; }
        else { $e[]="<div>\n$out\n</div>\n"; }
    }
    ksort($a);
    $out='<h2>The 40 TRIZ Principles</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->
<h2>Additional Principles</h2>
<div class="concept">
'.join("\n", $e).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(thePrinciples());

?>
