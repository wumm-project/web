<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-08-31
 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';
require_once 'layout.php';

function theParts($a) {
    $b=array();
    foreach($a as $v) {
        $b[]="<li>".showLanguage($v->all("skos:prefLabel"),", ")."</li>";
    }
    return "<ul>".join("\n",$b)."</ul>";
}

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
        $out="<h3>".showLanguage($concept->all("skos:prefLabel"),", ")."</h3>";
        if ($concept->all("od:hasPart")) {
            $out.="<h4>Has Parts</h4>".theParts($concept->all("od:hasPart"));
        }
        if ($concept->all("od:includes")) {
            $out.="<h4>Includes</h4>".theParts($concept->all("od:includes"));
        }
        if ($concept->all("od:hasPartialCase")) {
            $out.="<h4>Partial Case</h4>".theParts($concept->all("od:hasPartialCase"));
        }
        $a[$concept->getUri()]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The TRIZ Ontology</h2>

<p>This is a RDF version of the main diagram of the <a
href="https://triz-summit.ru/onto_triz/" >TRIZ Ontology Project</a> with labels
in Russian, German and English. </p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theGlossary("rdf/Ontology.rdf"));

?>
