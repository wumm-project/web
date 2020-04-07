<?php

/**
 * User: Hans-Gert Gräbe
 * last update: 2020-04-07
 */

/* ======= helper function ======== */

function htmlEnv($out) 
{
    return '
<HTML>
<HEAD>
  <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
</HEAD><BODY>
'.$out.'
</BODY></HTML>
';
}

function fixEncoding($out) {
    return str_replace(
        array("„","“","–"), array("&#8222","&#8221","&ndash;"), $out
    );
}

function showLanguage($a,$sep) {
    $out='';
    $b=array();
    foreach($a as $v) {
        $l=$v->getLang();
        $b[]="$l: $v";
    }
    return join($sep,$b);
}

function createLink($url,$text) {
    return '<a href='.$url.'>'.$text.'</a>';
}

function createUniLink($url) {
    return '<a href='.$url.'>'.$url.'</a>';
}

function genericLink() {
    return '
<h4>This web site is part of the <a href="http://wumm.uni-leipzig.de">WUMM Demonstration Project</a></h4>
';
}

function showDate($s) {
    return date("D d M Y",strtotime($s));
}

function multiline($s) {
    return str_replace("\n","<br/>",$s);
}

/* ============== display blocks ============= */

function getAutoren($node) {
    $s=array();
    foreach ($node->all("dcterms:creator") as $a) {
        $title=$a->get("foaf:title");
        $name=$a->get("foaf:name");
        if (!empty($title)) {
            $name="$title $name" ; 
        }
        array_push($s, '<span itemprop="creator">'.$name.'</span>');
    }
    return join(", ", $s);
}
