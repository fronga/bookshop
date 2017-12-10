<?php
require_once("defines.inc");

function insert_commande($form) {
    // Insert new commande in database
    // Expected input: JSON

    # Check if need to create new commande
    $json = json_decode(urldecode($form));
    $commande_id = null;
    if (!property_exists($json->commande, "id")) {
        $fields = ['date', 'fk_fournisseur_id', 'frais', 'monnaie'];
        $mysqli = $GLOBALS['mysqli'];
        $query = "INSERT INTO `commandes` (".join(',', $fields).") VALUES (";
        foreach ($fields as $field) {
            $query .= "'".$json->commande->{$field}."',";
        }
        $query = substr($query, 0, -1).")";
        print($query);
        # $mysqli->query($query);
        # $commande_id = $mysqli->insert_id;
    } else {
        $commande_id = $json->commande->id;
    }

    $fields = ['date', 'fk_fournisseur_id', 'frais', 'monnaie'];
    $query = "INSERT INTO `livre_commande` () VALUES (";
    print(join(",", array_keys($json->livre[0])));
    foreach ($json->livre as $livre) {
        #print_r($livre);
    }

}
?>