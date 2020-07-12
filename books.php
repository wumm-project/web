<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-06-29
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
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $id=join("",$book->all("dcterms:creator")).$book->get("dcterms:issued").$titel;
        $thebooks[$id]=listBook($book);
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
