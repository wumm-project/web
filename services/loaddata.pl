##################################
#
# author: graebe
# lastModified: 2020-11-01

# Changes:

# purpose: load data into the local Virtuoso store
# usage: perl loaddata.pl | isql-vt 1112 dba <YourSecretPassword>

my $RDFData="/local/home/wumm/web/rdf";
#my $RDFData="/home/graebe/git/WUMM/web/rdf";
# print cleardata();
print loaddata();

## end main ## 

sub cleardata { # nicht aktuell
  return <<EOT;
sparql clear graph <http://opendiscovery.org/rdf/People/> ;
EOT
}

sub loaddata {
  my $out;
  $out.=createLoadCommand("http://opendiscovery.org/rdf/People/","People.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MATRIZ-Certificates/","MATRIZ-Certificates.rdf"); 
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Thesaurus/","Thesaurus.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/VDI-Glossary/","VDI-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/OntoCards/","OntoCards.rdf");
  return $out;
}

sub createLoadCommand {
  my ($graph,$file)=@_;
  return<<EOT;
sparql clear graph <$graph> ;
sparql create silent graph <$graph> ; 
DB.DBA.RDF_LOAD_RDFXML_MT (file_to_string_output('$RDFData/$file'),'','$graph'); 
EOT
}


__END__

