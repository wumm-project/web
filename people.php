<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-06
 */

require 'vendor/autoload.php';
require_once 'helper.php';
require_once 'layout.php';

function thePeople() 
{
    setNamespaces();
    $graph = new \EasyRdf\Graph('http://opendiscovery.org/rdf/People/');
    $graph->parseFile("rdf/People.rdf");
    $graph->parseFile("rdf/MATRIZ-Certificates.rdf");
    $a=array();
    foreach ($graph->allOfType('foaf:Person') as $autor) {
        $b=array();
        $cert=getCertificate($graph,$autor->getURI());
        foreach ($autor->all("foaf:name") as $e) {
            $b[]='<strong><span itemprop="name" class="foaf:name">'
                .$e->getValue().'</span></strong>';
        }
        foreach ($autor->all("foaf:affil") as $e) {
            $b[]='<span itemprop="affiliation" class="foaf:affil">'
                .$e->getValue().'</span>';
        }
        foreach ($autor->all("foaf:homepage") as $e) {
            $b[]=createLink($e,$e);
        }
        if (!empty($cert)) { $b[]="MATRIZ Certificates: $cert"; }
        $a[$autor->getUri()]=
            '<div itemscope itemtype="http://schema.org/Person" class="creator">'
            .join('<br/>',$b).'</p></div>';
    }
    ksort($a);
    $out='<h3>People in the TRIZ Social Network</h3>
<div class="people">
'.join("\n", $a).'
</div> <!-- end class people -->';
    return '<div class="container">'.$out.'</div>';
}

function getCertificate($graph,$author) {
    $s=array();
    foreach ($graph->allOfType('od:CertificateLevel4') as $c) {
        if (strcmp($c->get("od:owner"),$author)==0) {
            $s[]=str_replace("http://opendiscovery.org/rdf/Certificate/","",
            $c->getURI());
        }
    }
    foreach ($graph->allOfType('od:CertificateLevel5') as $c) {
        if (strcmp($c->get("od:owner"),$author)==0) {
            $s[]=str_replace("http://opendiscovery.org/rdf/Certificate/","",
            $c->getURI());
        }
    }
    return join(", ",$s);
}


echo showpage(thePeople());

?>
