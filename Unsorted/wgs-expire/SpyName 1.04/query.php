<FORM method=post>
<TEXTAREA name=query rows=20 cols=100></TEXTAREA>
<BR>
<INPUT type=submit>
</FORM>

<?
 $_POST = $HTTP_POST_VARS;
 if ($_POST[query] != "") {
  $dbhost = "localhost";
  $dbname = "dtrax_mikeapted";
  $dbuser = "dtrax_mike";
  $dbpassword = "apted";
  $link = mysql_connect("$dbhost", "$dbuser", "$dbpassword")
        or die("Could not connect");
  mysql_select_db("$dbname") or die("Could not select database");
  $query = $_POST[query];
  print "$query:<P>\n";
  $result = mysql_query($query) or die("Query failed");
    print "<table>\n";
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        print "\t<tr>\n";
        foreach ($line as $col_value) {
            print "\t\t<td>$col_value</td>\n";
        }
        print "\t</tr>\n";
    }
    print "</table>\n";
  mysql_free_result($result);
  mysql_close($link);
 }
?>

