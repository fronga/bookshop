<?php
  include("defines.inc");
  $table = $_GET['table'];
  if ($table == "auteurs") {
    $query = "SELECT * FROM auteurs ORDER BY nom";    
  } elseif ($table == "livres") {
    $query = "SELECT id, titre FROM livres";
    if (array_key_exists('id', $_GET)) {
      $id_auteur = $mysqli->real_escape_string($_GET['id']);
      $query .= " WHERE fk_auteur_id = ".$id_auteur;
    }
  } elseif ($table == 'livre_commande') {
    $fk_fournisseur_id = $_GET['fk_fournisseur_id'];
    $src_id = $_GET['src_id'];
    $query = "SELECT lc.*, l.titre, a.nom_complet, a.id as fk_auteur_id";
    $query .= " FROM `livre_commande` AS lc LEFT JOIN commandes as c ON fk_commande_id = c.id";
    $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
    $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
    $query .= " WHERE src_id=".$src_id." AND c.fk_fournisseur_id=".$fk_fournisseur_id;
  }
  
  $result = $mysqli->query($query) or nicedie ("Query $query failed:\n".$mysqli->error);
  while ($answer = $result->fetch_object()) {
    $response[] = $answer;
  }
  $result->close();
  echo json_encode($response);
?>

