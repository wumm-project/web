<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2019-10-09

 * Dislaying the result of the TRIZ Trainer Translation Project 

 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

// output settings
//=========================
ini_set('default_charset', 'utf-8');

function displayTranslations($s) {
    EasyRdf_Namespace::set('tt', 'http://triztrainer.ru/rdf/Model#');
    EasyRdf_Namespace::set('ttr', 'http://triztrainer.ru/rdf/Record/');
    $graph = new EasyRdf_Graph("http://triztrainer.ru/rdf/Records/");
    $graph->parseFile($s);
    $res = $graph->allOfType('tt:Entry');
    $a=array();
    foreach ($res as $v) {
        $uri=str_replace('http://triztrainer.ru/rdf/Record/','',$v->getURI());
        $de=$v->getLiteral('tt:text','de');
        $ru=$v->getLiteral('tt:text','ru');
        // $a[]="$uri|$ru|$de";
        $a[]="<tr><td>$uri</td><td>$ru</td><td>$de</td><tr>";
    }
    return join("\n",$a);
}

function main() {
    $src="rdf/tt-texts.rdf";
    $people="rdf/People.rdf";
    $out='<h2> The TRIZ Trainer Translation Project</h2>

<p>This is an experimental setting only. </p>

<table align="center" border="2" width="90%">
<tr><th> ID </th><th> Russian </th><th> German </th></tr>
'.displayTranslations($src).'
</table>
';

    return htmlEnv($out);
}

echo main();


?>

