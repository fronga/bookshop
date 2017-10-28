<?php
  include("defines.inc");
  $view = array_key_exists("view", $_GET) ? $_GET["view"] : null;
  switch ($view) {
    case 'auteurs': 
      header("Location: http://localhost/~fronga/phpmyadmin/tbl_change.php?db=procure&table=auteurs");
      break;
    default:
      nicedie("pas de vue");

  }
?>