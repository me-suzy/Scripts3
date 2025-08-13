<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);

  print_header("List Documents");

  neutral_table_start("center", 1, 1);

  echo "<tr>\n";
    printf("  <th><b>.</b></th>\n");
    printf("  <th><a href=\"list.php?order=%s\">Filename</a></th>\n", ($order == "name DESC" ) ? "name%20ASC" : "name%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Size</a></th>\n", ($order == "size DESC" ) ? "size%20ASC" : "size%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Rev</a></th>\n", ($order == "revision DESC" ) ? "revision%20ASC" : "revision%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Author</a></th>\n", ($order == "author DESC" ) ? "author%20ASC" : "author%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Maintainer</a></th>\n", ($order == "maintainer DESC" ) ? "maintainer%20ASC" : "maintainer%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Created</a></th>\n", ($order == "cdate DESC" ) ? "cdate%20ASC" : "cdate%20DESC" );
    printf("  <th><a href=\"list.php?order=%s\">Modified</a></th>\n", ($order == "mdate DESC" ) ? "mdate%20ASC" : "mdate%20DESC" );
    printf("  <th>.</th>\n");
  echo "</tr>\n";

  if($user->god)
      if(isset($order))
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer ORDER BY ". rawurldecode($order) );
      else
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer ORDER BY id ASC");
  else
      if(isset($order))
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id ORDER BY ". rawurldecode($order) );
      else
          $res = mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,u2.name AS maintainer,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents AS d LEFT JOIN users AS u ON u.id=d.author LEFT JOIN users AS u2 ON u2.id=d.maintainer LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id ORDER BY id ASC");
  if( ! ($count = @mysql_num_rows($res)) ) {
    echo "<tr>\n";
    echo "  <td align=\"center\" colspan=\"7\">No documents found</td>\n";
    echo "</tr>\n";
  } else {
    echo "<h2 align=\"center\">Listing $count documents</h2>\n";
    while($row = @mysql_fetch_array($res)) {
      echo "<tr>\n";
      echo "  <td><a href=\"download.php?doc_id=$row[id]\"><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\" border=\"0\"></a></td>\n";
      echo "  <td><a href=\"detail.php?doc_id=$row[id]\">$row[name]</a></td>\n";
      if($row[size] < 0)
          continue;
      if( $row[size] < 10240 ) {
          $size_str = sprintf("%d bytes", $row[size]);
      } else if( $row[size] < 1048576 ) {
          $size_str = sprintf("%.1f Kb", ($row[size]/1024));
      } else {
          $size_str = sprintf("%.1f Mb", ($row[size])/(1024*1024));
      }
      echo "  <td align=\"right\">$size_str</td>\n";
      echo "  <td align=\"center\">$row[revision]</td>\n";
      echo "  <td>$row[author]</td>\n";
      echo "  <td>$row[maintainer]</td>\n";
      echo "  <td>$row[created]</td>\n";
    printf("  <td%s</td>\n", ($row[modified] == NULL) ? " align=\"center\">-" : ">$row[modified]" );
    printf("  <td align=\"center\"><img src=\"pix/%s.gif\" height=\"15\" width=\"15\" alt=\"[ Access: %s ]\"></td>\n", ($row[level] == NULL) ? "G" : $row[level], access_string( ($row[level] == NULL) ? "G" : $row[level] ) );
      echo "</tr>\n";
    }
  }

  table_end();

  print_footer()

?>
