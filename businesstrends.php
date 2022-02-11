<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-02-11
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function listPerLanguage($concept,$item) {
    $lang=array();
    foreach($concept->all($item) as $v) {
        $l=$v->getLang();
        if (empty($l)) { $l='en'; }
        $lang[$l]=1;
    }
    ksort($lang);
    $out='';
    foreach($lang as $l => $value) {
        $b=array();
        foreach($concept->all($item,"literal",$l) as $v) {
            $b[]="<li>$v</li>";
        }
        ksort($b);
        $out.="$l:<ul>".join("\n",$b).'</ul>';
    }
    return $out;
}

function getSubTrends($trend) {
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a
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

function theTrends($input) 
{
    setNamespaces();
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX tc: <http://opendiscovery.org/rdf/Concept/> 

describe ?a
where { ?a a tc:Trend . }';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('tc:Trend') as $trend) {
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessTrend/','',$trend->getURI());
        $out="<h3>".$uri."</h3>";
        $out.="<p>".showLanguage($trend->all("rdfs:label"),"<br/>")."</p>";
        $out.="<h4>Definition</h4><p>".showLanguage($trend->all("skos:definition"),"<br/>")."</p>";
        $out.="<h4>Provocative Questions</h4><p>".listPerLanguage($trend,"od:Provocation")."</p>";
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

echo showpage(theTrends("rdf/BusinessTrends-Wagner.rdf"));

?>
