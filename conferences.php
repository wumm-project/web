<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2019-07-09
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

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

function theEvent($v) {
    $label=$v->get("rdfs:label");
    $description=$v->get("ical:description");
    $start=$v->get("ical:dtstart");
    $end=$v->get("ical:dtend");
    $location=$v->get("ical:location");
    $url=$v->get("ical:url");
    return '
<h1>'.$label.'</h1>
<dl>
<dt>From '.$start.' until '.$end.'</dt>
<dt><strong>Location: </strong>'.$location.'</dt>
<dt><strong>URL: </strong>'.createLink($url,$url).'</dt>
<dt><strong>Description: </strong>'.$description.'</dt>

';
}

function abstracts($src,$people) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
    EasyRdf_Namespace::set('swc', 'http://data.semanticweb.org/ns/swc/ontology#');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Conference/');
    $graph->parseFile($src);
    $graph->parseFile($people);
    $out='';
    $res = $graph->allOfType('swc:ConferenceEvent');
    foreach ($res as $entry) {
        $out.=theEvent($entry);
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
    return htmlEnv($out);
}

function generalInfo() {
    return '
This web site lists RDF descriptions of different conferences collected within
our RDFData Conferences subdirectory. At the moment report on the following
conferences are available.

<ul>
<li> <a href="conferences.php?conference=rdf/TRIZ-Summit-2019.rdf&people=rdf/People.rdf">
TRIZ Summit 2019 in Minsk</a></li>
</ul>
'; 
    
}

function main() {
    $conf=$_GET["conference"];
    $people=$_GET["people"];
    if (empty($conf)) { return generalInfo(); }
    return abstracts($conf,$people);
    
}

echo genericLink().main();

// conferences.php?conference=rdf/TRIZ-Summit-2019.rdf&people=rdf/People.rdf

//echo abstracts('../rdf/TRIZ-Summit-2019.rdf','../rdf/People.rdf'); // for testing

?>
