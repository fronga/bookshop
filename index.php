<?php
  include("defines.inc");
  // Versatile sorting function
  // Call with: usort($array, build_sorter('key','order'));
  function build_sorter($key,$order) {
    return function ($a, $b) use ($key,$order) {
      if ( !$order ) return strnatcmp($a->$key, $b->$key);
      else return strnatcmp($b->$key, $a->$key);
    };
  }
  $views = array("auteurs", "livres", "commandes");  
  $view = array_key_exists("view", $_GET) ? $_GET["view"] : "livres"; // Default
  
?> 

<!DOCTYPE HTML>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Base de données procure</TITLE>
<LINK rel="stylesheet" href="main.css" type="text/css">
<script type="text/javascript" src="js/utils.js"></script>
<script type="text/javascript" src="js/display.js"></script>
<link rel="stylesheet" href="main.css">
<?php
if ($view == "commandes") {
  echo '<link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.css">
  <script src="jquery/jquery-1.12.4.js"></script>
  <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
  ';
  }
?>
</HEAD>
<BODY>
<div class="head">
<H1 class="title">Base de données procure</H2>

<?php
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
  if ($view == "commandes") {
    echo '<div id="accordion">';
    include "view_".$view.".php";
    echo '</DIV>';
    echo '<script>
    $( "#accordion" ).accordion(
        {
            collapsible: true,
            animate: 100,
            active: false,
            heightStyle: "content",
            beforeActivate: function( event, ui ) {
                if (ui.newPanel.children().html() == "") {
                  display_livres_commande(ui.newHeader.attr(\'id\'), ui.newPanel.children());
                }
            }
        }
    );
</script>';
  } else {
    echo '<DIV class="body">';
    echo '<TABLE class="list">';  
    include "view_".$view.".php";
    echo '</TABLE>';
    echo '</DIV>';
  }
  
?>
</BODY>
</HTML>
