<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theVideos($src,$people) 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Presentations/');
    $graph->parseFile($src);
    $graph->parseFile($people);
    $out='';
    $out.='<h3>Videos</h3><div class="videos">';
    $res = $graph->allOfType('od:Youtube-Video');
    foreach ($res as $talk) {
        $autoren=getAutoren($talk);
        $abstract=$talk->get("dcterms:abstract");
        $urlVideo=$talk->get("od:urlVideo");
        $lang=$talk->get("dcterms:language");
        $issued=$talk->get("dcterms:issued");
        $comment=$talk->get("rdfs:comment");
        $out.='<hr/>
<div itemscope itemtype="http://schema.org/CreativeWork" class="talk">
  <h4>
  <div class="referent"><p><strong>Author(s):</strong> '. $autoren.'</p></div></h4>';
        if ($lang) { 
            $out.='
  <div itemprop="language"><strong>Language:</strong> '.$lang.'</div>';
        }
        if ($abstract) { 
            $out.='
  <div itemprop="description" class="abstract"><strong>Description:</strong> '
            . $abstract .'</div>';
        }
        if ($comment) { 
            $out.='
  <div itemprop="comment"><strong>Comment:</strong> '.$comment.'</div>';
        }
        $out.='
  <div class="slides"> <p><a href="'.$urlVideo.'">Link to the Video</a></p> </div>';
        $out.='
</div> <!-- end class presentation -->';
    }
    return '<div class="container">'.$out.'</div>';
}

$src="rdf/Videos.rdf";
$people="rdf/People.rdf";
echo showpage(theVideos($src,$people));

?>
