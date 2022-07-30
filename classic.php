<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-07-30
 */

require_once 'layout.php';

// ------ the main info page

function classic() {
    $out='<h2>WUMM Project &ndash; Classical TRIZ Tools</h2>

<p>In the WUMM database, information about TRIZ publications, the TRIZ Body of
Knowledge and also structured information from various sources on classical
TRIZ tools is compiled in the semantic web format RDF.  In the publicly
accessible RDF store of the WUMM project, this information can be analysed in
more detail via its <a href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL
endpoint</a>.</p>

<p>Here we compiled for the moment the following demonstrations: 
<ul> 

<li>A list of <a href="books.php">TRIZ Books</a> (yet to be consolidated).</li>

<li>An RDF version of the <a href="tbk.php">TRIZ Body of Knowledge</a> (yet to
be consolidated, at the moment only the references are listed). </li>

<li><a href="principles.php">40 Principles</a>.</li>

<li><a href="parameters.php">39 Parameters</a> used in the Matrix.  Several <a
href="https://github.com/wumm-project/RDFData/tree/master/Matrix">Versions of
the Matrix</a> are available in JSON format.</li>

<li><a href="standards.php">76 Inventive Standards</a>.</li>

<li>Trends of Engineering Systems Evolution have yet to be added.</li>
 
</ul> 
';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(classic());

?>
