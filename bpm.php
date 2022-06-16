<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-06-16
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theBusinessProcessModelPatterns() 
{
    setNamespaces();
    \EasyRdf\RdfNamespace::set('bpm', 'http://bpmpatterns.org/rdf/Model#');
    \EasyRdf\RdfNamespace::set('bpmp', 'http://bpmpatterns.org/rdf/Pattern/');
    global $sparql;
    $query='
PREFIX skos: <http://www.w3.org/2004/02/skos/core#> 
PREFIX od: <http://opendiscovery.org/rdf/Model#>
PREFIX bpm: <http://bpmpatterns.org/rdf/Model#> 
PREFIX bpmp: <http://bpmpatterns.org/rdf/Pattern/> 

construct { ?a a bpm:Pattern; skos:prefLabel ?l; od:relatedPaper ?p .}

from <http://opendiscovery.org/rdf/BPM-Patterns/>
where { ?a a bpm:Pattern; skos:prefLabel ?l . ?p a od:BPMPaper; od:toPatternURI ?a .}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('bpm:Pattern') as $bmp) {
        $dim=array();
        foreach ($bmp->all("od:relatedPaper") as $v) {
            $dim[]=str_replace('http://bpmpatterns.org/rdf/','',$v->getURI());
        }
        $uri=str_replace('http://bpmpatterns.org/rdf/','',$bmp->getURI());
        $out="<h3>".$uri."</h3>";
        $out.="<p>".showLanguage($bmp->all("skos:prefLabel"),"<br/>")."</p>";
        if (!empty($dim)) {
            $out.="<h4>References</h4><ul><li>".join("</li>\n<li>",$dim)."</li></ul>";
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>Business Process Model Patterns</h2>

<p>The following Business Process Model Patterns are derived from <a
href="http://bpmpatterns.org/">bpmpatterns.org</a>.</p>

<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theBusinessProcessModelPatterns());

?>
