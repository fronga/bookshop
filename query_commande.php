<?php
  include("defines.inc");
  $id_commande = $mysqli->real_escape_string($_GET['commande']);
  $query = "SELECT c.id, c.date, c.frais, f.nom AS fournisseur, l.titre, a.nom_complet, lc.quantite FROM livre_commande AS lc";
  $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
  $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
  $query .= " LEFT JOIN commandes AS c ON c.id = lc.fk_commande_id";
  $query .= " LEFT JOIN fournisseurs AS f ON c.fk_fournisseur_id = f.id";
  $query .= " WHERE c.id = ".$id_commande;
  $query .= " ORDER BY date DESC";
  
  $result = $mysqli->query($query) or nicedie ("Query $query failed:\n".$mysqli->error);
  while ($answer = $result->fetch_object()) {
    $response[] = $answer;
  }
  $result->close();
  echo json_encode($response);
?>

