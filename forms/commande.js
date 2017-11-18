function getXHR() {
  try {
    xhr = new XMLHttpRequest();
    return xhr;
  } catch (e) {
    // Something went wrong
    alert("Your browser broke!");
    return false;
  }
}

$('.select_auteur').ready(function () {
  getAuthors($('.select_auteur'));
});

function getAuthors(element) {
  // Get list of authors dynamically
  // Argument: the select element to fill
  console.log("getAuthors called");
  var xhr = getXHR();  // The variable that makes Ajax possible!
  if (!xhr) { return false; }
  xhr.onreadystatechange = function () {
    element.html("<OPTION VALUE=''>-- Choisir un auteur --</OPTION>");
    if (xhr.readyState == 4) {
      result = JSON.parse(xhr.response);
      $.each(result, function (i, item) {
        var nom = (item.nom ? item.nom : '') + (item.postfix ? ' ' + item.postfix : '');
        var prenom = (item.prefix ? item.prefix + ' ' : '') + (item.prenom ? item.prenom : '');
        element.append($('<option>', {
          value: item.id,
          text: nom + ", " + prenom
        }));
      });
    } else if (xhr.readyState > 0 && xhr.readyState < 4) {
      element.html("<OPTION>Chargement en cours...</OPTION>");
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
    xhr.send(null);
  }
}

// Clone a row in a form
var regex = /^(livre\[)\d+(\].*)$/i;
var cloneIndex = $(".cloningInput").length + 1;
function clone() {
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
            $(this).html(cloneIndex + 1 + ".");
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
        console.log(match[1] + ", " + match[2]);
        if (form.livres[match[1]]) {
          form.livres[match[1]][match[2]] = item.value;
        } else {
          form.livres[match[1]] = {};
          form.livres[match[1]][match[2]] = item.value;
        }
      }
    }
  );
  // console.log(form);

  // Validate commande fields
  var cerrors = "";
  ["fournisseur", "date", "frais"].forEach(
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
  for (var i = 0; i < form.livres.length; ++i) {
    var lerrors = "";
    ["titre", "auteur", "prix", "remise", "quantite"].forEach(
      function (field) {
        if (!form.livres[i][field]) {
          if (error_list.length == 0) {
            error_list.push("Champs manquants dans les livres:");
          }
          if (!lerrors) {
            lerrors = "- livre " + (i + 1) + ": ";
          }
          lerrors += field + ", ";
        }
      }
    );
    if (lerrors) {
      error_list.push(lerrors.substr(0, lerrors.length - 2));
    }
  }
  if (error_list.length > 0) {
    console.log(error_list.length);
    alert(error_list.join("\n"));
    return false;
  }

  return true;
}