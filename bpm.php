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

construct { ?a a bpm:Pattern; skos:prefLabel ?l; od:relatedPaper ?p;
od:relatedCategory ?c .}

from <http://opendiscovery.org/rdf/BPM-Patterns/>
where { ?a a bpm:Pattern; skos:prefLabel ?l . 
optional {?p a od:BPMPaper; od:toPatternURI ?a . }
optional {?p a od:BPMPaper; od:toCategory ?c . }

}';

    try {
        $graph = $sparql->query($query);
    } catch (Exception $e) {
        print "<div class='error'>".$e->getMessage()."</div>\n";
    }
    $a=array();
    foreach($graph->allOfType('bpm:Pattern') as $bmp) {
        $paper=array();
        foreach ($bmp->all("od:relatedPaper") as $v) {
            $paper[]=str_replace('http://bpmpatterns.org/rdf/','',$v->getURI());
        }
        $cat=array();
        foreach ($bmp->all("od:relatedCategory") as $v) {
            $cat[]=str_replace('http://bpmpatterns.org/rdf/Model#','',$v->getURI());
        }
        $uri=str_replace('http://bpmpatterns.org/rdf/','',$bmp->getURI());
        $out="<h3>".$uri."</h3>";
        $out.="<p>".join(", ",$bmp->all("skos:prefLabel"))."</p>";
        if (!empty($paper)) {
            $out.="<p><strong>References: </strong>".join(", ",$paper)."</p>";
        }
        if (!empty($cat)) {
            $out.="<p><strong>Categories: </strong>".join(", ",$cat)."</p>";
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
