<?php
  require_once("defines.inc");
  require_once("commandes.inc");

  if (array_key_exists("validate", $_GET) && $_GET["validate"] == 1) {
    list($id, $insert) = create_insert($_POST["form"]);
    $ok = $mysqli->query($insert);
    if (!$ok) {
      nicedie("Insert failed: ".$insert."\nError: ".$mysqli->error);
    } else {
      # print("ID: $id");
      $url = "Location: /~fronga/procure/?view=commandes&commande=$id";
      header($url);
    }
    return;
  }
?>

<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
 <head>
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.css">
  <script src="jquery/jquery-1.12.4.js"></script>
  <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
 <title>V&eacute;rification insertion</title>
 </head>
 <body>
  <form method=post id="bookForm" action="add-check.php?validate=1">
    <input type="hidden" name="validated" value="1">
    <input type="hidden" name="form" value="<?php echo urlencode(json_encode($_POST)); ?>">
    <table class="form" id="tbl_commande">
<?php
  # Parse commande
  $header = "<thead><tr>";
  $row = "<tbody><tr>";
  foreach ($_POST['commande'] as $key => $value) {
    $header .= "<th>".ucfirst($key)."</th>";
    $row .= "<td>".$value."</td>";
  }
  $header .= "</tr></thead>";
  $row .= "</tr></tbody>";
  print($header."\n".$row);
?>
    </table>
    <button type="submit" value="Submit" class="ui-button ui-widget ui-corner-all">Confirmer</button>
    <table class="list">
<?php
  $header = "<thead><tr>";
  $rows = "<tbody>";
  $first = true;
  foreach ($_POST['livre'] as $livre) {
    $rows .= "<tr>";
    foreach ($livre as $key => $value) {
      if ($first) {
        $header .= "<th>".ucfirst($key)."</th>";        
      }
      $rows .= "<td>".$value."</td>";
    }
    $rows .= "</tr>";
    $first = false;
  }
  $header .= "</tr></thead>";
  $rows .= "</tbody>";
  print($header."\n".$rows);  
?>
    </table>
    </form>
 </body>
</html>
