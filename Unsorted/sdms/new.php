<?

    require('lib/config.inc');
    require('lib/auth.inc');
    require('lib/classes.inc');
    require('lib/functions.inc');

    $user = new user($login);

    if(! $user->god) {
        print_header("Access Dennied!");
        exit;
    }

    print_header("Upload a new document");

    echo "<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"16777216\">\n";

    table_start("center", 1, 0);

    echo "<tr>\n";
      echo "<td align=\"center\" colspan=\"2\"><font color=\"$cfg[table_text]\"><h3 align=\"center\">Upload new document (Max 16 Mb)</h3></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">File:</font></b></td>\n";
      echo "<td><input type=\"file\" name=\"userfile\"><br><font class=\"desc\">Click browse to select a file</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">Access:</font></b></td>\n";
      echo "<td><select name=\"level\">\n";
      echo "<option value=\"X\">Everybody: No Access</option>\n";
      echo "<option value=\"R\">Everybody: Read-Only</option>\n";
      echo "<option value=\"W\">Everybody: Read-Write</option>\n";
      if($user->god)
        echo "<option value=\"G\">Everybody: God Mode</option>\n";
      echo "</select><br><font class=\"desc\">Select the default access level for normal users</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td><b><font color=\"$cfg[table_text]\">Keywords:</font></b></td>\n";
      echo "<td><input type=\"text\" maxsize=\"512\" name=\"keywords\"><br><font class=\"desc\">Enter keywords delimited by spaces or commas</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td valign=\"top\"><b><font color=\"$cfg[table_text]\">Info:</font></b></td>\n";
      echo "<td><textarea name=\"info\" rows=\"4\" cols=\"28\"></textarea><br><font class=\"desc\">Enter a short comment describing this document</font></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
      echo "<td align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Upload this document\"></font></td>\n";
    echo "</tr>\n";

    table_end();

    echo "</form>\n";

    print_footer();

?>
