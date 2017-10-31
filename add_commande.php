<?php
  require_once("defines.inc");
  $mysqli = $GLOBALS['mysqli'];  
?>

<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
 <head>
  <title>Ajouter/modifier une commande</title>
  <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.css">
  <script src="jquery/jquery-1.12.4.js"></script>
  <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
  <script src="forms/commande.js"></script>
  <script language=JavaScript>
    $( function() {
      $( "#datepicker" ).datepicker();
    } );
  </script>
 </head>
 <body>
  <h1>Ajouter/modifier une commande</h1>
  <?php
    // TODO: Load data if modifying existing order
    if (array_key_exists("commande", $_GET)) {
      $query = "SELECT c.date, c.fk_fournisseur_nom, l.titre, a.nom_complet, lc.quantite FROM livre_commande AS lc";
      $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
      $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
      $query .= " LEFT JOIN commande AS c ON c.id = lc.fk_commande_id";
      $query .= " WHERE lc.fk_commande_id = ".$_GET["commande"];
      $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
      $com = $result->fetch_object();
      $result->close();
    }

    // Load data from fournisseurs
    $query = "SELECT * FROM fournisseur";
    $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
    while ($answer = $result->fetch_object()) {
        $fournisseurs[] = $answer;
    }
    $result->close();
  ?>
  <form method=post id="bookForm" action="add-check.php">
    <table class="form" id="tbl_commande">
      <tr>
        <td>
          <select name='fournisseur'>
          <?php 
            foreach ($fournisseurs as $f) {
              print "<OPTION ".($com && ($f->nom == $com->fk_fournisseur_nom) ? "SELECTED " : "");
              print "VALUE=".$f->id.">".$f->nom."</OPTION>"; 
            } 
          ?>
          </select>
        </td>
        <td>
          <input name='date' type='text' id='datepicker' <?php print $com ? "value=".$com->date : ""; ?>>
        </td>
      </tr>
    </table>
    <table class="form" id="tbl_livres">
      <tbody class="rowContainer">
        <tr class="cloningInput">
          <td>
            <?php
                $query = "SELECT id, nom_complet FROM auteurs";
                $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
                echo "<select class='select_auteur' id='auteur_0' name='auteur_0' 
                      onload=\"getAuthors(this)\" onchange=\"getAuthorBooks(this)\">";
                echo "<option value='null'></option></select>";
                echo "</select>";
                // Book drop-down
                echo "<select id='livre_0' name='livre_0'><option value='null'>--</option></select>";
            ?>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td>
            <button type="button" class="clone" onclick="clone()"></button>
          </td>
          <td>
            <button type="button" class="remove"></button>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="submit" value="Submit"><input type="reset" value="Reset" onclick="resetForm">
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
 </body>
</html>
