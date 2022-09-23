<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-09-23

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theCombinedList() {
    setNamespaces();

    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

construct { ?a ?b ?c .}
from <http://opendiscovery.org/rdf/EcoDesignPrinciples/>
from <http://opendiscovery.org/rdf/MBP-EcoDesignPrinciples/>
where { ?a ?b ?c . }
';
    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('od:EDP') as $v) {
        $uri=str_replace('http://opendiscovery.org/rdf/EcoDesignPrinciple/','',$v->getURI());
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
<tr><th>URI</th><th>Description</th><th>What</th><th>When</th><th>Action</th><<th>GenericPrinciple</th></tr>
'.join("\n", $a).'
</table></div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function joinLabels ($u) {


function displayCombinedRow($uri,$v) {
    $d=$v->get("skos:definition");
    $what=joinLabels($v->all("od:what"));
    $when=joinLabels($v->all("od:when"));
    $action=joinLabels($v->all("od:action"));
    $gp=joinLabels($v->all("od:toGenericStrategy"));
    return '<tr>'.'<td><a href="displayuri.php?uri='.$uri.'">'.$uri.'</a></td><td>'
                 .$d.'</td><td>'.$what.'</td><td>'
                 .$when.'</td><td>'.$action.'</td><td>'
                 .$gp."</td></tr>\n";
}

echo showpage(theCombinedList());

?>
