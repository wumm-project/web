<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-04-05
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function theBooks($src,$people) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('dc', 'http://purl.org/dc/elements/1.1/');
    EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Books/');
    $graph->parseFile($src);
    $graph->parseFile($people);
    $thebooks=array(); 
    $res = $graph->allOfType('od:TRIZ-Book');
    foreach ($res as $book) {
        $autoren=getAutoren($book);
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $id=join("",$book->all("dcterms:creator")).$titel;
        $abstract=$book->get("dcterms:abstract");
        $publisher=$book->get("dc:publisher");
        $year=join(", ",$book->all("dcterms:issued"));
        $isbn=join(", ",$book->all("bibo:isbn"));
        $asin=$book->get("bibo:asin");
        $lang=$book->get("dc:language");
        $comment=$book->get("rdfs:comment");
        $out='
<div itemscope itemtype="http://schema.org/Book" class="book">
<!-- ID: '.$id.' -->
  <h4><div itemprop="title" class="title">'.$titel.'</div></h4>
  <div class="author"><strong>Author(s):</strong> '. $autoren.'</div>';
        if ($lang) { 
            $out.='
  <div itemprop="language"><strong>Language:</strong> '.$lang.'</div>';
        }
        if ($publisher) {
            $s=array($publisher);
            if ($year) { $s[]=$year; }
            if ($isbn) { $s[]="ISBN: $isbn"; 
            if ($asin) { $s[]="ASIN: $ain"; }}
            $out.='
  <div itemprop="publisher"><strong>Publisher:</strong> '.join(", ",$s).'</div>';
        }
        if ($abstract) { 
            $out.='
  <div itemprop="description" class="abstract"><p><strong>Description:</strong><br/> '
            . $abstract .'</p></div>';
        }
        if ($comment) { 
            $out.='
  <div itemprop="comment"><strong>Comment:</strong> '.$comment.'</div>';
        }
        $out.='
</div> <!-- end class book -->';
        $thebooks[$id]=$out;
    }
    ksort($thebooks);
    return '
<div class="container">
  <h3>TRIZ Books</h3>
  <div class="books">
'.join("\n<hr/>\n",$thebooks).'
  </div> <!-- end class books -->
</div> <!-- class container >
';
}

$src="rdf/Books.rdf";
$people="rdf/People.rdf";
echo showpage(theBooks($src,$people));

?>
