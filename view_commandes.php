<?php

// Get list of commandes and books
$fields = array(
    "Commande" => 'date',
    "Auteur" => 'nom_complet',
    "Titre" => 'titre',
    "Quantité" => 'quantite'
);
$query = "SELECT c.id, c.date, c.frais, f.nom AS fournisseur, l.titre, a.nom_complet, lc.quantite FROM livre_commande AS lc";
$query .= " LEFT JOIN livres AS l ON lc.fk_livre_id = l.id";
$query .= " LEFT JOIN auteurs AS a ON l.fk_auteur_id = a.id";
$query .= " LEFT JOIN commandes AS c ON c.id = lc.fk_commande_id";
$query .= " LEFT JOIN fournisseurs AS f ON c.fk_fournisseur_id = f.id";
$query .= " ORDER BY date DESC";

$mysqli = $GLOBALS['mysqli'];

# Fetch full list of pieces
$result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
while ($answer = $result->fetch_object()) {
    $livres_commande[] = $answer;
}
$result->close();

# Print totals
print "<span class=\"totals\">".count($livres_commande)." livres commandés</span>\n";

# Sorting options
$order = array_key_exists('order',$_GET) ? $_GET['order'] : 0;
$sortby = array_key_exists('sortby',$_GET) ? $_GET['sortby'] : "nom_complet";

# Order as requested
usort($livres_commande, build_sorter($sortby, $order));

# Print table header with field names
print "<TR><TH>No.</TH>";
foreach ( $fields as $name => $var ) {
    print "<TH NOWRAP>";
    if ( $var ) {
        $url = "?view=commandes&sortby=$var&order=";
        $arrow = '  ';
        if ( $var == $sortby ) {
            $url .= abs($order-1);
            $arrow = "&".($order?'u':'d')."arr; ";
        } else {
            $url .= $order;
        }
        print "<A HREF=\"$url\">$arrow";
    }
    print $name;
    if ( $var ) print "</A>";
    print "</TH>";
}
print "</TR>\n";

$ilivre = 0; 
foreach ( $livres_commande as $livre ) {
    print '<TR><TD>'.$livre->id.'</TD>';
    print '<TD>'.$livre->fournisseur.' / '.$livre->date.'</TD>';
    print '<TD>'.$livre->nom_complet.'</TD>';
    print '<TD><I>'.$livre->titre."</I></TD>";
    print "<TD ALIGN=\"RIGHT\">".$livre->quantite."</TD></TR>\n";
}

?>