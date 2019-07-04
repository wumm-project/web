<?php
/**
 * User: Hans-Gert Gräbe
 * Date: 2019-07-04
 */

include_once("layout.php");

$content='      
<div class="container">
<div class="row">
<div  class="col-lg-3 col-sm-1"></div><div  class="col-lg-6 col-sm-10">

<p>This is a demonstration site, how to compose web presentations from RDF
data collected within the WUMM Project using lightweight PHP scripts.  </p>

<p>The examples use the <a href="http://getbootstrap.com" >Bootstrap
Framework</a> and the <a href="http://www.easyrdf.org/" >EasyRdf PHP
Library</a>.  The code is available from our github repo <a
href="https://github.com/wumm-project/web"
>https://github.com/wumm-project/web</a>. </p> </div>

<div class="col-lg-3 col-sm-1"> </div> </div>

';
echo showPage($content);

?>
