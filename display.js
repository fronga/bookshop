function show( id, view )
{
  window.open("show_" + view + ".php?id=" + id, "Informations " + view,
              config='height=200, width=400, toolbar=no, menubar=no, scrollbars=no,'
              + ' resizable=no, location=no, directories=no, status=no')
}
