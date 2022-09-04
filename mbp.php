<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-09-04

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theMBPList() {
    setNamespaces();

    global $sparql;
    $query='
PREFIX od: <http://opendiscovery.org/rdf/Model#>

select ?a ?bl ?d ?i
from <http://opendiscovery.org/rdf/EcoDesignPrinciples/>
from <http://opendiscovery.org/rdf/MBP-EcoDesignPrinciples/>
where { 
?a a od:EDP; od:hasMBPID ?i; skos:definition ?d; od:when ?b . 
?b skos:prefLabel ?bl .
filter regex(?a,"EcoDesignPrinciple/P")
}';
    try {
        $res = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($res as $v) {
        $uri=str_replace('http://opendiscovery.org/rdf/','',$v->a);
        $a[$uri]=displayMBPRow($v);
    }
    ksort($a);
    $out='<h2>The EDP List proposed by Maccioni, Borgianni, Pigosso (2019)</h2>

<p>The following list of EcoDesignPrinciples was extracted mainly from two
overview papers (Russo et al. 2017) and (Vezzoli, Mancini 2008).</p>

<div class="concept">
<table class="table table-bordered">
<tr><th>Id</th><th>Description</th><th>Phase</th></tr>
'.join("\n", $a).'
</table></div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

function displayMBPRow($v) {
    return '<tr><td>'.$v->i.'</td><td>'.$v->d.'</td><td>'
                     .$v->bl."</td></tr>\n";
}

echo showpage(theMBPList());

?>
