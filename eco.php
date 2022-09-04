<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-08-14
 */

require_once 'layout.php';

// ------ the main info page

function eco() {
    $out='<h2>WUMM Project &ndash; Eco Design and Business Models</h2>

<p>The WUMM database emphasises in particular on developments in the area of
Sustainable Business Models and related questions and compiled structured
information in that area in semantic web format RDF.  In the publicly
accessible RDF store of the WUMM project, this information can be analysed in
more detail via its <a href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL
endpoint</a>.</p>

<p>Here we compiled for the moment the following demonstrations: <ul>

<li>The <a href="mbp.php">List of EcoDesignPrinciples</a> considered in
(Maccioni, Borgianni, Pigosso 2019). </li>
 
<li>A <a href="edp-tree.php">Tree of EcoDesignPrinciples</a> proposed by
Davide Russo and Christian Spreafico. </li>

<li>A <a href="edp-combined.php">Combined List of EcoDesignPrinciples</a> from
(Russo, Spreafico 2020) and (Maccioni, Borgianni, Pigosso 2019). </li>
 
</ul> 
';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(eco());

?>
