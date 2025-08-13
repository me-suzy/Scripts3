<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  function hilite($text) {
    global $query;
    $uquery = strtoupper($query);
    if($query)
        return ereg_replace("$query", "<b>$query</b>", ereg_replace("$uquery", "<b>$uquery</b>", $text));
    else
        return $text;
  }

  $user = new user($login);
  $document = new document($doc_id);
  $document->get_access($user->id);
  $author = $document->author;
  $maintainer = $document->maintainer;

  print_header("Document Information");

  neutral_table_start("center", 1, 0);

  echo "<tr>\n";
    echo "<td align=\"left\" valign=\"top\">\n";
    echo "<img src=\"pix/". get_extension($document->name) ."-l.gif\" height=\"32\" width=\"32\" alt=\"[". strtoupper(get_extension($document->name)) ."]\">\n";
    echo "</td>\n";
    echo "<td align=\"center\">\n";
    echo "<h2 align=\"center\">Details for $document->name</h2>\n";
    echo "</td>\n";
    echo "<td align=\"right\"><form action=\"download.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" value=\"Download\"></form></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\">\n";
    neutral_table_start("center", 1, 0);
    echo "<tr>\n<td width=\"400\">\n";
    printf("<p>%s\n", ($document->info == NULL) ? "No information" : hilite(htmlspecialchars(stripslashes($document->info))) );
    printf("%s\n", (get_extension($document->name) != "exe") ? "" : "<p class=\"descb\">Note: This application has not been scanned for viruses!" );
    echo "</td>\n</tr>\n";
    table_end();
    echo "</td>\n";
    echo "<td rowspan=\"12\" valign=\"top\">\n";
    neutral_table_start("center", 1, 0);
    echo "<tr>\n<td>\n";
    echo "Keywords:\n";
    echo "<ul>\n";
    $kw = current($document->keywords);
    do {
      echo "<li>". hilite($kw) ."\n";
    } while( $kw = next($document->keywords) );
    echo "</ul>\n";
    echo "</td>\n</tr>\n";
    table_end();
    echo "</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\"> </td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>File name:</td>\n";
    printf("<td><img align=\"right\" src=\"pix/%s.gif\" height=\"15\" wifth=\"15\" alt=\"[ Access: %s ]\">%s</td>\n"
      , $document->level
      , access_string( ($document->level == NULL) ? "" : $document->level )
      , hilite($document->name) );
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>File size:</td>\n";
    echo "<td>". number_format($document->size, 0, ".", ",") ." bytes</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Mime type:</td>\n";
    echo "<td>$document->type</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td valign=\"top\">Download time:</td>\n";
    $seconds = ($document->size/5500) % 60;
    if($seconds<10)
        $seconds = "0$seconds";
    $minutes = number_format((($document->size/5500) - $seconds)/60, "0", "","");
    echo "<td>About $minutes:$seconds at 56K\n<br>No time at all at 10 Mbps</td>";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\"> </td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Author:</td>\n";
    echo "<td>$author->name &lt;<a href=\"mailto:$author->email\">$author->email</a>&gt;</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Maintainer:</td>\n";
    echo "<td>$maintainer->name &lt;<a href=\"mailto:$maintainer->email\">$maintainer->email</a>&gt;</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Created:</td>\n";
    echo "<td>$document->created</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\"> </td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Revision:</td>\n";
    echo "<td>$document->revision</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Last update:</td>\n";
    printf("<td>%s</td>\n", ($document->modified == NULL ) ? "-" : $document->modified );
  echo "</tr>\n";


  if( may_god($user->id, $document->id) ) {
      echo "<tr>\n";
        echo "<td colspan=\"3\">\n";
        echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
        echo "<tr>\n";
        echo "<td align=\"right\" width=\"75%\"><form action=\"edit.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" value=\"Edit Details\"></form></td>\n";
        echo "<td align=\"right\" width=\"25%\"><form action=\"acl.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" value=\"Edit Access\"></form></td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "</td>\n";
      echo "</tr>\n";
  }

  table_end();

  print_footer()

?>
