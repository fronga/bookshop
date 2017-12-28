function show( id, view ) {
  window.open("show_" + view + ".php?id=" + id, "Informations " + view,
              config='height=200, width=400, toolbar=no, menubar=no, scrollbars=no,'
              + ' resizable=no, location=no, directories=no, status=no')
}

function display_livres_commande( commande_id ) {
    console.log("Commande: "+ commande_id);
    html = "";
// print "<TR><TH>No.</TH>";
// foreach ( $fields as $name => $var ) {
//     print "<TH NOWRAP>";
//     if ( $var ) {
//         $url = "?view=commandes&sortby=$var&order=";
//         $arrow = '  ';
//         if ( $var == $sortby ) {
//             $url .= abs($order-1);
//             $arrow = "&".($order?'u':'d')."arr; ";
//         } else {
//             $url .= $order;
//         }
//         print "<A HREF=\"$url\">$arrow";
//     }
//     print $name;
//     if ( $var ) print "</A>";
//     print "</TH>";
// }
// print "</TR>\n";

// $ilivre = 0; 
// foreach ( $livres_commande as $livre ) {
//     print '<TR><TD>'.$livre->id.'</TD>';
//     print '<TD>'.$livre->fournisseur.' / '.$livre->date.'</TD>';
//     print '<TD>'.$livre->nom_complet.'</TD>';
//     print '<TD><I>'.$livre->titre."</I></TD>";
//     print "<TD ALIGN=\"RIGHT\">".$livre->quantite."</TD></TR>\n";
// }

}
