

// Call when page rendering is done
$(function () {
  getAuthors($('.select_auteur'));
});

function getAuthors(elements) {
  // Get list of authors dynamically
  // Argument: the select element to fill
  var xhr = getXHR();  // The variable that makes Ajax possible!
  if (!xhr) { return false; }
  xhr.onloadend = function (pe) {
    elements.html("<option value=''>-- Choisir un auteur --</option>");
    if (xhr.readyState == XMLHttpRequest.DONE) {
      result = JSON.parse(xhr.response);
      $.each(result, function (i, item) {
        var nom = (item.nom ? item.nom : '') + (item.postfix ? ' ' + item.postfix : '');
        var prenom = (item.prefix ? item.prefix + ' ' : '') + (item.prenom ? item.prenom : '');
        elements.append($('<option>', {
          value: item.id,
          text: nom + ", " + prenom
        }));
      });
    } else if (xhr.readyState > 0 && xhr.readyState < 4) {
        elements.html("<OPTION>Chargement en cours...</OPTION>");
    }
  }
  xhr.open("GET", "query.php?table=auteurs", true);
  xhr.send(null);
}

function getAuthorBooks(element) {
  // Get books dynamically from author
  // Argument: the select element from which to take the selected author
  var xhr = getXHR();  // The variable that makes Ajax possible!
  if (!xhr) { return false; }

  // function that will receive data sent from the server
  xhr.onreadystatechange = function () {
    var selectBook = $(element).next();
    if (xhr.readyState == 4) {
      result = JSON.parse(xhr.response);
      selectBook.empty()
      $.each(result, function (i, item) {
        selectBook.append($('<option>', {
          value: item.id,
          text: item.titre
        }));
      });
    } else if (xhr.readyState > 0 && xhr.readyState < 4) {
      selectBook.html("<OPTION>Chargement en cours...</OPTION>");
    }
  }
  var src_value = element.value;
  if (src_value) {
    xhr.open("GET", "query.php?table=livres&id=" + src_value, true);
  } else {
    xhr.open("GET", "query.php?table=livres", true);
  }
  xhr.send(null);
}

// Clone a row in a form
var regex = /^(livre\[)\d+(\].*)$/i;
var cloneIndex = $(".cloningInput").length + 1;
function clone(count) {
  $(".cloningInput").clone()
    .appendTo("tbody.rowContainer")
    .attr("id", "clonedInput" + cloneIndex)
    .attr("class", null)
    .find("*")
    .each(function () {
      // Update id and name
      ['id', 'name'].forEach(function (element) {
        if (this[element]) {
          if (match = this[element].match(regex)) {
            this[element] = match[1] + cloneIndex + match[2];
          } else if (this[element] == "count") {
            $(this).html(count + cloneIndex + ".");
          }
        }
      }, this);
    });
  cloneIndex++;
}

function remove() {
  $("#clonedInput" + (cloneIndex - 1)).remove();
  cloneIndex--;
}

function validateForm() {
  // Jsonify form data and validate it
  var form = { "commande": {}, livres: [] };
  var cregex = /^commande\[(.*?)\]$/
  var lregex = /^livre\[(\d+)\]\[(.*?)\]$/
  $("#bookForm").serializeArray().forEach(
    function (item) {
      if (match = item.name.match(cregex)) {
        form["commande"][match[1]] = item.value;
      } else if (match = item.name.match(lregex)) {
        if (form.livres[match[1]]) {
          form.livres[match[1]][match[2]] = item.value;
        } else {
          form.livres[match[1]] = {};
          form.livres[match[1]][match[2]] = item.value;
        }
      }
    }
  );
  
  // Validate commande fields
  var cerrors = "";
  ["fk_fournisseur_id", "date", "frais"].forEach(
    function (field) {
      if (!form.commande[field]) {
        if (!cerrors) {
          cerrors = "Les champs suivants manquent dans la commande:\n"
        }
        cerrors += field + ", ";
      }
    }
  );
  if (cerrors.length > 0) {
    cerrors = cerrors.substr(0, cerrors.length - 2);
    alert(cerrors);
    return false;
  }

  // Validate livre fields
  var error_list = [];
  form.livres.forEach(function(item, index, arr) {
    var lerrors = "";
    ["src_id", "fk_auteur_id", "prix_achat_ht", "remise", "quantite"].forEach(
      function (field) {
        if (!item[field]) {
          if (error_list.length == 0) {
            error_list.push("Champs manquants dans les livres:");
          }
          if (!lerrors) {
            lerrors = "- livre " + (index + 1) + ": ";
          } else {
            lerrors += ', ';
          }
          lerrors += field;
        }
      }
    );
    if (lerrors) {
      error_list.push(lerrors);
    }
  });
  if (error_list.length > 0) {
    alert(error_list.join("\n"));
    return false;
  }

  return true;
}

function setValue(key, values, pattern) {
  var name = pattern + key + "]";
  var element = $("[name='" + name + "']");
  if (element) {
    console.log(element);
    element.val(values[key]);
  } 
}

function getBookFromID(element) {
  // Get name of fournisseur
  e = document.getElementsByName("commande[fk_fournisseur_id]")[0];
  fk_fournisseur_id = e.options[e.selectedIndex].value;
  src_id = element.value
  pattern = element.name.substring(0, element.name.search("src_id"));
  
  var xhr = getXHR();  // The variable that makes Ajax possible!
  if (!xhr) { return false; }
  xhr.onloadend = function (pe) {
    if (xhr.readyState == XMLHttpRequest.DONE) {
      result = JSON.parse(xhr.response);
      if (result && result.length == 1) {
        var livre = result[0];
        console.log(livre);
        var key = 'fk_livre_id';
        $("[name='" + pattern + key + "]']").append(
          $('<option>', {
            value: livre[key],
            text: livre["titre"],
            selected: true
          }));
        setValue('prix_achat_ht', livre, pattern);
        setValue('remise', livre, pattern);
        setValue('quantite', livre, pattern);
        setValue('taxes', livre, pattern);
        setValue('fk_auteur_id', livre, pattern)
        
      }
      
      // $.each(result, function (i, item) {
      //   var nom = (item.nom ? item.nom : '') + (item.postfix ? ' ' + item.postfix : '');
      //   var prenom = (item.prefix ? item.prefix + ' ' : '') + (item.prenom ? item.prenom : '');
      //   elements.append($('<option>', {
      //     value: item.id,
      //     text: nom + ", " + prenom
      //   }));
      // });
    } else if (xhr.readyState > 0 && xhr.readyState < 4) {
    }
  }
  url = "query.php?table=livre_commande&src_id=" + src_id + "&fk_fournisseur_id=" + fk_fournisseur_id;
  xhr.open("GET", url, true);
  xhr.send(null);

}