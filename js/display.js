function show( id, view ) {
  window.open("show_" + view + ".php?id=" + id, "Informations " + view,
              config='height=200, width=400, toolbar=no, menubar=no, scrollbars=no,'
              + ' resizable=no, location=no, directories=no, status=no')
}


function print_commande(table) {
    var newWindow = window.open("", "_blank");
    newWindow.document.body.innerHTML = decodeURIComponent(table);
    newWindow.document.head.innerHTML = '\
<TITLE>Imprimer commande</TITLE> \
<STYLE> \
    table.list { text-align:left; border-collapse:collapse; } \
    table.list tr { padding:0px; } \
    table.list td {  padding-left:10px; padding-top:2px; } \
    table.list th {  padding:10px; text-align: center; } \
    table.sublist { font-size:120%; } \
</STYLE>';
}


function display_livres_commande( commande_id, elements ) {
    var xhr = getXHR();  // The variable that makes Ajax possible!
    if (!xhr) { return false; }
    xhr.onloadend = function (pe) {
        url = "/~fronga/procure/add.php?view=commandes&commande=" + commande_id;
        table = xhr.response;
        html = table + "<br><button type=\"button\" onclick=\"window.open('" + url + "')\">Modifier</button>";
        html += "<button type=\"button\" onclick=\"print_commande('" + encodeURIComponent(table).replace(/'/g, '&quot;') + "')\">Imprimer</button>";
        elements.html(html);
    }
    xhr.open("GET", "query_commande.php?commande=" + commande_id, true);
    xhr.send(null);
}
