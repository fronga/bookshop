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

function search() {
  // Declare variables 
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("list");
  tr = table.getElementsByTagName("tr");
  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    // console.log(tr[i].getElementsByTagName("td").map(function (e){return e.value;}));
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}