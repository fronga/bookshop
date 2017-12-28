<?php
  include("defines.inc");
  $table = $_GET['table'];
  if ($table == "auteurs") {
    $query = "SELECT * FROM auteurs ORDER BY nom";    
  } elseif ($table == "livres") {
    $id_auteur = $mysqli->real_escape_string($_GET['id']);
    $query = "SELECT id, titre FROM livres WHERE fk_auteur_id = ".$id_auteur;
  }
  
  $result = $mysqli->query($query) or nicedie ("Query $query failed:\n".$mysqli->error);
  while ($answer = $result->fetch_object()) {
    $response[] = $answer;
  }
  $result->close();
  echo json_encode($response);
?>

