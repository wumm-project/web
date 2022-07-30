##################################
#
# author: graebe
# lastModified: 2022-06-19

# Changes:

# purpose: load data in rdf/xml format into the WUMM Virtuoso store
# usage: perl loaddata.pl | isql-vt 1112 dba <YourSecretPassword>

my $RDFData="/local/home/wumm/web/rdf";
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
  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessModelPatterns/","BusinessModelPatterns.rdf");  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessStandards/","BusinessStandards.rdf");  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessTrends/","BusinessTrends-Wagner.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/EcoDesignPrinciples/","EcoDesignPrinciples.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MBP-EcoDesignPrinciples/","MBP-EcoDesignPrinciples.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MannDombExamples/","MannDombExamples.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/SBMPatterns/","SBMPatterns.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/CEBMPatterns/","CEBMPatterns.rdf");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BPM-Patterns/","BPM-Patterns.rdf");

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

