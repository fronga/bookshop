<?php

// Get list of authors and return table
$fields = array(
    "Nom" => 'nom_complet',
    "#(livres)" => 'nb_livres'
);
$query = "SELECT nom_complet, count(livres.id) AS nb_livres FROM `auteurs`
 LEFT JOIN livres ON livres.fk_auteur_id = auteurs.id GROUP BY auteurs.id;";
  
$mysqli = $GLOBALS['mysqli'];

# Fetch full list of pieces
$result = $mysqli->query($query) or nicedie ("Query $query failed: ".$mysqli->error);
while ($answer = $result->fetch_object()) {
    $authors[] = $answer;
}
$result->close();

# Print totals
print "<span class=\"totals\">".count($authors)." auteurs trouvés</span>\n";

# Sorting options
$order = array_key_exists('order',$_GET) ? $_GET['order'] : 0;
$sortby = array_key_exists('sortby',$_GET) ? $_GET['sortby'] : "nom_complet";

# Order as requested
usort($authors, build_sorter($sortby, $order));

# Print table header with field names
print "<TR><TH>Row</TH>";
foreach ( $fields as $name => $var ) {
    print "<TH NOWRAP>";
    if ( $var ) {
        $url = "?view=auteurs&sortby=$var&order=";
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

$iauthor = 0; 
foreach ( $authors as $author ) {
    print '<TR><TD>'.++$iauthor.'</TD>';
    print '<TD><A HREF="javascript:show_auteur('.$author->nom_complet.')">'.$author->nom_complet."</A></TD>";
    print "<TD>".$author->nb_livres."</TD></TR>\n";
}

?>