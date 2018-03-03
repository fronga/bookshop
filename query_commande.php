<?php
  include("defines.inc");
  $id_commande = $mysqli->real_escape_string($_GET['commande']);
  $query = "SELECT c.id, c.monnaie, c.date as c_date, l.titre, a.nom_complet,";
  $query .= " lc.quantite, lc.id, (lc.prix_achat_ht * (1 + lc.taxes)) as prix_public,";
  $query .= " (lc.prix_achat_ht * (100. - lc.remise)/100.) as prix_achat FROM livre_commande AS lc";
  $query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
  $query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
  $query .= " LEFT JOIN commandes AS c ON c.id = lc.fk_commande_id";
  $query .= " LEFT JOIN fournisseurs AS f ON c.fk_fournisseur_id = f.id";
  $query .= " WHERE c.id = ".$id_commande;
  $query .= " ORDER BY lc.id ASC";
  
  $result = $mysqli->query($query) or nicedie("Query $query failed:<BR>".$mysqli->error);
  while ($answer = $result->fetch_object()) {
    $livres[] = $answer;
  }
  $result->close();

  # Add price in other currency
  $query = "SELECT `facteur_eur_chf` FROM `conversion_monnaies`";
  $query .= " WHERE `date` > ".$livres[0]->c_date." ORDER BY `date` DESC LIMIT 1";
  $result = $mysqli->query($query) or nicedie("Query $query failed:\n".$mysqli->error);
  $answer = $result->fetch_object();
  $fact = $answer->facteur_eur_chf;

  if ($livres) {
    $response = "<TABLE class=\"list sublist\">";
    $response .= "<THEAD><TR><TH COLSPAN=\"3\"></TH><TH>Prix achat</TD><TH COLSPAN=\"2\">Prix public</TH><TH>Quantité</TH></THEAD>\n<TBODY>";
    $ilivre = 1;
    foreach ( $livres as $livre ) {
        $response .= '<TR><TD>'.$ilivre++.'.</TD>';
        $response .= '<TD>'.$livre->nom_complet.'</TD>';
        $response .= '<TD><I>'.$livre->titre."</I></TD>";
        $response .= '<TD ALIGN="RIGHT" nowrap>'.sprintf("%0.2f", $livre->prix_achat).' '.$livre->monnaie.'</TD>';
        $response .= '<TD ALIGN="RIGHT" nowrap>'.sprintf("%0.2f", $livre->prix_public).' '.$livre->monnaie.'</TD>';
        $response .= '<TD ALIGN="RIGHT" nowrap>'.sprintf("%0.0f", $livre->prix_achat*$fact).' CHF</TD>';
        $response .= '<TD ALIGN="RIGHT" nowrap> x '.$livre->quantite."</TD>";
        $response .= "</TR>\n";
    }
    $response .= "</TBODY></TABLE>";
  } else {
    $response = "Aucun livre commandé<BR>";
  }

  echo $response;
?>

