<?php
require_once("defines.inc");

function get_conversion() {
    $query = "SELECT id FROM `conversion_monnaies`";
    $mysqli = $GLOBALS['mysqli'];
    $mysqli->query($query);
}

function create_insert($form) {
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
        $mysqli->query($query);
        $commande_id = $mysqli->insert_id;
    } else {
        $commande_id = $json->commande->id;
    }

    # Retrieve latest conversion info

    # Process livres
    $query = "INSERT INTO `livre_commande` (";
    $fields = ["src_id", "fk_livre_id", "prix_achat_ht", "remise", "quantite", "taxe", "fk_commande_id", "fk_conversion_id"];
    foreach ($fields as $key) {
        $query .= $key.",";
    }
    $query = substr($query, 0, -1).") VALUES ";

    foreach ($json->livre as $livre) {
        $query .= "(";
        foreach ($fields as $key) {
            $query .= "'".$livre->$key."',";
        }
        $query = substr($query, 0, -1)."),";
    }
    $query = substr($query, 0, -1);
    
    return $query;
}

function commande_row($count, $row = null) {
    $empty = ($row == null);
    if ($empty) { print('<tr class="cloningInput">'); }
    print('  <td id="count">'.$count.'.</td>');
    print('  <td><input size=10 ');
    if ($empty) {
        print('name="livre[0][src_id]" placeholder="ID">');
    } else {
        if ($row['src_id']) { print(' value="'.$row['src_id'].'"'); }
        print(' disabled>');
    }
    print('</td><td>');
    if ($empty) {
        print('     <select class="select_auteur author_field" name="livre[0][fk_auteur_id]" onchange="getAuthorBooks(this)">');
        print('          <option></option>');
        print('     </select>');
        print('     <select class="title_field" name="livre[0][fk_livre_id]"><option>--</option></select>');
    } else {
        print('     <input class="author_field" disabled value="'.$row['nom_complet'].'">');
        print('     <input class="title_field" disabled value="'.$row['titre'].'">');
    }
    print('  </td><td><input type="number" min=0 max=100 step=0.01 class="price_field"');
    if ($empty) {
        print('name="livre[0][prix_achat_ht]" placeholder="Prix H.T.">');
    } else {
        print('disabled value="'.$row['prix_achat_ht'].'">');
    }
    print ('</td><td><input type="number" placeholder="%" min=0 max=100 step=5 class="num_field"');
    if ($empty) {
        print('name="livre[0][remise]">');
    } else {
        print('disabled value="'.($row['remise'] * 100.).'">');
    }
    print('</td><td><input type="number" placeholder="Qté" min=1 max=100 class="num_field"');
    if ($empty) {
        print('name="livre[0][quantite]">');
    } else {
        print('disabled value="'.$row['quantite'].'">');
    }
    print('</td>
    <td>  
        <select '.($empty ? 'name="livre[0][taxe]"' : 'disabled').'>
            <option value="0.0" '.($empty && $row['taxes'] == 0 ? 'selected': '').'>TTC</option>
            <option value="0.055" '.($empty || $row['taxes'] == 0.055 ? 'selected': '').'>5.5%</option>
            <option value="0.2" '.($empty && $row['taxes'] == 0.2 ? 'selected': '').'>20%</option>
        </select>
    </td>
</tr>');
}
?>