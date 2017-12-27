<?php

// Get list of authors and return table
$fields = array(
    "Auteur" => 'nom_complet',
    "Titre" => 'titre',
    "Vendus" => 'vendus'
);
$query =  "SELECT `livres`.id, `livres`.titre, `auteurs`.nom_complet, SUM(`livre_commande`.quantite) AS vendus";
$query .= " FROM `livres` LEFT JOIN auteurs ON livres.fk_auteur_id = auteurs.id";
$query .= " LEFT JOIN livre_commande ON `livres`.id = `livre_commande`.fk_livre_id";
$query .= " GROUP BY `livres`.id";
  
$mysqli = $GLOBALS['mysqli'];

# Fetch full list of pieces
$result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
while ($answer = $result->fetch_object()) {
    $livres[] = $answer;
}
$result->close();

# Print totals
print "<span class=\"totals\">".count($livres)." livres trouvés</span>\n";

# Sorting options
$order = array_key_exists('order',$_GET) ? $_GET['order'] : 0;
$sortby = array_key_exists('sortby',$_GET) ? $_GET['sortby'] : "titre";

# Order as requested
usort($livres, build_sorter($sortby, $order));

# Print table header with field names
print "<TR><TH>No.</TH>";
foreach ( $fields as $name => $var ) {
    print "<TH NOWRAP>";
    if ( $var ) {
        $url = "?view=livres&sortby=$var&order=";
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
foreach ( $livres as $livre ) {
    print '<TR><TD>'.++$ilivre.'</TD>';
    print '<TD>'.$livre->nom_complet.'</TD>';
    print '<TD><!-- A HREF="javascript:show('.$livre->id.', \'livre\')" --><I>'.$livre->titre."</I><!-- /A --></TD>";
    print "<TD ALIGN=\"RIGHT\">".$livre->vendus."</TD></TR>\n";
}

?>