<? if (!$skip) { include "header.php"; } ?>

<?
include ("admin/set_inc.php");
include ("admin/language/$lang");

$find_str = HTMLSpecialChars($find_str);

// If searchstring is given...SEARCH
if ($find_str)
{
	 
	print "<span class='articlebody'><b><h3>$la_search_result</h3></b></a><br><p></span>";

	print "<table width='100%' class='articlebody'>";
	require("admin/db.php");
	$sql = "SELECT * FROM article_news where title like '%$find_str%' or intro like '%$find_str%' or body like '%$find_str%'";
	$result = mysql_query ($sql);
	$records = mysql_num_rows($result);
	
	print "$la_search_found1 <b>$records</b> $la_search_found2 <b>$find_str</b>:<p>";
	
	

	while ($row = mysql_fetch_array($result)) 
	{
		$title_s = $row["title"];
		$id_s = $row["id"];
		$intro_s = $row["intro"];
		$body_s = $row["body"];
		$count = $row["count"];
		$NR = $NR + 1;
	
		if ($skip)
		{
			$fname = "index.php";	
		}
		else 
		{
			$fname = "art.php";	
		}
		
		print "<tr><td align=top>$NR.</td>";
    	print "<td align=top><a href='$fname?artid=$id_s'><b>$title_s</b></a></td>";
  		print "</tr><tr><td align=top></td><td align=top>$intro_s<p></td></tr>";

	}
	print("</table>");
}
// If no searchstring given...do show form
else
{
	print "<span class='articlebody'><p>$la_search_header";
	print "<form method='post' action='$PHP_SELF'>";
	print "<input type='hidden' name='side' value='let'>";
	print "<table border=0 cellspacing=1 width=100% height=100>";
	print "<input type='text' name='find_str' size='20'>";
	print "<input type=\"submit\" name=\"submit\" value=\"$la_search_now\">";
	print "</span>";
	print "</table></form>";
}
?>




<? if (!$skip) { include "footer.php"; } ?>