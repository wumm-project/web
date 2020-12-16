<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-12-16
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theBooks($author) 
{
    setNamespaces();
    //echo "Author is $author";
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/Books/');
    $graph->parseFile('rdf/Books.rdf');
    $graph->parseFile('rdf/People.rdf');
    $graph->parseFile('rdf/TBK-References.rdf');
    $thebooks=array(); 
    $res = $graph->allOfType('od:TRIZ-Book');
    foreach ($res as $book) {
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $theAuthors=join("",$book->all("dcterms:creator"));
        $id=$theAuthors.$book->get("dcterms:issued").$titel;
        if (empty($author) or strpos($theAuthors,$author)) {
            $thebooks[$id]=listBook($book);
        }
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

$author=$_GET["author"];
echo showpage(theBooks($author));

?>
