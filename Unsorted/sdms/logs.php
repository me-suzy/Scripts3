<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);

  print_header("View Download Logs");

  neutral_table_start("center", 0, 1);

  echo "<tr>\n";
    printf("  <th><b>User</b></th>\n");
    printf("  <th><b>.</b></th>\n");
    printf("  <th><b>Filename</b></th>\n");
    printf("  <th><b>Rev Downl.</b></th>\n");
    printf("  <th><b>Rev Curr.</b></th>\n");
    printf("  <th><b>Date</b></th>\n");
    printf("  <th><b>Address</b></th>\n");
  echo "</tr>\n";

  $res = @mysql_query("SELECT COUNT(*) FROM documents_log");
  $row = @mysql_fetch_array($res);
  $total = $row[0];

  if(!isset($next))
      $next = 0;

  $res = mysql_query("SELECT l.document AS id,l.revision AS revision,d.revision AS current,DATE_FORMAT(l.date, '%d/%m/%Y %H:%i:%S') AS date,u.name AS user,u.email AS email,d.name AS name,l.address AS address FROM documents_log AS l LEFT JOIN users AS u ON u.id=l.user LEFT JOIN documents AS d ON d.id=l.document ORDER BY l.date DESC LIMIT $next,20");

  if( ! ($count = @mysql_num_rows($res)) ) {
    echo "<tr>\n";
    echo "  <td align=\"center\" colspan=\"7\">No downloads found</td>\n";
    echo "</tr>\n";
  } else {
    printf("<h2 align=\"center\">Listing downloads %d - %d of $total</h2>\n", ($next+1), (($next+20) > $total) ? $total : ($next+20) );
    while($row = @mysql_fetch_array($res)) {
      echo "<tr>\n";
      echo "  <td align=\"left\">$row[user]</td>\n";
      echo "  <td><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\"></td>\n";
      echo "  <td align=\"left\"><a href=\"detail.php?doc_id=$row[id]\">$row[name]</a></td>\n";
      echo "  <td align=\"center\">$row[revision]</td>\n";
      echo "  <td align=\"center\">$row[current]</td>\n";
      echo "  <td>$row[date]</td>\n";
      echo "  <td>$row[address]</td>\n";
      echo "</tr>\n";
    }
    echo "<tr>\n";
    echo "<td colspan=\"7\">\n";

    echo "<table border=\"0\" width=\"100%\">\n";
    echo "<tr>\n";

    if($next > 0)
        printf("  <td align=\"left\"><a href=\"logs.php?next=%d\">&lt; Prev</a></td>\n", $next-20);
    else
        echo "<td> </td>\n"; 
    echo "<td colspan=\"4\" align=\"right\"> </td>\n";
    if(($next+20) < $total)
        printf("  <td align=\"right\"><a href=\"logs.php?next=%d\">Next &gt;</a></td>\n", $next+20);
    else
        echo "<td> </td>\n"; 

    echo "</td>\n";
    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
  }

  table_end();

  print_footer()

?>
