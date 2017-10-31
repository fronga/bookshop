<?php
  include("defines.inc");
  $table = $_GET['table'];
  if ($table == "auteurs") {
    $query = "SELECT id, nom_complet FROM auteurs";    
  } elseif ($table == "livres") {
    $id_auteur = $mysqli->real_escape_string($_GET['id']);
    $query = "SELECT id, titre FROM livres WHERE fk_auteur_id = ".$id_auteur;
  }
  
  $result = $mysqli->query($query) or die ("Query $query failed: ".$mysqli->error);
  while ($answer = $result->fetch_object()) {
    $response[] = $answer;
  }
  $result->close();
echo json_encode($response);
?>

