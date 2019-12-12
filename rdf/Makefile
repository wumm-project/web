all:
	rapper -g ../../RDFData/People.ttl -o rdfxml-abbrev >People.rdf
	rapper -g ../../RDFData/Conferences/TRIZ-Summit-2019.ttl -o rdfxml-abbrev >TRIZ-Summit-2019.rdf
	rapper -g ../../RDFData/Glossary.ttl -o rdfxml-abbrev >Glossary.rdf
	rapper -g ../../RDFData/Presentations.ttl -o rdfxml-abbrev >Presentations.rdf
	rapper -g ../../RDFData/ThePrinciples.ttl -o rdfxml-abbrev >Principles.rdf
	rapper -g ../../RDFData/StandardSolutions.ttl -o rdfxml-abbrev >StandardSolutions.rdf
	rapper -g ../../WUMM-intern/Projekte/TRIZ-Trainer/tt-texts.ttl -o rdfxml-abbrev >tt-texts.rdf

	php ../ttAsCSV.php >tt-texts.csv
