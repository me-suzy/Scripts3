<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);

  print_header("Contacts List");

  echo "<h2 align=\"center\">Contact List</h2>\n";

  neutral_table_start("center", 1, 0);

  echo "<tr>\n";
    echo "<th><b>Name</b></th>\n";
    echo "<th><b>Email</b></th>\n";
  echo "</tr>\n";

  $res = @mysql_query("SELECT user,name,email FROM users ORDER BY name ASC");
  while( $row = @mysql_fetch_array($res) )
    echo "<tr><td align=\"left\"><a href=\"userdetail.php?contact=$row[user]\">$row[name]</a></td><td align=\"left\">&lt;<a href=\"mailto:$row[email]\">$row[email]</a>&gt;</td>\n</tr>\n";
  echo "</tr>\n";

  table_end();


  print_footer()

?>
