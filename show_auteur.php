<?php
  include("defines.inc");

  # Fetch information
  $id = $_GET['id'];
  if ( $id>0 ) {
    $query = "SELECT titre, auteurs.nom_complet FROM livres RIGHT JOIN auteurs ON livres.fk_auteur_id = auteurs.id WHERE livres.fk_auteur_id =".$id;    ;
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
  print "<B>Compositeur :</B> ".$answer->composer."<BR>";
  print "<B>Titre       :</B> <I>".$answer->title."</I><BR>";
  print "<B>Volume      :</B> ".$answer->volume."<BR>";
  print "<B>Page        :</B> ".$answer->page;
  if ($answer->num>0) { echo ($answer->num==1?'a':'b'); }
  print '<BR><BR>';
  print $answer->comment;
  print '<BR><BR>';
  print "<B>Jou&eacute;        :</B><BR>";
  
  # Fetch dates
  $cselect = "WHERE ";
  foreach ( $GLOBALS['rites'] as $rite ) $cselect .= "$rite=".$answer->id." OR ";
  $cselect = substr($cselect,0,-4);
  $cquery = "SELECT id,date,name FROM ceremonies ".$cselect." GROUP BY date DESC LIMIT 10";
  $cresult = mysql_query($cquery,$db) or die ("Query $cquery failed: ".mysql_error());
  print '<TABLE class="inv">';
  while ( $canswer = mysql_fetch_array($cresult) ) {
    print '<TR><TD>'.$canswer['date'].'</TD><TD><A HREF="">'.$canswer['name']."</A></TD></TR>";
  }
  print '</TABLE>';

?>
</BODY>
</HTML>
