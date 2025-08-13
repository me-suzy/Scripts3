<?

    require('lib/config.inc');
    require('lib/auth.inc');
    require('lib/classes.inc');
    require('lib/functions.inc');

    $user = new user($login);

    print_header("Update a document");

    echo "<form action=\"update.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"16777216\">\n";

    table_start("center", 1, 0);

    echo "<tr>\n";
      echo "<td align=\"center\" colspan=\"2\"><font color=\"$cfg[table_text]\"><h3 align=\"center\">Update a document (Max 16 Mb)</h3></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">Document:</font></b></td>\n";
      echo "<td><select name=\"doc_id\">\n";
      if($user->god)
        $res = @mysql_query("SELECT d.id AS id,d.name AS name,u.name AS user FROM documents AS d LEFT JOIN users AS u on d.author=u.id ORDER BY name ASC");
      else
        $res = @mysql_query("SELECT d.id AS id,d.name AS name,a.level AS level FROM documents AS d LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (a.level='W' OR a.level='G') ORDER BY name ASC");
      if(!mysql_num_rows($res)) {
        echo "<option selected>You cannot update any documents</option>\n";
      } else {
        while( $row = @mysql_fetch_array($res)) {
          if($user->god)
            echo "<option value=\"$row[id]\">$row[name] &nbsp;&nbsp; [$row[user]]</option>\n";
          else
            echo "<option value=\"$row[id]\">$row[name]</option>\n";
      } // while
    }
    echo "</select><br>\n";
    echo "<font class=\"desc\">Select a document to update</font></td>\n";
    echo "</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">File:</font></b></td>\n";
      echo "<td><input type=\"file\" name=\"userfile\"><br><font class=\"desc\">Click browse to select a file</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">Keywords:</font></b></td>\n";
      echo "<td><input type=\"text\" maxsize=\"512\" name=\"keywords\"><br><font class=\"desc\">Enter keywords delimited by spaces or commas or<br>leave blank to keep originals</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td valign=\"top\"><b><font color=\"$cfg[table_text]\">Info:</font></b></td>\n";
      echo "<td><textarea name=\"info\" rows=\"4\" cols=\"28\"></textarea><br><font class=\"desc\">Enter a short comment describing this document or<br>leave blank to keep original</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Update this document\"></font></td>\n";
    echo "</tr>\n";

    table_end();

    echo "</form>\n";

    print_footer();

?>
