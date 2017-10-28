<?php
  include("defines.inc");
  $view = array_key_exists("view", $_GET) ? $_GET["view"] : null;
  switch ($view) {
    case 'auteurs':
    case 'livres':
      header("Location: http://localhost/~fronga/phpmyadmin/tbl_change.php?db=procure&table=".$view);
      break;
    default:
      nicedie("pas de vue");
  }
?>