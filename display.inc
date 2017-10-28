<?php

include("piece.pm");

#______________________________________________
function format_volume( $obj ) {
  $string = "<I>".$obj->title."</I> ".$obj->volume.", ".$obj->editor.", p. ".$obj->page;
  if ( $obj->num ) 
    if ( $obj->num == 1 ) $string .= 'a';
    else $string .= 'b';
  return $string;
}

#______________________________________________
function format_pieces( $obj ) {
  $string = '';
  foreach ( $GLOBALS['rites'] as $rite ) {
    if ( $obj[$rite]>0 ) {
      $url = "<A HREF=\"javascript:show_piece(".$obj[$rite].")\">".$obj[$rite]."</A>";
      $string .= $url.' ('.ucfirst(substr($rite,0,3)).') ';
      #$string .= $obj[$rite].' ('.ucfirst(substr($rite,0,3)).') ';
    }
  }
  return $string;
}

#______________________________________________
function display_pieces() {
  
  # List of fields to show, and possible sorting options (blank = no sorting)
  $fields = array('ID'          => 'id',
                  'Compositeur' => 'composer',
                  'Morceau'     => '',
                  'Jou&eacute;' => 'count',
                  'Derni&egrave;re fois' => 'last');
  
  $db = $GLOBALS['db'];
  
  # Fetch full list of pieces
  $pieces = array();
  $query = "SELECT * FROM pieces";
  $result = mysql_query($query,$db) or die ("Query $query failed: ".mysql_error());
  while ( $answer = mysql_fetch_object($result) ) {
    $pieces[] = new piece($answer);
  }
  mysql_free_result($result);

  # Print totals
  print "<span class=\"totals\">".count($pieces)." morceaux trouv&eacute;s</span>\n";

  # Sorting options
  $order = 0;
  $sortby = "composer";
  if ( array_key_exists('order',$_GET) ) { $order = $_GET['order']; }
  if ( array_key_exists('sortby',$_GET) ) { $sortby = $_GET['sortby']; }

  # Order as requested
  usort($pieces, build_sorter($sortby,$order));

  # Print table header with field names
  print "<TR><TH>Row</TH>";
  foreach ( $fields as $name => $var ) {
    print "<TH NOWRAP>";
    if ( $var ) {
      $url = "?table=pieces&sortby=$var&order=";
      $arrow = '  ';
      if ( $var == $sortby ) {
        $url .= abs($order-1);
        $arrow = "&".($order?'u':'d')."arr; ";
      } else {
        $url .= $order;
      }
      print "<A HREF=\"$url\">$arrow";
    }
    print $name;
    if ( $var ) print "</A>";
    print "</TH>";
  }
  print "</TR>\n";

  $ipiece = 0; 
  foreach ( $pieces as $piece ) {
    print '<TR><TD>'.++$ipiece.'</TD>';
    print '<TD><A HREF="javascript:show_piece('.$piece->id.')">'.$piece->id."</A></TD>";
    print "<TD>".$piece->composer."</TD>";
    print "<TD>".format_volume($piece)."</TD>";
    print "<TD>".$piece->count."</TD>";
    if ( $piece->count > 0 ) {
      print "<TD>".$piece->last."</TD></TR>\n";
    } else {
      print "<TD></TD></TR>\n";
    }
  }
}

#______________________________________________
function display_ceremonies() {
  
  $fields = array('ID'         => 'id',
                  'Date'       => 'date',
                  'F&ecirc;te' => 'name',
                  'Morceaux'   => '',
                  'Commentaire'=> '');
  
  $db = $GLOBALS['db'];
  
  # Sorting options
  $order = 0;
  $sortby = "date";
  if ( array_key_exists('order',$_GET) ) { $order = $_GET['order']; }
  if ( array_key_exists('sortby',$_GET) ) { $sortby = $_GET['sortby']; }
  
  # Fetch list of pieces
  $query = "SELECT * FROM ceremonies ORDER BY $sortby ".($order?'ASC':'DESC');
  $result = mysql_query($query,$db) or die ("Query $query failed: ".mysql_error());

  # Print totals
  print "<span class=\"totals\">".mysql_num_rows($result)." c&eacute;r&eacute;monies trouv&eacute;es</span>\n";

  # Print table header with field names
  print "<TR>";
  foreach ( $fields as $name => $var ) {
    print '<TH>';
    $arrow = '  ';
    if ( $var ) {
      $url = "?table=ceremonies&sortby=$var&order=";
      if ( $var == $sortby ) {
         $url .= abs($order-1);
         $arrow = "&".($order?'d':'u')."arr; ";
      }
      else $url .= $order;
      print '<A HREF="'.$url.'">'.$arrow;
    }
    print $name;
    if ( $var ) print '</A>';
    print '</TH>';
  }
  print "</TR>\n";
  
  while ( $answer = mysql_fetch_array($result) ) {
    print "<TR><TD>".$answer['id']."</TD>";
    print "<TD>".$answer['date']."</TD>";
    print "<TD>".$answer['name']."</TD>";
    print "<TD>".format_pieces($answer)."</TD>";
    print "<TD>".$answer['comment']."</TD></TR>\n";
  }
}

?>
