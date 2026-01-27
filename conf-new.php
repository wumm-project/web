<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theEvent($v,$graph) {
    $label=$v->get("schema:name");
    $start=$v->get("schema:startDate");
    $end=$v->get("schema:endDate");
    $description=$v->get("schema:description");
    $about=$v->get("schema:about");
    $loc=$v->get("schema:location");
    if ($loc) { $location=$loc->get("schema:name"); }
    $url=$v->all("schema:url");
    $series=$v->get("od:toConferenceSeries");
    //$proceedings=$v->all("schema:workPresented");
    $out='
<h2>'.$label.'</h2>
<ul>
<li>From '.showDate($start).' until '.showDate($end).'</li>
';
    if ($series) {
        $name=$graph->resource($series)->get("rdfs:label");
        $out.='
<li><strong>Conference Series: </strong>'.$name.'</li>';
    }
    if ($location) { 
        $out.='
<li><strong>Location: </strong>'.$location.'</li>';
    }
    if ($summary) { 
        $out.='
<li><strong>Summary: </strong>'.$summary.'</li>';
    }
    if ($description) { 
        $out.='
<li><strong>Description: </strong>'.$description.'</li>';
    }
    if ($url) { 
        $out.='
<li><strong>URL: </strong>'
        .join("<br/> ",array_map('createLink',$url,$url)).'</li>';
    }
    if ($proceedings) { 
        $out.='
<li><strong>Proceedings: </strong>'
        .join("<br/> ",array_map('createLink',$proceedings,$proceedings)).'</li>';
    }
    if ($details) {
        $link="conferences.php?conference=$details";
        $out.='
<li><strong>'.createLink($link,"Detailed Report").'</strong></li>';
    }
    if ($reports) { 
        $out.='
<li><strong>Personal Reports: </strong>'
        .join("<br/> ",array_map('createLink',$reports,$reports)).'</li>';
    }
    if ($fotos) { 
        $out.='
<li><strong>Conference Fotos: </strong>'
        .join("<br/> ",array_map('createLink',$fotos,$fotos)).'</li>';
    }
    if ($videos) { 
        $out.='
<li><strong>Conference Videos: </strong>'
        .join("<br/> ",array_map('createLink',$videos,$videos)).'</li>';
    }
    return $out.'
</ul>';
}


function generalConferenceInfo($graph) {
    $graph->parseFile("rdf/Konf.rdf");
    $res = $graph->allOfType('schema:Event');
    $a=array();
    foreach ($res as $entry) {
        $start=$entry->get("schema:startDate");
        $a["$start"]=theEvent($entry,$graph);
    }
    krsort($a);
    return theTitle().'<ul>'.join("\n",$a).'</ul>';
}

function theTitle() {    
    return '
<h2 align="center"> Past TRIZ Conferences </h2>

<p>This web site is generated from the RDF descriptions of different
conferences collected within our 
<a href="https://github.com/wumm-project/RDFData">RDFData subproject</a>. </p>

'; 
    
}

function mainConferences() {
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Conference/');
    $graph->parseFile("rdf/ConferenceSeries.rdf");
    $out=generalConferenceInfo($graph); 
    return '<div class="container">'.$out.'</div>';
}

echo showpage(mainConferences());

// echo mainConferences(); // for testing

?>
