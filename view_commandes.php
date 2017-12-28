<?php

// Get list of commandes and books
$query = "SELECT c.id, c.date, c.frais, c.monnaie, f.nom AS fournisseur,";
$query .= " count(lc.id) AS num_livres, sum(lc.prix_achat_ht * (1 + lc.taxes)) AS prix_total";
$query .= " FROM commandes AS c";
$query .= " LEFT JOIN `fournisseurs` AS f ON c.fk_fournisseur_id = f.id";
$query .= " LEFT JOIN `livre_commande` AS lc ON c.id = lc.fk_commande_id";
$query .= " GROUP BY f.nom, c.id";
$query .= " ORDER BY date DESC";

$mysqli = $GLOBALS['mysqli'];

# Fetch full list of pieces
$result = $mysqli->query($query) or nicedie ("Query $query failed:<BR>".$mysqli->error);
while ($answer = $result->fetch_object()) {
    $commandes[] = $answer;
}
$result->close();

## Print totals
#print "<span class=\"totals\">".count($livres_commande)." livres commandés</span>\n";

foreach ($commandes as $commande) {
    print('<h3 id="'.$commande->id.'">'.$commande->date.' '.$commande->fournisseur);
    print('<div style="float:right"><small>');
    print('livres: '.$commande->num_livres.' / total: '.round($commande->prix_total + $commande->frais).' '.$commande->monnaie);
    print('</small></div></h3>');
    print('<div><p class="accordion"></p></div>');
}
?>
