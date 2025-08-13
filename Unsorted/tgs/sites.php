<?php
session_start();
ob_start();
include("includes/config.php");
include("includes/header.php");
include("includes/messages.php");
include("includes/validate_member.php");
?>
<script language="javascript">
function selectAll()
{
	with(document.frm)
	{
		var len = length;
		for(var i=0; i<len; i++)
		{
			if(elements[i].type == "checkbox")
			{
				elements[i].checked = true;
			}
		}
	}
}
function clearAll()
{
	with(document.frm)
	{
		var len = length;
		for(var i=0; i<len; i++)
		{
			if(elements[i].type == "checkbox")
			{
				elements[i].checked = false;
			}
		}
	}
}
function deleteAll()
{
	with(document.frm)
	{
		var len = length;
		var flag = true;
		for(var i=0; i<len; i++)
		{
			if(elements[i].type == "checkbox")
			{
				if(elements[i].checked == true)
				{
					flag = false;
					break;
				}
			}
		}
		if(flag == true)
		{
			alert("Please select a record to delete");
		}
		else
		{
			if(confirm("Are you sure you want to delete record(s)?"))
			{
				submit();
			}
		}
	}
}
function editAll()
{
	with(document.frm)
	{
		var len = length;
		var count = 0;
		for(var i=0; i<len; i++)
		{
			if(elements[i].type == "checkbox")
			{
				if(elements[i].checked == true)
				{
					var val = elements[i].value;
					count = count + 1;
				}
			}
		}
		if(count == 0)
		{
			alert("Please select a record to edit");
		}
		else
		{
			if(count == 1)
			{
				action = "changeSiteInfo.php?act=edit&site="+val;
				submit();
			}
			if(count > 1)
			{
				alert("Slect only one record to edit");
			}
		}
	}
}

</script>
<?php
if($doaction == "delete")
{
	foreach($id as $key)
	{
		$st = "delete from StatSite where id = $key";
		$rs = mysql_query($st) or die(mysql_error());
		$msg = $M_RecordsDeleted;
	}
}
?>
<center><b>Sites</b></center><br>
<?php
	if($msg != "")
	{
		print "<center><span class=error>$msg</span></center><br>";
	}
?>
<table width="420" border="1" bordercolor="#92adcc" cellspacing="1" cellpadding="2" align="center">
  <tr>
	<td width="50">
	  <div align="center">&nbsp;</div>
	</td>
	<td width="370">
	  <div align="center">Site Name</div>
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
			print "<tr><td width='50'><div align='center'><input type='checkbox' name=id[] value=".$row['id']."></div></td>";
			print "<td width='100'><div align='center'>".$row['sitename']."</div></td></tr>";
		}
		print "<tr><td  colspan=6><div align='center'><a href='javascript:selectAll()'>Select All</a>&nbsp;|&nbsp;<a href='javascript:clearAll()'>Clear All</a>&nbsp;|&nbsp;<a href='javascript:deleteAll()'>Delete</a>&nbsp;|&nbsp;<a href='changeSiteInfo.php'>Add</a>&nbsp;|&nbsp;<a href='javascript:editAll()'>Edit</a></div></td></tr>";
		print "</form>";
	}
	else
	{
		print "<tr><td colspan=6><div align='center'><span class=error>$M_NoRecordFound</span></div></td></tr>";
		print "<tr><td  colspan=6><div align='center'><a href='changeSiteInfo.php'>Add</a></div></td></tr>";
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
<?php
include("includes/footer.php");
?>
