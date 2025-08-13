<!-- ## CATCOL.PHP START ## -->

<!-- Catcol.php: open 1 -->
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1" alt='' /></td>
  </tr>
</table>
<!-- Catcol.php: close 1 -->


<!-- Catcol.php: open 1 -->
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%"> 

	<?
	$catname= HTMLSpecialChars($catname);
    print("<span class=\"title\">$catname</span><p />");

if ($kid==0) { $where_clause = "where catfatherid = 0";}
else { $where_clause = "where catfatherid=$kid";  }


print "<!-- START CATCOL COLUMS -->";
function column_y()
{
	
 print("\n\n\n<!-- Column table start -->\n\n<table border=\"0\" width=\"100%\">\n\n<tr>\n");
 global $cat_tbl;
 global $where_clause;
 global $categories_per_column;
 global $file;
 global $ccatid;
 global $cantall;
 global $frontpage;

 $sql = "select * from $cat_tbl $where_clause order by catname";
 $result = mysql_query ("$sql");
 $catcounter =  mysql_num_rows($result);

 for ($i=0; $i<$catcounter; $i++)
 {
 	
  print "<td valign=\"top\" align=\"left\">";
  $row = mysql_fetch_array($result);
  $catid = $row["catid"];
  $catfatherid = $row["catfatherid"];
  $catname = $row["catname"];
  $catdescription = $row["catdescription"];
  $total = $row["total"];
  $catimage = $row["catimage"];
  $catno = $catno + 1;
  $filename = "templates/cat.html";
  $fd = fopen ($filename, "r");
  $file= fread ($fd, filesize ($filename));
  $file = str_replace("{IMAGE}", "<img src=\"catimages/$catimage\" border=\"0\" alt='' />", $file);
  $file = str_replace("{CATEGORYNAME}", "$catname", $file);
  $file = str_replace("{TOTAL_ADS}", "$total", $file);
  $catname = urlencode($catname);
  if ($frontpage == '2') 
  { 
  	$file = str_replace("{URL}", "<a href=\"add_ad_cat.php?kid=$catid&amp;catname=$catname\">", $file);
  	//$file = str_replace("{URL}", "<a href=\"add_ad.php?catid=$catid&amp;catfatherid=$catfatherid\">", $file); 
  }
  else  { $file = str_replace("{URL}", "<a href=\"index.php?kid=$catid&amp;catname=$catname\">", $file); }
  $file = str_replace("{/URL}", "</a>", $file);
  if (!$frontpage) { $file = str_replace("{CATDESCRIPTION}", "$catdescription", $file); }
  else { $file = str_replace("{CATDESCRIPTION}", " ", $file); }
  print($file);




  print "</td>\n";

  if ($catno == $categories_per_column)
  {
   	print("</tr>\n\n\n<tr>\n");
   	$catno = 0;
  }
 }

		print("<td></td>\r</tr>\r</table>\n\n\n<!-- Column tables end -->\n\n");
		
}
// End of column type y

// Start column type x
function column_x()
{

 
 print("\n\n\n<!-- Column table start -->\n\n<table border=\"0\" width=\"100%\"><tr><td valign=\"top\">");
 global $cat_tbl;
 global $where_clause;
 global $categories_per_column;
 global $file;
 global $ccatid;
 global $cantall;
 global $frontpage;

 $sql = "select * from $cat_tbl $where_clause order by catname";

 $result = mysql_query ("$sql");
 $catcounter =  mysql_num_rows($result);

 for ($i=0; $i<$catcounter; $i++)
 {
  $row = mysql_fetch_array($result);
  $catid = $row["catid"];
  $catfatherid = $row["catfatherid"];
  $catname = $row["catname"];
  $catdescription = $row["catdescription"];
  $total = $row["total"];
  $allowads = $row["allowads"];
  $catimage = $row["catimage"];
  $catno = $catno + 1;
  $filename = "templates/cat.html";
  $fd = fopen ($filename, "r");
  $file= fread ($fd, filesize ($filename));
  $file = str_replace("{CATDESCRIPTION}", "{CATDESCRIPTION}<br />", $file);
  $file = str_replace("{IMAGE}", "<img src=\"catimages/$catimage\" border=\"0\" alt='' />", $file);
  $file = str_replace("{CATEGORYNAME}", "$catname", $file);
  $file = str_replace("{TOTAL_ADS}", "$total", $file);

  $catname = urlencode($catname);
  if ($frontpage == '2') 
  { 
  	$file = str_replace("{URL}", "<a href=\"add_ad_cat.php?kid=$catid&amp;catname=$catname\">", $file);
  	//$file = str_replace("{URL}", "<a href=\"add_ad.php?catid=$catid&amp;catfatherid=$catfatherid\">", $file); 
  }
  else  { $file = str_replace("{URL}", "<a href=\"index.php?kid=$catid&amp;catname=$catname\">", $file); }
  $file = str_replace("{/URL}", "</a>", $file);
  if (!$frontpage) { $file = str_replace("{CATDESCRIPTION}", "$catdescription", $file); }
  else { $file = str_replace("{CATDESCRIPTION}", " ", $file); }
  print($file);


  if ($catno == $categories_per_column)
  {
   print("</td>\n\n\n<td valign=\"top\">\n");
   $catno = 0;
  }

  }
  print("</td></tr></table>\n\n\n<!-- Column tables end -->\n\n");
  

}
// End column type x


if ($xy == 'y')
{
 column_y();
}
elseif ($xy == 'x')
{
 column_x();
}
else
{
 print "<b>Error:</b><br />Type of category layout is not set !";
}
print "<!-- END CATCOL COLUMS -->";

// New structure, show add ad button
$sql = "select * from $cat_tbl where catid=$kid";
$result_allow = mysql_query ($sql);
if ($result_allow)
{
$row = mysql_fetch_array($result_allow);
}
$allowads = $row["allowads"];

if ($allowads AND $frontpage==2)
{
	
	?>    
	<p />
	<table border="0" cellspacing="5" cellpadding="5" height="23">
  	<tr>
    <td height="23" width="176" valign="middle" align="center" nowrap="nowrap" background="images/bg_cat.gif"><a href="add_ad.php?catid=<? echo $kid; ?>"> <b><? echo $la_add_adcat ?></b> </a></td> 
  	</tr>
	</table>

	<?
}
print "</td></tr></table>";
?>
<!-- Catcol.php: close 1 -->


<!-- ## CATCOL.PHP END ## -->