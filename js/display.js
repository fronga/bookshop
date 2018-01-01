function show( id, view ) {
  window.open("show_" + view + ".php?id=" + id, "Informations " + view,
              config='height=200, width=400, toolbar=no, menubar=no, scrollbars=no,'
              + ' resizable=no, location=no, directories=no, status=no')
}

function display_livres_commande( commande_id, elements ) {
    var xhr = getXHR();  // The variable that makes Ajax possible!
    if (!xhr) { return false; }
    xhr.onloadend = function (pe) {
        url = "/~fronga/procure/add.php?view=commandes&commande=" + commande_id;
        html = xhr.response;
        html += "<br><button type=\"button\" onclick=\"window.open('" + url + "')\">Modifier</button>";
        elements.html(html);
    }
    xhr.open("GET", "query_commande.php?commande=" + commande_id, true);
    xhr.send(null);
}
