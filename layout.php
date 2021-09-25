<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2020-11-07
 */

function pageHeader() {
  return '
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="WUMM Demonstration Site"/>
    <meta name="author" content="The WUMM Project"/>

    <title>WUMM Demonstration Site</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    
  </head>
<!-- end header -->
  <body>

';
}

function pageNavbar() {
  return '

    <!-- Fixed navbar -->
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li> 
            <li><a href="people.php">People</a></li>
            <li><a href="books.php">Books</a></li>
            <li><a href="tbk.php">TRIZ Body of Knowledge</a></li>
            <li><a href="conferences.php">Past Conferences</a></li>
            <li><a href="presentations.php">TRIZ Presentations</a></li>
            <li><a href="ontology.php">TRIZ Ontology</a></li>
            <li><a href="principles.php">Principles</a></li>
            <li><a href="parameters.php">Parameters</a></li>
            <li><a href="standards.php">Standards</a></li>
            <li><a href="businessstandards.php">Business Standards</a></li>
            <li><a href="wtsp.php">WTSP Metadata</a></li>
            <!--<li><a href="test.php">Test</a></li>-->
          </ul>
        </div><!-- navbar end -->
      </div><!-- container end -->
    </nav>
';
}

function generalContent() {
  return '
<div class="container">
  <h1 align="center">Demonstation Site of the 
    <a href="https://wumm-project.github.io">WUMM Social Network</a></h1>
</div>
';
}

function pageFooter() {
  return '

    <div class="container">
      <div class="footer">
        <p class="text-muted">&copy; <a href="http://wumm.uni-leipzig.de">The WUMM Projekt</a> since 2019 </p>
      </div>
    </div>
    <!-- jQuery (necessary for Bootstrap JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    
  </body>
</html>';
}

function showPage($content) {
  return pageHeader().generalContent().pageNavbar().($content).pageFooter();
}
