##################################
#
# author: graebe
# lastModified: 2022-02-12

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
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MATRIZMembers/","MATRIZMembers.rdf");
  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Thesaurus/","Thesaurus.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TOP-Glossary/","TOP-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Lippert-Glossary/","Lippert-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Matvienko-Glossary/","Matvienko-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Souchkov-Glossary/","Souchkov-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/VDI-Glossary/","VDI-Glossary.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Parameters/","Parameters.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Principles/","Principles.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TopLevel/","TopLevel.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/OntoCards/","OntoCards.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/StandardSolutions/","StandardSolutions.rdf");
  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessStandards/","BusinessStandards.rdf");  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessTrends/","BusinessTrends-Wagner.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Books/","Books.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TBK-References/","TBK-References.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/FlowDevelopmentPattern/","FlowDevelopmentPattern.rdf");
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

