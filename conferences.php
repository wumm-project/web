<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theEvent($v,$graph) {
    $label=$v->get("rdfs:label");
    $summary=$v->get("ical:summary");
    $description=$v->get("ical:description");
    $start=$v->get("ical:dtstart");
    $end=$v->get("ical:dtend");
    $location=$v->get("ical:location");
    $url=$v->all("ical:url");
    $series=$v->get("od:toConferenceSeries");
    $reports=$v->all("od:hasReports");
    $proceedings=$v->all("od:theProceedings");
    $fotos=$v->all("od:theFotos");
    $videos=$v->all("od:theVideos");
    $details=$v->get("od:detailedReport");
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

function showTalk($talk) {
    $autoren=getAutoren($talk);
    $presenter=$talk->get("od:presentedBy");
    $titel=showLanguage($talk->all("dcterms:title"),"<br/>");
    $abstract=showLanguage($talk->all("dcterms:abstract"),"<p>");
    $section=$talk->get("swc:relatedToEvent");
    $a=array();
    foreach ($talk->all("od:urlPaper") as $v) {
        $a[]=createLink($v,"Paper");
    }
    $urlPaper=join(", ",$a);
    $a=array();
    foreach ($talk->all("od:urlPreprint") as $v) {
        $a[]=createLink($v,"Preprint");
    }
    $urlPreprint=join(", ",$a);
    $a=array();
    foreach ($talk->all("od:urlSlides") as $v) {
        $a[]=createLink($v,"Slides");
    }
    $urlSlides=join(", ",$a);
    $a=array();
    foreach ($talk->all("od:urlVideo") as $v) {
        $a[]=createLink($v,"Video");
    }
    $urlVideo=join(", ",$a);
    $out='
<div itemscope itemtype="http://schema.org/CreativeWork" class="talk">
  <h4>
  <div itemprop="title" class="talktitle">'.$titel.'</div></h4>
  <div class="referent"><p><strong>Author(s):</strong> '. $autoren.'</p></div>';
    if ($presenter) { 
        $out.='
  <div class="presenter"><p><strong>Presented by:</strong> <span itemprop="creator">'
        . $presenter->get("foaf:name") .'</span></p></div>';
    }
    if ($section) { 
        $out.='
  <div class="section"><p><strong>Track:</strong> '
        . $section->get("rdfs:label") .'</p></div>';
    }
    if ($urlPaper) { 
        $out.='
  <div class="paper"> <img alt="" src="images/13_icon_pdf.gif"'
        .' width="18px"/>&nbsp;'.$urlPaper.' </div>';
    } 
    if ($urlPreprint) { 
        $out.='
  <div class="preprint"> <img alt="" src="images/13_icon_pdf.gif"'
        .' width="18px"/>&nbsp;'.$urlPreprint.' </div>';
    } 
    if ($urlSlides) { 
        $out.='
  <div class="slides"> <img alt="" src="images/13_icon_pdf.gif"'
        .' width="18px"/>&nbsp;'.$urlSlides.' </div>';
    } 
    if ($urlVideo) { 
        $out.='
  <div class="video"> <img alt="" src="images/video.png"'
        .' width="18px"/>&nbsp;'.$urlVideo.' </div>';
    } 
    if ($abstract) { 
        $out.='
  <div itemprop="description" class="abstract"><p><strong>Abstract:</strong><br/> '
        . $abstract .'</p></div>';
    }
    $out.='
</div> <!-- end class talk -->';
    return $out;
}
function showPaper($talk) {
    $autoren=getAutoren($talk);
    $titel=showLanguage($talk->all("dcterms:title"),"<br>");
    $abstract=showLanguage($talk->all("dcterms:abstract"),"<p>");
    $section=$talk->get("swc:relatedToEvent");
    $url=$talk->get("dcterms:source");
    $out='
<div itemscope itemtype="http://schema.org/CreativeWork" class="talk">
  <h4>
  <div itemprop="title" class="talktitle">'.$titel.'</div></h4>
  <div class="referent"><p><strong>Author(s):</strong> '. $autoren.'</p></div>';
    if ($section) { 
        $out.='
  <div class="section"><p><strong>Track:</strong> '
        . $section->get("rdfs:label") .'</p></div>';
    }
    if ($url) { 
        $out.='
  <div class="paper"> <img alt="" src="images/13_icon_pdf.gif"'
        .' width="18px"/>&nbsp;<a href="'.$url.'">Full Text</a> </div>';
    } 
    if ($abstract) { 
        $out.='
  <div itemprop="description" class="abstract"><p><strong>Abstract:</strong><br/> '
        . $abstract .'</p></div>';
    }
    $out.='
</div> <!-- end class talk -->';
    return $out;
}

function abstracts($src,$graph) {
    $graph->parseFile($src);
    $out='';
    $res = $graph->allOfType('swc:ConferenceEvent');
    foreach ($res as $entry) {
        $out.=theEvent($entry,$graph);
    }
    $out.='<h3>Contributions</h3><div class="talks">';
    $s=array();
    $res = $graph->allOfType('od:Talk');
    foreach ($res as $talk) { $s[]=showTalk($talk); }
    $res = $graph->allOfType('od:Paper');
    foreach ($res as $talk) { $s[]=showPaper($talk); }
    return $out.join("<hr/>\n",$s).'
</div> <!-- end class talks -->
';
}

function generalConferenceInfo($graph) {
    $graph->parseFile("rdf/PastConferences.rdf");
    $res = $graph->allOfType('swc:ConferenceEvent');
    $a=array();
    foreach ($res as $entry) {
        $start=$entry->get("ical:dtstart");
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
    $conf=$_GET["conference"];
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Conference/');
    $graph->parseFile("rdf/People.rdf");
    $graph->parseFile("rdf/ConferenceSeries.rdf");
    $out='';
    if (empty($conf)) { $out=generalConferenceInfo($graph); }
    else { $out=abstracts($conf,$graph); }
    return '<div class="container">'.$out.'</div>';
}

echo showpage(mainConferences());

// conferences.php?conference=rdf/TRIZ-Summit-2019.rdf&people=rdf/People.rdf

// echo abstracts('../rdf/TRIZ-Summit-2019.rdf','../rdf/People.rdf'); // for testing

?>
