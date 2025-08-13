<?
  require('lib/config.inc');
  require('lib/auth.inc');
  require('lib/classes.inc');
  require('lib/functions.inc');

  global $cfg;

  $tmp = explode("/", $REQUEST_URI);
  $doc_id = $tmp[sizeof($tmp)-2];

  @mysql_connect($cfg[server], $cfg[user], $cfg[pass]) or die("Unable to connect to MySQL server");
  @mysql_select_db($cfg[db]) or die("Unable to select $cfg[db] database");

  $user = new user($login);

  if( may_read($user->id,$doc_id) ) {
    $res = @mysql_query("SELECT d.id AS id,d.name AS name,d.type AS type,d.size AS size,c.content AS content FROM documents AS d LEFT JOIN documents_content AS c ON d.id=c.id WHERE d.id=$doc_id");
    $row = @mysql_fetch_array($res);
    Header("Content-Type: $row[type]");
    echo "". base64_decode($row[content]) ."";
    exit;
  }

  print_header("Permission Denied");
  echo "<h2 align=\"center\">Permission denied</h2>\n";
  print_footer();
  
 
?>
