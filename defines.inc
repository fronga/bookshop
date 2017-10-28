<?php
  #
  # Global definitions
  #
  
  # MYSQL settings
  global $user, $dbase, $pass, $mysqli;
  $user="root";
  $dbase="procure";
  $pass="mysql";
  
  # Connect to database
  $mysqli = new mysqli("localhost",$user,$pass,$dbase) or die ("Couldn't connect: ".$mysql->error);
  $mysqli->set_charset("utf8");
  
  #__________________________________________________________________________
  #
  # Global functions
  #
  
  function nicedie($error) {
    # Prints error and exits
    if (!headers_sent()) {
      echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
      <html>
      <head>
      <title>ERREUR 007</title>
      </head>
      <BODY BGCOLOR="#FFFFFF">';
    }
    echo '    <B><FONT COLOR="#FF0000">Erreur : '.$error.'</FONT></B>
    <BR><BR>
    </BODY>
    </HTML>'."\n";
    exit();
  }
  
  
  ?>
