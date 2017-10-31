// Clone a row in a form
var regex = /^(.+?_)(\d+)$/i;
var cloneIndex = $(".cloningInput").length + 1; 
console.log("Clone index: " + cloneIndex);

function clone() {
  $(".cloningInput").clone()
      .appendTo("tbody.rowContainer")
      .attr("id", "clonedInput" +  cloneIndex)
      .attr("class", null)
      .find("*")
      .each(function() {
        var id = this.id || "";
        var match = id.match(regex) || [];
        if (match.length == 3) {
            this.id = match[1] + (cloneIndex);
        }
      })
      .on('click', 'button.clone', clone)
      .on('click', 'button.remove', remove);
  cloneIndex++;
}
function remove(){
  $(this).parents(".cloningInput").remove();
}
$("button.clone").on("click", clone);
$("button.remove").on("click", remove);