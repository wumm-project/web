<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-01-04
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function theGlossary($input) 
{
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    $graph = new EasyRdf_Graph('http://opendiscovery.org/rdf/Glossary/');
    $graph->parseFile($input);
    $a=array();
    $res = $graph->allOfType('skos:Concept');
    foreach ($res as $concept) {
        $out="<h3>".showLanguage($concept->all("skos:prefLabel"),"<br/>")."</h3>";
        $out.="<h4>Definition</h4>".showLanguage($concept->all("skos:definition"),"<br/>");
        if ($concept->all("skos:note")) {
            $out.="<h4>Notes</h4>".showLanguage($concept->all("skos:note"),"<br/>");
        }
        if ($concept->all("skos:example")) {
            $out.="<h4>Examples</h4>".showLanguage($concept->all("skos:example"),"<br/>");
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The TRIZ Glossary in the VDI norm 4521 </h2>

<p>This is a version of the glossary of the "VDI norm 4521 Blatt 1"
transformed into RDF and enriched with a Russian version.</p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theGlossary("rdf/Glossary.rdf"));

?>
