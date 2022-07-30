<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-07-30
 */

require_once 'layout.php';

// ------ the main info page

function business() {
    $out='<h2>WUMM Project &ndash; Business TRIZ</h2>

<p>The WUMM database emphasises in particular on developments in the area of
Business Processes, Models, and Business TRIZ and compiled structured
information in that area in semantic web format RDF.  In the publicly
accessible RDF store of the WUMM project, this information can be analysed in
more detail via its <a href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL
endpoint</a>.</p>

<p>Here we compiled for the moment the following demonstrations: 
<ul> 

<li>The <a href="bmp.php">Business Model Patterns</a> from the St. Gallen
Business Model Navigator. </li>

<li>The <a href="bpm.php">Business Process Model Pattern</a> collected at <a
href="http://bpmpatterns.org/">bpmpatterns.org/</a>. </li>

<li>The <a href="businesstrends.php">Business Trends</a>, a transfer of the
development trends for engineering systems proposed by L. Wagner.</li>

<li>The <a href="businessstandards.php">Business Standards</a>, a transfer of
the 76 inventive standards to Business TRIZ proposed by V. Souchkov.</li>
 
</ul> 
';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(business());

?>
