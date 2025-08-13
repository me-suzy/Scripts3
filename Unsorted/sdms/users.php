<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);

  print_header("Edit Users");

  switch($button) {

      case "Yes, add user":
          echo "<h3 align=\"center\">Action: Add user $victim: \n";
          @mysql_query("INSERT INTO users(user,pass,name,email) VALUES('$victim',PASSWORD('$pass'),'". addslashes($name) ."','$email')");
          if(mysql_errno())
              echo "Error<br>". mysql_error() ."</h3>";
          else 
              echo "OK</h3>";
          break;

      case "Add User":
          echo "<h2 align=\"center\">Add user $victim?</h2>\n";
          echo "<div align=\"center\">\n";
          echo "<form action=\"users.php\" method=\"post\">\n";
          echo "<input type=\"hidden\" name=\"victim\" value=\"$victim\">\n";
          echo "<input type=\"hidden\" name=\"pass\" value=\"$pass\">\n";
          echo "<input type=\"hidden\" name=\"name\" value=\"$name\">\n";
          echo "<input type=\"hidden\" name=\"email\" value=\"$email\">\n";
          echo "<input type=\"submit\" name=\"button\" value=\"Yes, add user\">\n";
          echo "<input type=\"submit\" name=\"button\" value=\"Oh, never mind\">\n";
          echo "</form>\n";
          echo "</div>\n";
          print_footer();
          exit;
          break;

      case "Delete User":
          $tmp = explode(",", $victim);
          echo "<h2 align=\"center\">Delete user $tmp[1]?</h2>\n";
          echo "<div align=\"center\">\n";
          echo "<form action=\"users.php\" method=\"post\">\n";
          echo "<input type=\"hidden\" name=\"victim\" value=\"$victim\">\n";
          echo "<input type=\"submit\" name=\"button\" value=\"Yes, I am sure\">\n";
          echo "<input type=\"submit\" name=\"button\" value=\"Oh, never mind\">\n";
          echo "</form>\n";
          echo "</div>\n";
          print_footer();
          exit;
          break;

      case "Yes, I am sure":
          echo "<h3 align=\"center\">Action: Delete user $victim: \n";;
          $tmp = explode(",", $victim);
          @mysql_query("DELETE FROM users WHERE id=$tmp[0]");
          if(mysql_errno())
              echo "Error<br>". mysql_error() ."</h3>";
          else
              echo "OK</h3>";
          break;

      default:
          break;


  }

  echo "<form action=\"users.php\" method=\"post\">\n";
  table_start("center", 1, 0);
  echo "<tr>\n";
  echo "<td colspan=\"2\"><h3 align=\"center\"><font color=\"$cfg[table_text]\">Add a user</font></h3></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td><b><font color=\"$cfg[table_text]\">Login:</font></td>\n";
  echo "<td><input type=\"text\" name=\"victim\" size=\"16\" maxlength=\"16\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td><b><font color=\"$cfg[table_text]\">Password:</font></td>\n";
  echo "<td><input type=\"text\" name=\"pass\" size=\"16\" maxlength=\"8\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td><b><font color=\"$cfg[table_text]\">Name:</font></td>\n";
  echo "<td><input type=\"text\" name=\"name\" size=\"16\" maxlength=\"64\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td><b><font color=\"$cfg[table_text]\">Email:</font></td>\n";
  echo "<td><input type=\"text\" name=\"email\" size=\"16\" maxlength=\"64\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"button\" value=\"Add User\"></td>\n";
  echo "</tr>\n";
  table_end();
  echo "</form>\n";

  echo "<form action=\"users.php\" method=\"post\">\n";
  table_start("center", 1, 0);
  echo "<tr>\n";
  echo "<td colspan=\"2\"><h3 align=\"center\"><font color=\"$cfg[table_text]\">Delete a user</font></h3></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td><b><font color=\"$cfg[table_text]\">User:</font></td>\n";
  echo "<td><select name=\"victim\">\n";
  $res = @mysql_query("SELECT id,user,name FROM users ORDER BY name ASC");
  while( $row = @mysql_fetch_array($res) ) {
    $tmp = new user($row[user]);
    if(!$tmp->god)
        printf("<option value=\"%d,%s\">%s</option>\n", $tmp->id, $tmp->name, $tmp->name );
  }
  echo "</select></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"button\" value=\"Delete User\"></td>\n";
  echo "</tr>\n";
  table_end();
  echo "</form>\n";


  print_footer()

?>
