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
    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

describe ?a ?b
from <http://opendiscovery.org/rdf/People/>
from <http://opendiscovery.org/rdf/TRIZ-References/>
where { 
?a a foaf:Person .
?b a od:Reference .
}';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $thebooks=array(); 
    foreach($graph->allOfType('od:TRIZ-Book') as $book) {
        $titel=showLanguage($book->all("dcterms:title"),"<br/>");
        $aut=$book->all("dcterms:creator"); // yet to be fixed to get them in alphabetic order.
        $theAuthors=join("",$aut);
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
