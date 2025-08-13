<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);
  $document = new document($doc_id);

  if( !may_god($user->id, $document->id) ) {
     print_header("Permission Denied");
     echo "<h2 align=\"center\">Permission denied</h2>\n";
     print_footer();
     exit;
  }

  $tmpauthor = $document->author;
  $tmpmaintainer = $document->maintainer;

  if(isset($button)) {
      print_header("Save Document Information");
      if($document->name != $name) {
          if( get_extension($name) != get_extension($document->name))
              $name .= ".". get_extension($document->name);
          @mysql_query("UPDATE documents SET name='$name' WHERE id=$document->id");
      }
      if($document->revision != $revision) @mysql_query("UPDATE documents SET revision=$revision WHERE id=$document->id");
      if($tmpauthor->id != $author) @mysql_query("UPDATE documents SET author=$author WHERE id=$document->id");
      if($tmpmaintainer->id != $maintainer) @mysql_query("UPDATE documents SET maintainer=$maintainer WHERE id=$document->id");
      if($document->info == NULL)
          @mysql_query("INSERT INTO documents_info(id,info) VALUES($document->id,'". addslashes($info) ."')");
      else
          @mysql_query("UPDATE documents_info SET info='". addslashes($info) ."' WHERE id=$document->id");
  } else
      print_header("Edit Document Information");

  // Reload updated information.
  $document = new document($doc_id);
  $author = $document->author;
  $maintainer = $document->maintainer;

  echo "<form action=\"edit.php\" method=\"post\">\n";
  echo "<input type=\"hidden\" name=\"doc_id\" value=\"$document->id\">\n";
  neutral_table_start("center", 1, 0);

  echo "<tr>\n";
    echo "<td align=\"left\" valign=\"top\">\n";
    echo "<img src=\"pix/". get_extension($document->name) ."-l.gif\" height=\"32\" width=\"32\" alt=\"[". strtoupper(get_extension($document->name)) ."]\">\n";
    echo "</td>\n";
    echo "<td align=\"center\">\n";
    if(isset($button))
        echo "<h2 align=\"center\">Saved details for $document->name</h2>\n";
    else
        echo "<h2 align=\"center\">Edit details for $document->name</h2>\n";
    echo "</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\">\n";
    echo "<textarea name=\"info\" wrap=\"virtual\" rows=\"3\" cols=\"34\">". htmlspecialchars(stripslashes($document->info)) ."</textarea>\n";
    echo "</td>\n";
    echo "<td rowspan=\"11\" valign=\"top\">\n";
    neutral_table_start("center", 1, 0);
    echo "<tr>\n<td>\n";
    echo "Keywords:\n";
    $document->print_keywords();
    echo "</td>\n</tr>\n";
    table_end();
    echo "</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\"> </td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>File name:</td>\n";
    echo "<td><input type=\"text\" name=\"name\" value=\"$document->name\"><br><font class=\"descb\">Note: The file extension .". strtoupper(get_extension($document->name)) ." will remain in place<br>regardless of changes to it here</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>File size:</td>\n";
    $seconds = ($document->size/7000) % 60;
    if($seconds<10)
        $seconds = "0$seconds";
    $minutes = number_format((($document->size/7000) - $seconds)/60, "0", "","");
    echo "<td>". number_format($document->size, 0, ".", ",") ." bytes (About $minutes:$seconds @ 56K)\n</td>";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Mime type:</td>\n";
    echo "<td>$document->type</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td colspan=\"2\"> </td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Author:</td>\n";
    echo "<td><select name=\"author\">\n";
    $res = @mysql_query("SELECT id,name,email FROM users ORDER BY id ASC");
    while($row = @mysql_fetch_array($res))
        printf("<option value=\"$row[id]\"%s>$row[name] &lt;$row[email]&gt;</option>\n", ($row[id] == $author->id) ? " selected" : "" );
    echo "</select></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Maintainer:</td>\n";
    echo "<td><select name=\"maintainer\">\n";
    $res = @mysql_query("SELECT id,name,email FROM users ORDER BY id ASC");
    while($row = @mysql_fetch_array($res))
        printf("<option value=\"$row[id]\"%s>$row[name] &lt;$row[email]&gt;</option>\n", ($row[id] == $maintainer->id) ? " selected" : "" );
    echo "</select></td>\n";
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
    echo "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"revision\" value=\"$document->revision\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
    echo "<td>Last update:</td>\n";
    echo "<td>$document->modified</td>\n";
    if( $row[uid] == $user->id )
        echo "<td align=\"right\"><form action=\"edit.php\" method=\"post\"><input type=\"hidden\" name=\"doc_id\" value=\"$document->id\"><input type=\"submit\" value=\"Edit\"></form></td>\n";
  echo "</tr>\n";

  echo "<tr><td colspan=\"3\" align=\"center\">\n";
  echo "<input type=\"submit\" name=\"button\" value=\"Save details\">\n";
  echo "</td></tr>\n";
  

  table_end();
  echo "</form>\n";

  print_footer()

?>
