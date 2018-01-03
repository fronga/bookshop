<?php
  require_once("defines.inc");
  require_once("commandes.inc");
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
  <script src="js/utils.js"></script>
  <script src="js/commande.js"></script>
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
      $query = "SELECT c.*, f.nom from `commandes` AS c";
      $query .= " LEFT JOIN fournisseurs AS f on c.fk_fournisseur_id = f.id";
      $query .= " WHERE c.id = ".$_GET["commande"];
      $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
      $com = $result->fetch_object();
      $result->close();

      $query = "SELECT l.titre, a.nom_complet, lc.* FROM livre_commande AS lc";
      $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
      $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
      $query .= " WHERE lc.fk_commande_id = ".$_GET["commande"];
      $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
      $livres = $result->fetch_all(MYSQLI_ASSOC);
      $result->close();
    }

    // Load data from fournisseurs
    $query = "SELECT * FROM fournisseurs";
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
            <?php print $com ? "<input type=\"hidden\" name=\"commande[id]\" value=\"".$com->id."\">\n" : ""; ?>
            <select name='commande[fk_fournisseur_id]'>
            <?php 
              foreach ($fournisseurs as $f) {
                print "<OPTION ".($com && ($f->nom == $com->nom) ? "SELECTED " : "");
                print "VALUE=".$f->id.">".$f->nom."</OPTION>"; 
              } 
            ?>
            </select>
            <input name='commande[date]' type='text' size='10' id='datepicker' <?php print $com ? "value=".$com->date : "";?>>
            <br>
            <input name="commande[frais]" type="number" placeholder="Frais" min=0.00 max=100 step=0.01 style="width: 7em" 
            <?php print $com ? "value=".$com->frais : "";?>>
            <select name="commande[monnaie]">
              <option value="EUR" <?php print ($com && $com->monnaie == "CHF") ? "" : "selected" ?>>EUR</option>
              <option value="CHF" <?php print ($com && $com->monnaie == "CHF") ? "selected" : "" ?>>CHF</option>
            </select>
            <input name='commande[remise_incluse]' type='checkbox' <?php print ($com && $com->remise_incluse) ? "checked" : "" ?>>
                <label>Remise incluse</label>
          </fieldset>
        </td>
      </tr>
    </table>
    <table class="form" id="tbl_livres">
      <tbody class="rowContainer">
        <?php 
          $count = 1;
          if ($livres) {
            foreach ($livres as $livre) {
              commande_row($count, $livre);
              $count++;
            }
          }
          commande_row($count);
        ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <button type="button" class="clone" onclick="clone(<?php print($count); ?>)">
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
