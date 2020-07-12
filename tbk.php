<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-06-29
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function theTBK($src,$people) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('dc', 'http://purl.org/dc/elements/1.1/');
    EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Books/');
    $graph->parseFile($src);
    $graph->parseFile($people);
    $theBooks=array(); 
    $res = $graph->allOfType('od:TRIZ-Book');
    foreach ($res as $book) {
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $id=join("",$book->all("dcterms:creator")).$book->get("dcterms:issued").$titel;
        $theBooks[$id]=listBook($book)."<p>Referenced in ".getReferences($book)."</p>" ;
    }
    $thePapers=array(); 
    $res = $graph->allOfType('od:Reference');
    foreach ($res as $book) {
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $id=join("",$book->all("dcterms:creator")).$book->get("dcterms:issued").$titel;
        $thePapers[$id]=listBook($book)."<p>Referenced in ".getReferences($book)."</p>";
    }
    ksort($theBooks);
    ksort($thePapers);
    return '
<div class="container">
  <h3>TRIZ Books</h3>
  <div class="books">
'.join("\n<hr/>\n",$theBooks).'
  </div> <!-- end class books -->
  <h3>TRIZ Papers</h3>
  <div class="papers">
'.join("\n<hr/>\n",$thePapers).'
  </div> <!-- end class papers -->
</div> <!-- class container >
';
}

function getReferences($book) {
    $a=array();
    foreach($book->all("od:hatVerweis") as $v) {
        $v=str_replace("http://opendiscovery.org/rdf/","",$v);
        $v=str_replace("Verweis.","",$v);
        $a[]=$v;
    }
    return join(", ",$a);
}

$src="rdf/TBK-References.rdf";
$people="rdf/People.rdf";
echo showpage(theTBK($src,$people));

?>
