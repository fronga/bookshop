<?php
  require_once("defines.inc");
  $view = array_key_exists("view", $_GET) ? $_GET["view"] : null;
  switch ($view) {
    case 'auteurs':
    case 'livres':
      header("Location: http://localhost/~fronga/phpmyadmin/tbl_change.php?db=procure&table=".$view);
      break;
    case 'commandes':
      include("add_commande.php");
      break;
    default:
      nicedie("pas de vue");
  }
?>