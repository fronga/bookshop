<?php
  require_once("defines.inc");
  $mysqli = $GLOBALS['mysqli'];  
?>

<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
 <head>
  <title>Ajouter/modifier une commande</title>
  <link rel="stylesheet" href="main.css">
  <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.css">
  <script src="jquery/jquery-1.12.4.js"></script>
  <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
  <script src="forms/commande.js"></script>
  <script language=JavaScript>
    $( function() {
      $( "#datepicker" ).datepicker();
    } );
    $( function() {
      $( ".widget button" ).button( {
          icon: "ui-icon-gear",
          showLabel: false
        } ).end();
    } );
  </script>
 </head>
 <body>
  <h1>Ajouter/modifier une commande</h1>
  <?php
    // TODO: Load data if modifying existing order
    if (array_key_exists("commande", $_GET)) {
      $query = "SELECT * from `commande` WHERE id = ".$_GET["commande"];
      $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
      $com = $result->fetch_object();
      $result->close();

      $query = "SELECT l.titre, a.nom_complet, lc.* FROM livre_commande AS lc";
      $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
      $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
      $query .= " WHERE lc.fk_commande_id = ".$_GET["commande"];
      $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
      $livres = $result->fetch_object();
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
  <form method=post id="bookForm" action="add-check.php" onsubmit="return validateForm()">
    <table class="form" id="tbl_commande">
      <tr>
        <td>
          <fieldset>
            <legend>Fournisseur / date&nbsp;:</legend>
            <select name='commande[fournisseur]'>
            <?php 
              foreach ($fournisseurs as $f) {
                print "<OPTION ".($com && ($f->nom == $com->fk_fournisseur_nom) ? "SELECTED " : "");
                print "VALUE=".$f->id.">".$f->nom."</OPTION>"; 
              } 
            ?>
            </select>
            <input name='commande[date]' type='text' size='10' id='datepicker' <?php print $com ? "value=".$com->date : "";?>>
            <br>
            <input name="commande[frais]" type="number" placeholder="Frais" min=0.00 max=100 step=0.01 style="width: 7em" 
            <?php print $com ? "value=".$com->frais : "";?>>
            <select name="commande[monnaie]">
              <option value="eur" <?php print ($com && $com->monnaie == "CHF") ? "" : "selected" ?>>EUR</option>
              <option value="chf" <?php print ($com && $com->monnaie == "CHF") ? "selected" : "" ?>>CHF</option>
            </select>
            <input name='commande[remise_incluse]' type='checkbox' <?php print ($com && $com->remise_incluse) ? "checked" : "" ?>>
                <label>Remise incluse</label>
          </fieldset>
        </td>
      </tr>
    </table>
    <table class="form" id="tbl_livres">
      <tbody class="rowContainer">
        <tr class="cloningInput">
          <td id="count">1.</td>
          <td><input name="source_id" size=10 placeholder="ID"></td>
          <td>
            <?php
                echo "<select class='select_auteur' name='livre[0][auteur]' 
                      onload=\"getAuthors(this)\" onchange=\"getAuthorBooks(this)\">";
                echo "<option value=''></option></select>";
                echo "</select>";
                echo "<select name='livre[0][titre]'><option value=''>--</option></select>";
            ?>
          </td>
          <td><input name="livre[0][prix]" type="number" placeholder="Prix" min=0 max=100 step=0.01 style="width: 7em"></td>
          <td>
              <input name="livre[0][remise]" type="number" placeholder="%" min=0 max=100 step=5 style="width: 5em">
          </td>
          <td>
            <input name="livre[0][quantite]" type="number" placeholder="Qté" min=1 max=100 style="width: 5em">
          </td>
          <td>  
            <select name="livre[0][taxe]">
              <option value="0.55" selected>5.5%</option>
              <option value="0.2">20%</option>
            </select>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <button type="button" class="clone" onclick="clone()">
              + Ajouter
            </button>
          </td>
          <td colspan="2">
            <button type="button" class="remove" onclick="remove()">
              - Supprimer
            </button>
          </td>
        </tr>
        <tr>
          <td class="submit" colspan="3">
            <button type="submit" value="Submit" class="ui-button ui-widget ui-corner-all">Submit</button>
            <button type="reset" value="Reset" class="ui-button ui-widget ui-corner-all" onclick="resetForm">Reset</button>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
 </body>
</html>
