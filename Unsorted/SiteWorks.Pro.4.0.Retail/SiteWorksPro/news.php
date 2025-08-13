<?php include_once("templates/top.php"); ?>

<?php

	$page = @$_GET["page"];
	$start = @$_GET["start"];
	
	if(!is_numeric($page) || $page < 1)
		$page = 1;

	if($page == 1)
		$start = 0;
	else
		$start = ($page * $newsPerPage) - $newsPerPage;
		
	$numRows = mysql_num_rows(mysql_query("select pk_dnId from tbl_News"));
?>

	<!-- Start News -->
	<div align="center">
		<center>
		<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="100%" colspan="2" class="BodyHeader2">
					<span class="BodyHeading">
						<br><?php echo $siteName . " News"; ?>
					</span>
				</td>
			</tr>
			<tr>
				<td width="100%" height="20" colspan="2" align="right" valign="top">
				<?php

					if($page > 1)
					  $nav .= "<a href='news.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

					for($i = 1; $i <= ceil($numRows / $newsPerPage); $i++)
					  if($i == $page)
					    $nav .= "<a href='news.php?page=$i'><b>$i</b></a> | ";
					  else
					    $nav .= "<a href='news.php?page=$i'>$i</a> | ";
																  
					if(($start+$newsPerPage) < $numRows && $numRows > 0)
					  $nav .= "<a href='news.php?page=" . ($page+1) . "'><u>Next »</u></a>";
																
					if(substr(strrev($nav), 0, 2) == " |")
					  $nav = substr($nav, 0, strlen($nav)-2);
																  
					echo $nav . "<br>&nbsp;";
				?>
				</td>
			</tr>
			<tr>
				<td width="100%" height="20" colspan="2" valign="top">
				<?php
				
					// Get the news posts from the database and list them
					$nResult = mysql_query("select nAuthorId, nTitle, nContent, nDateAdded, nSource, nURL from tbl_News order by pk_dnId desc limit $start, $newsPerPage");
					
					while($nRow = mysql_fetch_array($nResult))
					{
					?>
						<a href="<?php echo $nRow["nURL"]; ?>" target="_blank"><span class="BodyHeading1"><?php echo $nRow["nTitle"]; ?></span></a><br>
						<span class="BodyHeading2">Posted: </span><span class="BodyHeading3"><?php echo MakeDate($nRow["nDateAdded"]); ?></span><br>
						<span class="BodyHeading2">Author: </span><span class="BodyHeading3"><?php GetAuthorName($nRow["nAuthorId"]); ?></span><br>
						<span class="BodyHeading2">Source: </span><span class="BodyHeading3"><?php echo $nRow["nSource"]; ?></span><br>
						
						<br>
						<span class="Text1">
							<?php echo $nRow["nContent"]; ?>
						</span>
						<br><br>
						<div align="right">
							<img src="images/gd.gif">
							<a href="<?php echo $nRow["nURL"]; ?>" target="_blank"><span class="Link1">Read Full News Item</span></a>
							<hr>
						</div>
					<?php
					}
				?>
				</td>
			</tr>
			<tr>
				<td width="100%" height="20" colspan="2" align="right" valign="top">
					<?php echo $nav . "<br>&nbsp;"; ?>
				<br>&nbsp;
				</td>
			</tr>
		</table>
		</center>
	</div>
	<!-- End News -->
	
<?php include_once("templates/bottom.php"); ?>