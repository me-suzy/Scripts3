<?
include ("admin/db.php");
$sql = "SELECT * FROM cat_news order by id desc";

$result = mysql_query($sql);
$num_res = mysql_num_rows($result);

for ($i=0; $i<$num_res; $i++)
{
              $myrow = mysql_fetch_array($result);
              $c_id = $myrow["id"];
              $c_name = $myrow["catname"];
         
              
    		  $sql_art = "SELECT * FROM article_news where catid = $c_id AND validated = 1 AND status = 1";
			  $result_art = mysql_query($sql_art);
			  $num_art = mysql_num_rows($result_art);

              print " <a href='cat.php?catid=$c_id&catname=$c_name'>$c_name</a> (<small>$num_art</small>)<br>";
}
?>
