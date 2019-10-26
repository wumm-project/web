<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2019-10-26

 * Save the result of the TRIZ Trainer Translation Project as CSV

 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

function TranslationsAsCSV($s) {
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
        $a[]="$uri|$ru|$de";
    }
    return join("\n",$a);
}

echo TranslationsAsCSV("tt-texts.rdf");
