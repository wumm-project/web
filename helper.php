<?php

/**
 * User: Hans-Gert Gräbe
 * last update: 2020-10-28
 */

/* ======= helper function ======== */

function setNamespaces() {
    EasyRdf_Namespace::set('bibo', 'http://purl.org/ontology/bibo/');
    EasyRdf_Namespace::set('dc', 'http://purl.org/dc/elements/1.1/');
    EasyRdf_Namespace::set('dcterms', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('ical', 'http://www.w3.org/2002/12/cal/ical#');
    EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    EasyRdf_Namespace::set('swc', 'http://data.semanticweb.org/ns/swc/ontology#');
    EasyRdf_Namespace::set('od', 'http://opendiscovery.org/rdf/Model#');
    EasyRdf_Namespace::set('odp', 'http://opendiscovery.org/rdf/Person/');
    EasyRdf_Namespace::set('tc', 'http://opendiscovery.org/rdf/Concept/');
}

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
        if (empty($l)) { $l='en'; }
        $b[$l]="$l: $v";
    }
    ksort($b);
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

function listBook($book) {
    $autoren=getAutoren($book);
    $titel=showLanguage($book->all("dcterms:title"),"<br/>");
    $id=join("",$book->all("dcterms:creator")).$titel;
    $abstract=multiline($book->get("dcterms:abstract"));
    $publisher=$book->get("dc:publisher");
    $year=join(", ",$book->all("dcterms:issued"));
    $isbn=join(", ",$book->all("bibo:isbn"));
    $asin=$book->get("bibo:asin");
    $lang=$book->get("dc:language");
    $url=join(", ",array_map('createUniLink',$book->all("od:relatedLinks")));
    $comment=$book->get("rdfs:comment");
    $out='
<div itemscope itemtype="http://schema.org/Book" class="book">
<!-- ID: '.$id.' -->
  <h4><div itemprop="title" class="title">'.$titel.'</div></h4>
  <div class="author"><strong>Author(s):</strong> '. $autoren.'</div>';
    if ($lang) { 
        $out.='
  <div itemprop="language"><strong>Language:</strong> '.$lang.'</div>';
    }
    if ($publisher) {
        $s=array($publisher);
        if ($year) { $s[]=$year; }
        if ($isbn) { $s[]="ISBN: $isbn";} 
        if ($asin) { $s[]="ASIN: $ain"; }
        $out.='
  <div itemprop="publisher"><strong>Publisher:</strong> '.join(", ",$s).'</div>';
    }
    if ($abstract) { 
        $out.='
  <div itemprop="description" class="abstract"><p><strong>Description:</strong><br/> '
        . $abstract .'</p></div>';
    }
    if ($url) { 
        $out.='
  <div><strong>Links:</strong> '.$url.'</div>';
    }
    if ($comment) { 
        $out.='
  <div itemprop="comment"><strong>Comment:</strong> '.$comment.'</div>';
    }
    return $out;
}
