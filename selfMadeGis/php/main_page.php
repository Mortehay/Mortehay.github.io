<?php

//ini_set('display_errors', 1);
include('restriction.php');
include('rotatingArrows.php');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

  <head>
    <title>Volia QGIS Mapserver Client</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <!-- Framework CSS --> 
    <link rel="stylesheet" href="../css/blueprint/screen.css" type="text/css" media="screen, projection"> 
    <link rel="stylesheet" href="../css/blueprint/print.css" type="text/css" media="print">


    <!--[if lt IE 8]><link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]--> 
    
    <!-- Import fancy-type plugin for the sample page. --> 
    <link rel="stylesheet" href="../css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection"> 
    <!-- Tims styles on top of blueprint -->
    <link rel="stylesheet" href="../css/style.css" type="text/css" media="screen, projection"> 
    <link rel="stylesheet" href="../css/accordion.css" type="text/css">  

    <link rel="stylesheet" href="../css/tools.css" type="text/css">  
    <link rel="stylesheet" href="../css/vis.css" type="text/css">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- jquery -->
    <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- tmpl -->
    <script  src="../libs/tmpl/tmpl.js"></script>
    <!-- VIZ JS-->
    <script  src="../libs/vis/vis.js"></script>

    <script type="text/javascript" src="../js/textexchange.js"></script>
  </head>

 <body>
    <div class="container">
      <div class="span-18">
        <h1>VOLIA GIS - The Future is Now!</h1>
        <h2 class="alt">Volia QGIS Web Client</h2> 
      </div>
      <div class="span-6 last">
        <a href="http://volia.com/ukr/"><img src="../img/logo.png" alt="volia icon"/></a>
      </div>
      <hr>
      
        <div id="result" class="result accordion">
        <?php
         echo $accordion;
        ?>
                 
        </div>
        <?php
       
         //echo $tools; 
         //echo $buttons;
         echo $newTools;
      
        ?>
        <div class="supply">
          <ul>
            <li>qgis web project and database support - <span>Yurii Shpylovyi</span></li>
            <li>data preparation - <span>Andrey Zaverukha</span></li>
            <li>serverside scripting support - <span>Yurii Shpylovyi and Alexander Gusachenko</span></li>
            <li>network engineer - <span>Oleksandr Sadovnik</span></li>
          </ul>
        </div> 
      </div>
      <?php
      echo $rotatingArrows;
      ?>

    <script type="text/javascript" src="../js/accordion.js"></script>
    <script type="text/javascript" src="../js/whois.js"></script>
    <script type="text/javascript" src="../js/topologyUpdate.js"></script>
    <script type="text/javascript" src="../js/visible_maker.js"></script>
  </body>
</html>