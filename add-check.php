<?php
  include("defines.inc");
?>

<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
 <head>
 <title>V&eacute;rification insertion</title>
 </head>
 <body>
<?php

  # Parse commande
  $commande = $_POST['commande'];
  
  
  foreach ($_POST['livre'] as $livre) {
    foreach ($livre as $field => $value) {
      print($field.":".$value."<BR>");
    }
  }
?>

 </body>
</html>
