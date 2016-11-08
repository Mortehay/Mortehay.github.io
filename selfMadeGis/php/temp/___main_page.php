<?php

ini_set('display_errors', 1);
$restriction=NULL;
include('login.php'); 

 $restriction = $_GET["restriction"];
  if ($restriction != NULL) {
  $restriction=json_encode($restriction);
  print "<script type='text/javascript'>console.log(".$restriction.");</script>";
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

  <head>
    <title>Volia QGIS Mapserver Client</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <!-- Framework CSS --> 
    <link rel="stylesheet" href="../css/blueprint/screen.css" type="text/css" media="screen, projection"> 
    <link rel="stylesheet" href="../css/blueprint/print.css" type="text/css" media="print">

    <link rel="stylesheet" href="../css/accordion.css" type="text/css">  
    <!--[if lt IE 8]><link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]--> 
    
    <!-- Import fancy-type plugin for the sample page. --> 
    <link rel="stylesheet" href="../css/blueprint/plugins/fancy-type/screen.css" type="text/css" media="screen, projection"> 
    <!-- Tims styles on top of blueprint -->
    <link rel="stylesheet" href="../css/style.css" type="text/css" media="screen, projection"> 
    <!-- jquery -->
    <script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
    <!-- tmpl -->
    <script  src="../libs/tmpl/tmpl.js"></script>
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
          <script type="text/template" id="template">
            <% for (var i=0; i<data.length;i++) {%>
              <div class="accordion-section<%=i%>">
                  <a class="accordion-section-title" href="#accordion-<%=i%> "><%= data[i].city %></a>
                   
                  <div id="accordion-<%=i%>" class="accordion-section-content">
                      <p><%= data[i].notes %></p>
                      <% for (var j=0; j<data[i].links.length;j++) {%>

                        <a href="<%=data[i].links[j]%>"><%=data[i].links_description[j]%></a>
                      <% }%>
                  </div>
              </div>
            <% }%>
          </script>

        
        </div> 
        <div class="supply">
          <ul>
            <li>qgis web project and database support - <span>Yurii Shpylovyi</span></li>
            <li>data preparation - <span>Andrey Zaverukha</span></li>
            <li>serverside scripting support - <span>Alexander Gusachenko</span></li>
            <li>network engineer - <span>Yurii Lobenko</span></li>
            <li><a href="#" onclick="textexchange();">Click Me!</a></li>
          </ul>
        </div> 
      </div>
      
    
    <script type="text/javascript" src="../js/links.js"></script>
    <script type="text/javascript" src="../js/accordion.js"></script>
    <script type="text/javascript" src="../js/whois.js"></script>
  </body>
</html>
