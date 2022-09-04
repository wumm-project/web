<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-09-04

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theCombinedList() {
    setNamespaces();

    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

select ?a ?d ?what ?when ?action
from <http://opendiscovery.org/rdf/EcoDesignPrinciples/>
from <http://opendiscovery.org/rdf/MBP-EcoDesignPrinciples/>
where {
?a a od:EDP ; skos:definition ?d .
optional { ?a od:what ?w1 . ?w1 skos:prefLabel ?what . }
optional { ?a od:when ?w2 . ?w2 skos:prefLabel  ?when . }
optional { ?a od:action ?w3 . ?w3 skos:prefLabel  ?action . } 
}';
    try {
        $res = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($res as $v) {
        $uri=str_replace('http://opendiscovery.org/rdf/EcoDesignPrinciple/','',$v->a);
        $a[$uri]=displayCombinedRow($uri,$v);
    }
    ksort($a);
    $out='<h2>A Combined List of EcoDesignPrinciples</h2>

<p>The following combined list of EcoDesignPrinciples was extracted from the
overview papers (Russo, Spreafico 2020) and (Maccioni, Borgianni, Pigosso 2019).</p>

<p>This list serves only as proof of concept to demonstrate the potential of
the RDF backed WUMM database.  The links in the table point to a relevant part
of the complete RDF information in the database. </p>

<div class="concept">
<table class="table table-bordered">
<tr><th>URI</th><th>Description</th><th>What</th><th>When</th><th>Action</th></tr>
'.join("\n", $a).'
</table></div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayCombinedRow($uri,$v) {
    return '<tr>'.'<td><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></td><td>'
                 .$v->d.'</td><td>'.$v->what.'</td><td>'
                 .$v->when.'</td><td>'.$v->action."</td></tr>\n";
}

echo showpage(theCombinedList());

?>
