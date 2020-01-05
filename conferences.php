<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-01-05
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function setNamespaces() {
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
    EasyRdf_Namespace::set('swc', 'http://data.semanticweb.org/ns/swc/ontology#');
}

function getAutoren($node) {
    $s=array();
    foreach ($node->all("dcterms:creator") as $a) {
        $title=$a->get("foaf:title");
        $name=$a->get("foaf:name");
        if (!empty($title)) {
            $name="$title $name" ; 
        }
        array_push($s, '<span itemprop="creator">'.$name.'</span>');
    }
    return join(", ", $s);
}

function theEvent($v,$graph) {
    $label=$v->get("rdfs:label");
    $description=$v->get("ical:description");
    $start=$v->get("ical:dtstart");
    $end=$v->get("ical:dtend");
    $location=$v->get("ical:location");
    $url=$v->all("ical:url");
    $series=$v->get("od:toConferenceSeries");
    $reports=$v->all("od:hasReports");
    $proceedings=$v->all("od:theProceedings");
    $fotos=$v->all("od:theFotos");
    $details=$v->get("od:detailedReport");
    $out='
<li>'.$label.'
<ul>
<li>From '.showDate($start).' until '.showDate($end).'</li>
';
    if ($series) {
        $name=$graph->resource($series)->get("rdfs:label");
        $out.='<li><strong>Conference Series: </strong>'.$name.'</li>';
    }
    if ($location) { 
        $out.='<li><strong>Location: </strong>'.$location.'</li>';
    }
    if ($description) { 
        $out.='<li><strong>Description: </strong>'.$description.'</li>';
    }
    if ($url) { 
        $out.='<li><strong>URL: </strong>'
            .join("<br/> ",array_map('createLink',$url,$url)).'</li>';
    }
    if ($proceedings) { 
        $out.='<li><strong>Proceedings: </strong>'
            .join("<br/> ",array_map('createLink',$proceedings,$proceedings)).'</li>';
    }
    if ($details) {
        $link="conferences.php?conference=$details";
        $out.='<li><strong>'.createLink($link,"Detailed Report").'</strong></li>';
    }
    if ($reports) { 
        $out.='<li><strong>Personal Reports: </strong>'
            .join("<br/> ",array_map('createLink',$reports,$reports)).'</li>';
    }
    if ($fotos) { 
        $out.='<li><strong>Conference Fotos: </strong>'
            .join("<br/> ",array_map('createLink',$fotos,$fotos)).'</li>';
    }
    return $out.'</ul>

';
}

function abstracts($src,$graph) 
{
    $graph->parseFile($src);
    $out='';
    $res = $graph->allOfType('swc:ConferenceEvent');
    foreach ($res as $entry) {
        $out.=theEvent($entry,$graph);
    }
    $out.='<h3>Contributions</h3><div class="talks">';
    $res = $graph->allOfType('od:Talk');
    foreach ($res as $talk) {
        $autoren=getAutoren($talk);
        $presenter=$talk->get("od:presentedBy");
        $titel=showLanguage($talk->all("dcterms:title"),"<br>");
        $abstract=showLanguage($talk->all("dcterms:abstract"),"<p>");
        $section=$talk->get("swc:relatedToEvent");
        $urlPaper=$talk->get("od:urlPaper");
        $urlSlides=$talk->get("od:urlSlides");
        $urlVideo=$talk->get("od:urlVideo");
        $out.='<hr/>
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
            .' width="18px"/>&nbsp;<a href="'.$urlPaper.'">Paper</a> </div>';
        } 
        if ($urlSlides) { 
            $out.='
  <div class="slides"> <img alt="" src="images/13_icon_pdf.gif"'
            .' width="18px"/>&nbsp;<a href="'.$urlSlides.'">Slides</a> </div>';
        } 
        if ($urlVideo) { 
            $out.='
  <div class="video"> <img alt="" src="images/video.png"'
            .' width="18px"/>&nbsp;<a href="'.$urlVideo.'">Video</a> </div>';
        } 
        if ($abstract) { 
            $out.='
  <div itemprop="description" class="abstract"><p><strong>Abstract:</strong><br/> '
            . $abstract .'</p></div>';
        }
        $out.='
</div> <!-- end class talk -->';
    }
    return $out;
}

function generalInfo($graph) {
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
<h3 align="center"> Past TRIZ Conferences </h3>

<p>This web site is generated from the RDF descriptions of different
conferences collected within our 
<a href="https://github.com/wumm-project/RDFData">RDFData subproject</a>. </p>

'; 
    
}

function main() {
    setNamespaces();
    $conf=$_GET["conference"];
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Conference/');
    $graph->parseFile("rdf/People.rdf");
    $graph->parseFile("rdf/ConferenceSeries.rdf");
    $out='';
    if (empty($conf)) { $out=generalInfo($graph); }
    else { $out=abstracts($conf,$graph); }
    return '<div class="container">'.$out.'</div>';
}

echo showpage(main());

// conferences.php?conference=rdf/TRIZ-Summit-2019.rdf&people=rdf/People.rdf

//echo abstracts('../rdf/TRIZ-Summit-2019.rdf','../rdf/People.rdf'); // for testing

?>
