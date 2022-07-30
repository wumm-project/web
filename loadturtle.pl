##################################
#
# author: graebe
# lastModified: 2022-07-30

# Changes:

# purpose: load data in turtle format into the local Virtuoso store
# usage: perl loaddata.pl | isql-vt 1111 dba <YourSecretPassword>

my $RDFData="/home/graebe/git/WUMM/RDFData";
print loaddata();

## end main ## 

sub loaddata {
  my $out;
  # social   
  $out.=createLoadCommand("http://opendiscovery.org/rdf/People/","People.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MATRIZ-Certificates/","MATRIZ-Certificates.ttl");

  # ontology
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Thesaurus/","Ontologies/Thesaurus.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TOP-Glossary/","Ontologies/TOP-Glossary.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Lippert-Glossary/","Ontologies/Lippert-Glossary.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Matvienko-Glossary/","Ontologies/Matvienko-Glossary.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Souchkov-Glossary/","Ontologies/Souchkov-Glossary.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/VDI-Glossary/","Ontologies/VDI-Glossary.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TopLevel/","Ontologies/TopLevel.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/OntoCards/","Ontologies/OntoCards.ttl");

  # TRIZ classics
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Parameters/","Matrix/Parameters.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Principles/","Matrix/Principles.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/StandardSolutions/","Ontologies/StandardSolutions.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/SeparationPrinciples/","Ontologies/SeparationPrinciples.ttl");

  # Business TRIZ
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessModelPatterns/","Business-TRIZ/BusinessModelPatterns.ttl");  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessStandards/","Business-TRIZ/BusinessStandards.ttl");  
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BusinessTrends/","Business-TRIZ/BusinessTrends-Wagner.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/EcoDesignPrinciples/","Business-TRIZ/EcoDesignPrinciples.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MBP-EcoDesignPrinciples/","Business-TRIZ/MBP-EcoDesignPrinciples.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/MannDombExamples/","Business-TRIZ/MannDombExamples.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/SBMPatterns/","Business-TRIZ/SBMPatterns.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/CEBMPatterns/","Business-TRIZ/CEBMPatterns.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/BPM-Patterns/","Business-TRIZ/BPM-Patterns.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/EDP-Tree/","Business-TRIZ/EDP-Tree.ttl");

  # experimental
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Books/","Books.ttl");
  $out.=createLoadCommand("http://opendiscovery.org/rdf/TBK-References/","TBK-References.ttl");
#  $out.=createLoadCommand("http://opendiscovery.org/rdf/FlowDevelopmentPattern/","FlowDevelopmentPattern.ttl");
  return $out;
}

sub loadsomedata {
  my $out;
  $out.=createLoadCommand("http://opendiscovery.org/rdf/Principles/","Matrix/Principles.ttl");
  return $out;
}

sub createLoadCommand {
  my ($graph,$file)=@_;
  return<<EOT;
sparql clear graph <$graph> ;
sparql create silent graph <$graph> ; 
DB.DBA.TTLP_MT (file_to_string_output('$RDFData/$file'),'','$graph'); 
EOT
}


__END__

