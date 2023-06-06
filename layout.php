<?php
/**
 * User: Hans-Gert Gräbe
 * last update: 2022-07-30
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="navbar-nav me-auto mb-1 mb-lg-0">
            <li class="nav-item">
	      <a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item">
	      <a class="nav-link" href="social.php">TRIZ Social
		Network</a></li>
            <li class="nav-item">
	      <a class="nav-link" href="classic.php">Classical TRIZ</a></li>
            <li class="nav-item">
	      <a class="nav-link" href="ontology.php">TRIZ Ontology</a></li>
            <li class="nav-item">
	      <a class="nav-link" href="business.php">Business TRIZ</a></li>
            <li class="nav-item">
	      <a class="nav-link" href="eco.php">Eco Design and Business
		Models</a></li>
          </ul>
	</div><!-- navbar end -->
      </div><!-- container end -->
    </nav>
';
}

function generalContent() {
  return '
<div class="container">
  <h1 align="center">Demonstration Site of the 
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
