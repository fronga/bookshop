<!-- One row of "livre commande" -->
<?php

  require_once("defines.inc");

  function addBook($mysql_link, $counter) {
    
    // Author drop-down
    $aid = sprintf('auteur_%d', $counter);
    $lid = sprintf('livre_%d', $counter);
    $query = "SELECT id, nom_complet FROM auteurs";
    $result = $mysql_link->query($query) or nicedie ("Query $query failed: ".$mysql_link->error);
    $row .= "<select id='".$aid."' name='".$aid."' onchange=\"authorBooks(this.form, '".$aid."', '".$lid."')\">
    <option value='null'>-- Choisir auteur --</option>";  
    while ($author = $result->fetch_object()) {
      $row .= "<option value='".$author->id."'>".$author->nom_complet."</option>";
    }
    $row .= "</select>";

    // Book drop-down
    $row .= "<select id='".$lid."' name='".$lid."'><option value='null'>--</option>";
    $row .= "</select>\n";
    return $row;
  }
?>
