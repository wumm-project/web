<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-12-18
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function thePrinciples() 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/ThePrinciples/');
    $graph->parseFile("rdf/Principles.rdf");
    $a=array(); $e=array();
    $res = $graph->allOfType('od:Principle');
    foreach ($res as $concept) {
        $name=str_replace("http://opendiscovery.org/rdf/Concept/","",$concept->getUri());
        $nr=$concept->get("od:hasAltshuller84Id");
        $out="<h3> Principle $nr: $name </h3>";
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
