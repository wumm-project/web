<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2021-09-25

 * Some Metadata from the WTSP Project of Toru Nakagawa. 

 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function metadata() {
    setNamespaces();
    \EasyRdf\RdfNamespace::set('', 'https://www.osaka-gu.ac.jp/php/nakagawa/WSTP/');
    $graph = new \EasyRdf\Graph("http://opendiscovery.org/rdf/WTSP/");
    $graph->parseFile("rdf/WTSP.rdf");
    $res = $graph->allOfType('od:WSTPEntry');
    $a=array();
    foreach ($res as $v) {
        $uri=str_replace('https://www.osaka-gu.ac.jp/php/nakagawa/WSTP/',
                         '',$v->getURI());
        $link=$v->get('od:hasLink');
        $title=$v->get('rdfs:label');
        $a[$uri]='<li>'.createlink($link,$uri).": $title</li>";
    }
    return '<ol>'.join("\n",$a).'</ol>';
}

function WTSP() {
    $out='<h3> Metadata from the WTSP Project</h3>

<p>This is a first version of metadata to ease the navigation within the WTSP
project of Toru Nakagawa. WTSP stands for "World TRIZ-related Sites Project".
The project initiator was awarded as <a
href="https://matriz.org/triz-champion/">TRIZ Champion</a> in 2021. For more
information about the goals, mission and history we refer to the <a
href="https://www.osaka-gu.ac.jp/php/nakagawa/TRIZ/eTRIZ/eWTSP/eWTSP-A1-Policies.html">website
of the project</a>. </p>

<h3> Sites listed in the WTSP Project</h3>
'.metadata();
    return '<div class="container">'.$out.'</div>';
}

echo showpage(WTSP());

?>

