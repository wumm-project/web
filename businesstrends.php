<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-02-11
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function getProvocations($concept) {
    $out='';
    $b=array();
    foreach($concept->all("od:Provocation","literal","de") as $v) {
        $b[]="<li>$v</li>";
    }
    ksort($b);
    $out.='de:<ul>'.join("\n",$b).'</ul>';
    $b=array();
    foreach($concept->all("od:Provocation","literal","en") as $v) {
        $b[]="<li>$v</li>";
    }
    ksort($b);
    $out.='en:<ul>'.join("\n",$b).'</ul>';
    $b=array();
    foreach($concept->all("od:Provocation","literal","ru") as $v) {
        $b[]="<li>$v</li>";
    }
    ksort($b);
    $out.='ru:<ul>'.join("\n",$b).'</ul>';
    return $out;
}

function getSubTrends($trend,$graph) {
    $a=array();
    foreach($graph->allOfType('tc:SubTrend') as $subtrend) {
        

function theTrends($input) 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/BusinessTrends/');
    $graph->parseFile($input);
    $a=array();
    foreach($graph->allOfType('tc:Trend') as $trend) {
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessTrend/','',$trend->getURI());
        $out="<h3>".$uri."</h3>";
        $out.="<p>".showLanguage($trend->all("rdfs:label"),"<br/>")."</p>";
        $out.="<h4>Definition</h4><p>".showLanguage($trend->all("skos:definition"),"<br/>")."</p>";
        $out.="<h4>Provocative Questions</h4><p>".getProvocations($trend)."</p>";
        $out.="<h4>Subtrends</h4><p>".getSubTrends($trend,$graph)."</p>";
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The Business Trends in L. Wagner\'s Mater Thesis</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theTrends("rdf/BusinessTrends-Wagner.rdf"));

?>
