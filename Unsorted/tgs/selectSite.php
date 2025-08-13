<?php
session_start();
ob_start();
include("includes/config.php");
include("includes/header.php");
include("includes/messages.php");
include("includes/validate_member.php");

if(!session_is_registered("siteCode"))
{
	session_register("siteCode");
}
?>
<script language="javascript">
function submitFrm(str)
{
}
</script>
<center><b>Sites</b></center><br>
<center>Select a site to see statistics.</center><br>
<?php
	if($msg != "")
	{
		print "<center><span class=error>$msg</span></center><br>";
	}
?>
<table width="400" border="1" bordercolor="#92adcc" cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td width="400">
	  <div class="text" align="center">Site Name</div>
	</td>
  </tr>
  <?php
	$limit=5; // rows to return
	if(empty($offset))
	{
		$offset=0;
	}
	$st = "Select * from StatSite where userid = $sessionSiteId";
	$rs = mysql_query($st) or die(mysql_error());
	$numrows = mysql_num_rows($rs);
	$st = "Select * from StatSite where userid = $sessionSiteId limit $offset,$limit";
	$rs = mysql_query($st) or die(mysql_error());
	if($numrows > 0)
	{
		print "<form name=frm method=post action='sites.php'>";
		print "<input type=hidden name=doaction value='delete'>";
		while($row = mysql_fetch_array($rs))
		{
			print "<tr>";
			print "<td width='400'><div align='center'><div class=text><a href='totalVisitors.php?site=".$row['id']."'>".$row['sitename']."</a></div></td></tr>";
		}
		print "</form>";
	}
	else
	{
		print "<tr><td colspan=6><div align='center'><span class=error>$M_NoRecordFound</span></div></td></tr>";
	}
  ?>
</table>
<?php
	// Printing table for displaying paging at center
	print "<br><table border=\"0\" width='500' align='center'>";
	print "<tr><td align='center'>\n";
	// If the number of rows are more then limit then only show the prev and next link
	if($numrows>$limit)
	{

		if ($offset!=0 && $offset>=$limit)
		{
			// bypass PREV link if offset is 0
			$prevoffset=$offset-$limit;
			print "<a href=\"$PHP_SELF?offset=$prevoffset\"><font face='arial' size='2'>Prev</font></a> &nbsp; \n";
		}
		else
		{
			// don't give link to PREV if the page number is 1
			print "<font face='arial' size='2'>Prev </font>&nbsp; \n";
		}

		// calculate number of pages needing links
		$pages=intval($numrows/$limit);

		// $pages now contains int of pages needed unless there is a remainder from division
		if ($numrows%$limit)
		{
			// has remainder so add one page
			$pages++;
		}

		// This loop is to show the page number
		for ($i=1;$i<=$pages;$i++)
		{
			// loop through
			$newoffset=$limit*($i-1);
			if($newoffset==$offset)
			{
				print "$i &nbsp; \n";
			}
			else
			{
				print "<a href=\"$PHP_SELF?offset=$newoffset\">$i</a> &nbsp; \n";
			}
		}

		// check to see if last page
		if (!(($offset/$limit)==$pages-1) && $pages!=1)
		{
			// not last page so give NEXT link
			$newoffset=$offset+$limit;
			print "<a href=\"$PHP_SELF?offset=$newoffset\"><font face='arial' size='2'>Next</font></a><p>\n";
		}
		else
		{
			// Do not give link to NEXT if no more records are there
			print "<font face='arial' size='2'>Next</font><p>\n";
		}
	}
	print "</td></tr>";
	print "\n";
?>
<form name="frm" method="post" action="referralUrl.php">

</form>
<?php
include("includes/footer.php");
?>
