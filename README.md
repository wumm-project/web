# WUMM Project - Demonstration Site

## The code

The repository contains the code of a small demo site for some applications
within the WUMM Project.

It uses lightweight PHP scripts based on the Bootstrap Framework and the
EasyRdf PHP Library.

At the moment this website is running at <http://wumm.uni-leipzig.de/>.

## Additional services

Part of the data is uploaded in a [Virtuoso](https://virtuoso.openlinksw.com/)
based RDF store that operates a __SPARQL Endpoint__ at
<http://wumm.uni-leipzig.de:8891/sparql>.

The _services_ directory contains Perl code for maintenance of that endpoint.

## Changes

2020-11-06: Switched to EasyRDF 1.0 and composer.