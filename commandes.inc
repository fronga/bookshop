<?php
require_once("defines.inc");

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
        # $mysqli->query($query);
        # $commande_id = $mysqli->insert_id;
    } else {
        $commande_id = $json->commande->id;
    }

    # Process livres
    $query = "INSERT INTO `livre_commande` (";
    $fields = [];
    foreach ($json->livre[0] as $key => $val ) {
        $fields[] = $key;
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
    $zero = $count - 1;
    echo '<tr class="cloningInput"><td id="count">'.$count.'</td>';
    echo '  <td><input name="livre['.$zero.'][src_id]" size=10 placeholder="ID" '.(print($row ? $row->src_id : "")).'></td>';
    echo '  <td><select class="select_auteur" name="livre['.$zero.'][fk_auteur_id]" onload=\"getAuthors(this)\" onchange=\"getAuthorBooks(this)\">';
    echo '          <option value="'.(print($row ? $row->fk_auteur_id : '')).'"></option>';
    echo "      </select>";
    echo "      <select name='livre[".$zero."][fk_livre_id]'><option value=''>--</option></select>";
    echo '  </td>
    <td><input name="livre['.$zero.'][prix_achat_ht]" type="number" placeholder="Prix H.T." min=0 max=100 step=0.01 style="width: 7em"></td>
    <td>
        <input name="livre['.$zero.'][remise]" type="number" placeholder="%" min=0 max=100 step=5 style="width: 5em">
    </td>
    <td>
        <input name="livre['.$zero.'][quantite]" type="number" placeholder="Qté" min=1 max=100 style="width: 5em">
    </td>
    <td>  
        <select name="livre['.$zero.'][taxe]">
            <option value="0.0">TTC</option>
            <option value="0.055" selected>5.5%</option>
            <option value="0.2">20%</option>
        </select>
    </td>
</tr>';
}
?>