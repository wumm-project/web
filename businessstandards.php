<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function theBusinessStandards($input) 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/TheStandards/');
    $graph->parseFile($input);
    $a=array();
    $res = $graph->allOfType('od:TRIZ_Standard');
    foreach ($res as $concept) {
        $uri=str_replace('http://opendiscovery.org/rdf/BusinessStandard/','',$concept->getURI());
        $out="<h3> Standard ".$uri."</h3>";
        $out.="<p>".showLanguage($concept->all("skos:preflabel"),"<br/>")."</p>";
        if ($concept->all("skos:narrower")) {
            $out.="<p> Belongs to Group ".$concept->get("skos:narrower")->get("skos:preflabel")."</p>";
        }
        if ($concept->all("skos:example")) {
            $out.="<h4>Example</h4>".showLanguage($concept->all("skos:example"),"<br/>");
        }
        $a[$uri]="<div>\n$out\n</div>\n";
    }
    ksort($a);
    $out='<h2>The Business Standards proposed by Valeri Souchkov</h2>
<div class="concept">
'.join("\n", $a).'
</div> <!-- end concept list -->';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(theBusinessStandards("rdf/TheBusinessStandards.rdf"));

?>
