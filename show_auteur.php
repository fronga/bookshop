<?php
  include("defines.inc");

  # Fetch information
  $id = $_GET['id'];
  if ( $id>0 ) {
    $query =  "SELECT titre, auteurs.nom_complet FROM livres";
    $query .= " RIGHT JOIN auteurs ON livres.fk_auteur_id = auteurs.id WHERE livres.fk_auteur_id =".$id;
    $result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
    while ($answer = $result->fetch_object()) {
      $livres[] = $answer;
    }
    $result->close();  
  } else {
    nicedie ("ID must be non-null");
  }
?>

<!DOCTYPE HTML>
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Livres </TITLE>
</HEAD>
<BODY>
<?php
  if (count($livres) == 0) {
    print "Aucun livre trouvé";
  } else {
    print "<H1>".$livres[0]->nom_complet."</H1>";
    foreach ($livres as $livre) {
      print "<I>".$livre->titre."</I><BR>";
    }
  }
?>
</BODY>
</HTML>
