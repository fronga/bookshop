<?php

$mysqli = $GLOBALS['mysqli'];

// Get list of commandes and books
$query = "SELECT c.id, c.date, c.frais, c.monnaie, f.nom AS fournisseur,";
$query .= " count(lc.id) AS num_livres, sum(lc.prix_achat_ht *(1 + lc.taxes)*lc.quantite*(100.-lc.remise)/100.) AS cout_total";
$query .= " FROM commandes AS c";
$query .= " LEFT JOIN `fournisseurs` AS f ON c.fk_fournisseur_id = f.id";
$query .= " LEFT JOIN `livre_commande` AS lc ON c.id = lc.fk_commande_id";
$query .= " GROUP BY f.nom, c.id";
$query .= " ORDER BY date DESC";
$result = $mysqli->query($query) or nicedie ("Query $query failed:<BR>".$mysqli->error);
while ($answer = $result->fetch_object()) {
    $commandes[] = $answer;
}
$result->close();

foreach ($commandes as $commande) {
    print('<h3 id="'.$commande->id.'">'.$commande->date.' '.$commande->fournisseur);
    print('<div style="float:right"><small>');
    print('livres: '.sprintf("%03d", $commande->num_livres).' / total: '.sprintf("%06.2f", $commande->cout_total + $commande->frais).' '.$commande->monnaie);
    print('</small></div></h3>');
    print('<div><p class="accordion"></p></div>');
}
?>
