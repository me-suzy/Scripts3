<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  $user = new user($login);
  $query = strtolower($query);

  print_header("Search Results");

  neutral_table_start("center", 0, 1);

  echo "<tr>\n";
    printf("  <th><b>.</b></th>\n");
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Filename</a></th>\n", ($order == "name,id DESC" ) ? "name,id%20ASC" : "name,id%20DESC" );
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Size</a></th>\n", ($order == "size DESC" ) ? "size%20ASC" : "size%20DESC" );
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Rev</a></th>\n", ($order == "revision DESC" ) ? "revision%20ASC" : "revision%20DESC" );
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Author</a></th>\n", ($order == "author DESC" ) ? "author%20ASC" : "author%20DESC" );
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Created</a></th>\n", ($order == "cdate DESC" ) ? "cdate%20ASC" : "cdate%20DESC" );
    printf("  <th><a href=\"search.php?query=$query&order=%s\">Modified</a></th>\n", ($order == "mdate DESC" ) ? "mdate%20ASC" : "mdate%20DESC" );
    printf("  <th><b>.</b></th>\n");
  echo "</tr>\n";

  if($user->god) {
    if(isset($order))
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id WHERE k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%' ORDER BY ". rawurldecode($order) );
    else
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id WHERE k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%' ORDER BY id ASC");
  } else {
    if(isset($order))
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%') ORDER BY ". rawurldecode($order) );
    else
      $res = mysql_query("SELECT DISTINCT k.id AS id,d.name AS name,d.type AS type,d.size AS size,u.name AS author,d.revision AS revision,DATE_FORMAT(d.created, '%d-%m-%Y, %H:%i:%S') AS created,DATE_FORMAT(d.modified, '%d-%m-%Y, %H:%i:%S') AS modified,d.created AS cdate,d.modified AS mdate,a.level AS level FROM documents_keywords AS k LEFT JOIN documents AS d ON k.id=d.id LEFT JOIN users AS u ON u.id=d.author LEFT JOIN documents_info AS i on i.id=k.id LEFT JOIN ACL AS a ON a.document_id=d.id WHERE a.user_id=$user->id AND (k.keyword LIKE '%$query%' OR d.name LIKE '%$query%' OR i.info LIKE '%$query%') ORDER BY id ASC");
  }
  if( ! ($count = @mysql_num_rows($res)) ) {
    echo "<tr>\n";
    echo "  <td align=\"center\" colspan=\"7\">No documents found</td>\n";
    echo "</tr>\n";
  } else {
    echo "<h2 align=\"center\">Found $count matching documents</h2>\n";
    while($row = @mysql_fetch_array($res)) {
      echo "<tr>\n";
      echo "  <td><img src=\"pix/". get_extension($row[name]) .".gif\" height=\"16\" width=\"16\" alt=\"[". strtoupper(get_extension($row[name])) ."]\"></td>\n";
      echo "  <td><a href=\"detail.php?doc_id=$row[id]&query=$query\">$row[name]</a></td>\n";
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
      echo "  <td>$row[created]</td>\n";
      printf("  <td%s</td>\n", ($row[modified] == NULL) ? " align=\"center\">-" : ">$row[modified]" );
      printf("  <td align=\"center\"><img src=\"pix/%s.gif\" height=\"15\" width=\"15\" alt=\"[ Access: %s ]\"></td>\n", ($row[level] == NULL) ? "G" : $row[level], access_string( ($row[level] == NULL) ? "G" : $row[level] ) );
      echo "</tr>\n";
    }
  }

  table_end();

  print_footer()

?>
