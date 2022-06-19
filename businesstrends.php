<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-06-18
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function getSubTrends($trend) {
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a 
from <http://opendiscovery.org/rdf/BusinessTrends/>
where { ?a a tc:SubTrend; od:isSubtrendOf <'.$trend.'> . }';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('tc:SubTrend') as $subtrend) {
        $a[]=displaySubTrend($subtrend);
    }
    asort($a);
    return join("\n",$a);
}

function displaySubTrend($subtrend) {
    $uri=str_replace('http://opendiscovery.org/rdf/BusinessTrend/','',$subtrend->getURI());
    $out="<h5>".$uri."</h5>";
    $out.="<h6>Note</h6><p>".showLanguage($subtrend->all("skos:note"),"<br/>")."</p>";
    $out.="<h6>Example</h6><p>".listPerLanguage($subtrend,"skos:example")."</p>";
    return $out;
}

function getBMLabels($a) {
    $b=array();
    foreach($a as $v) {
        $b[]=$v->getLiteral("skos:prefLabel","en");
    }
    return $b;
}

function theTrends() 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/>
PREFIX bmp: <http://opendiscovery.org/rdf/BusinessModelPattern/> 

describe ?a ?b
from <http://opendiscovery.org/rdf/BusinessTrends/>
from <http://opendiscovery.org/rdf/BusinessModelPatterns/>
where { ?a a tc:Trend . ?b a od:BusinessModelPattern . }';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('tc:Trend') as $trend) {
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessTrend/','',$trend->getURI());
        $bmp=getBMLabels($trend->all("od:toBusinessModelPattern"));
        $out="<h3>".$uri."</h3>\n";
        $out.="<p>".showLanguage($trend->all("rdfs:label"),"<br/>")."</p>\n";
        $out.="<h4>Definition</h4><p>"
            .showLanguage($trend->all("skos:definition"),"<br/>")."</p>\n";
        $out.="<p><strong>Related Business Models: </strong>".join(", ",$bmp)."</p>\n";
        $out.="<h4>Provocative Questions</h4><p>"
            .listPerLanguage($trend,"od:Provocation")."</p>";
        $out.="<h4>Subtrends</h4><p>".getSubTrends($trend->getURI())."</p>";
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The Business Trends in L. Wagner\'s Master Thesis</h2>

<p>The following Business Model Innovation trends are derived from the TESE
TRIZ Trends of System Evolution in <a
href="https://wumm-project.github.io/Texts/WagnerLuisa-2021.pdf">Luisa
Wagner\'s Master Thesis</a> (in German). </p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theTrends());

?>
