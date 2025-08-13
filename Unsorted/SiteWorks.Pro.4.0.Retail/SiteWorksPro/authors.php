<?php include_once("templates/top.php"); ?>
<?php

	$page = @$_GET["page"];
	$start = @$_GET["start"];
	
	if(!is_numeric($page) || $page < 1)
		$page = 1;

	if($page == 1)
		$start = 0;
	else
		$start = ($page * $authorsPerPage) - $authorsPerPage;

	$aResult = mysql_query("select pk_alId, alEmail, alFName, alLName, alBio, alDateJoined, alSecLevel from tbl_AdminLogins order by alFName, alLName limit $start, $authorsPerPage");
	$numRows = mysql_num_rows(mysql_query("select pk_alId from tbl_AdminLogins order by alFName, alLName"));

	if($numRows > 0)
	{
	?>
			<!-- Start Authors -->
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" class="BodyHeader2">
							<span class="BodyHeading">
								<br><?php echo $siteName; ?> Authors
							</span>
					</tr>
					<tr>
						<td width="100%" height="20" colspan="2" align="right" valign="top">
						<?php

							if($page > 1)
							  $nav .= "<a href='authors.php?page=" . ($page-1) . "'><u>« Prev</u></a> | ";

							for($i = 1; $i <= ceil($numRows / $authorsPerPage); $i++)
							  if($i == $page)
							    $nav .= "<a href='authors.php?page=$i'><b>$i</b></a> | ";
							  else
							    $nav .= "<a href='authors.php?page=$i'>$i</a> | ";
															  
							if(($start+$authorsPerPage) < $numRows && $numRows > 0)
							  $nav .= "<a href='authors.php?page=" . ($page+1) . "'><u>Next »</u></a>";
															
							if(substr(strrev($nav), 0, 2) == " |")
							  $nav = substr($nav, 0, strlen($nav)-2);
															  
							echo $nav . "<br>&nbsp;";
						?>
						</td>
					</tr>
				</table>
				<?php while($aRow = mysql_fetch_array($aResult)) { ?>
			    <div align="center"><center>
			    <table width="96%" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="3%">
						<img src="imageview.php?what=getAuthorPic&authorId=<?php echo $aRow["pk_alId"]; ?>">&nbsp;
					</td>
					<td width="97%" valign="top">
						<a href="mailto:<?php echo $aRow["alEmail"]; ?>"><span class="BodyHeading1"><?php echo $aRow["alFName"] . " " . $aRow["alLName"]; ?></span></a><br>
						<span class="BodyHeading2">Status:</span><span class="BodyHeading3"> 
						<?php
						
							if($aRow["alSecLevel"] == 2)
								echo "Publisher";
							else
								echo "Site Administrator";
						
						?>
						</span><br>
						<span class="BodyHeading2">Email:</span><span class="BodyHeading3"> <a href="mailto:<?php echo $aRow["alEmail"]; ?>"><?php echo $aRow["alEmail"]; ?></a><br></span>
						<span class="BodyHeading2">Date Joined:</span><span class="BodyHeading3"> <?php echo MakeDate($aRow["alDateJoined"]); ?><br></span>
						<span class="BodyHeading2">Articles Published:</span><span class="BodyHeading3">
						<?php
						
							$naResult = mysql_query("select count(pk_aId) from tbl_Articles where aAuthorId = '{$aRow["pk_alId"]}' and aActive = 1 and aStatus = 1");
							$naRow = mysql_fetch_row($naResult);
							echo $naRow[0];
						
						?>
						<br></span>
					</td>
					</tr>
					<tr>
					<td width="100%" colspan="2">
					<span class="Text1">
						<br><?php echo $aRow["alBio"]; ?>
						<br>&nbsp;<hr>
					</td>
					</tr>
			    </table>
			    </center></div>
			<?php } ?>
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" height="20" colspan="2" align="right" valign="top">
							<?php echo $nav . "<br>&nbsp;"; ?>
						<br>&nbsp;
						</td>
					</tr>
				</table>
			</center>
		</div>
		<!-- End Authors -->
	<?php	
	}
	else
	{
		// No authors found in the database
		?>
			<!-- StartAuthors -->
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
							<span class="BodyHeading">
								<br>No Authors Found
							</span>
							<span class="Text1">
								<br><br>
								No authors were found in the database. Please use
								the link below to return to our home page.
								<br><br>
							</span>
							<a href="index.php">Return Home</a>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<!-- End Authors -->
		<?php
	}
?>

<?php include_once("templates/bottom.php"); ?>