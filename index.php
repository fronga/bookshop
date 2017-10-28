<?php
  include("defines.inc");
  include("display.inc");
  ?> 

<!DOCTYPE HTML>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Base de données procure</TITLE>
<LINK rel="stylesheet" href="main.css" type="text/css">
<script type="text/javascript" src="display.js"></script>
</HEAD>
<BODY>
<div class="head">
<H1 class="title">Base de données procure</H2>

<?php
  $views = array("auteurs", "livres", "commandes");
  
  // Find out what to show
  $view = array_key_exists("view", $_GET) ? $_GET["view"] : "livres"; // Default

  echo '<DIV class="menu">';
  foreach ( $views as $itab ) {
    if ( $view != $itab ) {
      echo '<SPAN class="tab"><A HREF="?view='.$itab.'">';
    } else {
      echo '<SPAN class="selected_tab">';
    }
    echo ucfirst($itab);
    if ( $view != $itab ) echo '</A>';
    echo '</SPAN>';
    if ( $view == $itab ) {
      echo '<SPAN class="atab"><A onclick="window.open(\'add.php?view='.$itab.'\');">+</A></SPAN>';      
    }
  }
  echo '</DIV>'; # End menu
  echo '</DIV>'; # End header
  echo '<DIV class="body">';
  echo '<TABLE class="list">';  
  include "view_".$view.".php";
  
?>
</TABLE>
</DIV>
</BODY>
</HTML>
