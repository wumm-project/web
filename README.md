# WUMM Project - Demonstration Site

## The code

The repository contains the code of a small demo site for some applications
within the WUMM Project.

It uses lightweight PHP scripts based on the Bootstrap Framework and the
EasyRdf PHP Library.

At the moment this website is running at <http://wumm.uni-leipzig.de/>.

## The data

In the _rdf_ subdirectory the RDF Data required to operate the scripts is
stored as rdf/xml files (faster loaded by EasyRDF).  A _Makefile_ is used to
transform Turtle files in the WUMM/RDFData repository to this data. The
transformed data is stored in this repository.

Part of the data is uploaded in a [Virtuoso](https://virtuoso.openlinksw.com/)
based RDF store that operates a __SPARQL Endpoint__ at
<http://wumm.uni-leipzig.de:8891/sparql>.

To upload the data the Perl script _loadturtle.pl_ can be used. It produces
the Virtuoso load commands to STDOUT to load the required data directly from
the Turtle files in the WUMM/RDFData repository. An environment variable
WUMM_RDFDATA has to be set to find this repository both in the development and
production environments.

## Changes

- 2020-11-06: Switched to EasyRDF 1.0 and composer.
- 2022-08-01: Upload to the RDF store changed to loadturtle.pl

