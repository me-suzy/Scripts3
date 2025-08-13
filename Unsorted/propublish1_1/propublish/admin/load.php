<?
include "db.php";
$id = htmlspecialchars($id);

if ($id)
{
	$sql = "SELECT body FROM article_news WHERE id = $id";
	$result = mysql_query($sql);
	$num_res = mysql_num_rows($result);

	for ($i=0; $i<$num_res; $i++)
	{
              $myrow = mysql_fetch_array($result);
              $body = $myrow["body"];
              
	}
}
$body = nl2br($body);
$body = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"", $body); 	
print $body;

?>
