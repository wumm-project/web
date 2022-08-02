<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-07-30
 */

require_once 'layout.php';

// ------ the main info page

function social() {
    $out='<h2>WUMM Project &ndash; A TRIZ Social Network</h2>

<p>In the WUMM database, information on various TRIZ activities is compiled in
the semantic web format RDF. On the one hand, this format requires globally
unique textual representations (URI) for referencing real or mental subjects
or objects as digital identities and, on the other hand, offers the
possibility to assign properties of general interest to these digital
identities as key-value pairs or to compile more general three-word sentences
about them. In the publicly accessible RDF store of the WUMM project, this
information can be analysed in more detail via its <a
href="http://wumm.uni-leipzig.de:8891/sparql">SPARQL endpoint</a>.</p>

<p>In this part of the WUMM project, these concepts are prototypically used to
document selected social activities in the TRIZ community.</p>

<p>For the moment we compiled 
  <ul> 
    <li>a list of <a href="people.php">People involved with TRIZ</a>. It
      consists of an URI and a short information record of each listed
      person. </li>
    <li>short records of <a href="conferences.php">Past TRIZ Conferences</a>.
      For many of these conferences detailed records (authors and their talks,
      partly abstracts, links to papers and presentations) are available, see
      the links in the overview page.
      <ul>
	<li> A <a href="https://wumm-project.github.io/Events.html">list of
	    upcoming events</a> is part of the WUMM github pages.  </li>
      </ul>
    </li>
    <li><a href="presentations.php">Selected TRIZ Presentations</a>.</li>
    <li><a href="videos.php">Selected TRIZ Videos</a>.</li>
    <li>a first RDF version of a part of the <a href="wtsp.php">WTSP
    Metadata</a>.</li>
  </ul>
</p>
';
    return '<div class="container">'.$out.'</div>';
}

echo showpage(social());

?>
