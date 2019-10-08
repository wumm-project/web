<?php
/**
 * User: Hans-Gert Gräbe
 * Last Update: 2019-10-08

 * Transformiere Turtle-Datei zurück in csv

 */

require_once 'lib/EasyRdf.php';
require_once 'helper.php';

// output settings
//=========================
ini_set('default_charset', 'utf-8');

function processFile($s) {
    EasyRdf_Namespace::set('tt', 'http://triztrainer.ru/rdf/Model#');
    EasyRdf_Namespace::set('ttr', 'http://triztrainer.ru/rdf/Record/');
    $graph = new EasyRdf_Graph("http://triztrainer.ru/rdf/Records/");
    $graph->parseFile($s);
    $res = $graph->allOfType('tt:Entry');
    $a=array();
    foreach ($res as $v) {
        if ($v->get('tt:sheet')=="100") {
            $uri=$v->getURI();
            $de=$v->getLiteral('tt:text','de');
            $ru=$v->getLiteral('tt:text','ru');
            $a[]="$uri|$ru|$de";
        }
    }
    return join("\n",$a);
}

echo processFile("tt-texts.rdf");


?>

