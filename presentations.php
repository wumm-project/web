<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2019-08-07
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

function thePresentations($src,$people) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Presentations/');
    $graph->parseFile($src);
    $graph->parseFile($people);
    $out='';
    $out.='<h3>Presentations</h3><div class="presentations">';
    $res = $graph->allOfType('od:Presentation');
    foreach ($res as $talk) {
        $autoren=getAutoren($talk);
        $titel=showLanguage($talk->all("dcterms:title"),"<br>");
        $abstract=showLanguage($talk->all("dcterms:abstract"),"<p>");
        $urlSlides=$talk->get("od:urlSlides");
        $size=$talk->get("dcterms:extent");
        $lang=$talk->get("dcterms:language");
        $license=$talk->get("dcterms:rights");
        $issued=$talk->get("dcterms:issued");
        $out.='<hr/>
<div itemscope itemtype="http://schema.org/CreativeWork" class="talk">
  <h4>
  <div itemprop="title" class="title">'.$titel.'</div></h4>
  <div class="referent"><p><strong>Author(s):</strong> '. $autoren.'</p></div>';
        if ($size) { 
            $out.='
  <div itemprop="size"><strong>Size:</strong> '.$size.'</div>';
        }
        if ($lang) { 
            $out.='
  <div itemprop="language"><strong>Language:</strong> '.$lang.'</div>';
        }
        if ($license) { 
            $out.='
  <div itemprop="license"><strong>Legal Notes:</strong> '.$license.'</div>';
        }
        if ($abstract) { 
            $out.='
  <div itemprop="description" class="abstract"><p><strong>Description:</strong><br/> '
            . $abstract .'</p></div>';
        }
        if ($urlSlides) { 
            $out.='
  <div class="slides"> <p><img alt="" src="images/13_icon_pdf.gif"'
            .' width="18px"/>&nbsp;<a href="'.$urlSlides.'">Slides</a></p> </div>';
        } 
        $out.='
</div> <!-- end class presentation -->';
    }
    return htmlEnv($out);
}

function main() {
    $src="rdf/Presentations.rdf";
    $people="rdf/People.rdf";
    return thePresentations($src,$people);
}

echo genericLink().main();

?>
