<? include("header.php"); ?>
<? require("admin/db.php"); ?>
<? require("func.php"); ?>
<? include ("admin/set_inc.php"); ?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" valign="top">
<tr><td valign="top">
<table valign="top" border=0 width="100%">
<?
$sql = "select id from article_news where validated=1 AND status=1 AND frontpage=1 order by topnews desc limit $fp_limit";

$result = mysql_query($sql);
$num_res = mysql_num_rows($result);
for ($i=0; $i<$num_res; $i++)
{
      $colcount++;
      $myrow = mysql_fetch_array($result);
      $id = $myrow["id"];


      if ($colcount==1)
      {
       print "<tr>";
      }

      if (!$done)
      {
		print "<td colspan = '2' valign=\"top\" width=\"100%\">";
      	query('',$id,'');
      	print "</td></tr>";	
      	$done = 1;
      	$colcount = $colcount -1 ;
      }
      else 
      {
      	print "<td valign=\"top\" width=\"49%\">";
      	query('',$id,'');
      	print "</td>";
      
      	if ($colcount==2)
      	{
	    	print "</tr>";
      	}
      
      	if ($colcount==2)
      	{
        	$colcount=0;
      	}
      }
}
?>
</table>
<!-- Slutt hovedinnhold -->
<!-- Høyre meny -->
 <td width="100" valign="top">
	    <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td>
				<?
					require("latest_best.php");
				?>

			</td>
       </tr>
       </table>
	</td>
</tr>
</table>
<? include("footer.php"); ?>