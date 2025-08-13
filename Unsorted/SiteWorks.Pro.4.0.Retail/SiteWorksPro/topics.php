<?php include_once("templates/top.php"); ?>

<?php

	// This page displays the headers for each article whose
	// aTopicIds field contains the selected topic
	
	$topicId = @$_GET["topicId"];
	$page = @$_GET["page"];
	
	if(is_numeric($topicId))
	{
		$tResult = mysql_query("select tName from tbl_Topics where pk_tId = '$topicId'");
		if($tRow = mysql_fetch_row($tResult))
		{
			// Topic was found
			$topicName = $tRow[0];
		}
		else
		{
			// Topic doesn't exist
			?>
				<!-- Start Topics -->
				<div align="center">
					<center>
					<table width="96%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" colspan="2" class="BodyHeader2">
								<span class="BodyHeading">
									<br>Invalid Topic Requested
								</span>
								<span class="Text1">
									<br><br>
									The topic ID that you have selected is invalid. Please use
									the link below to return to our home page.
									<br><br>
								</span>
								<a href="index.php">Return Home</a>
							</td>
						</tr>
					</table>
					</center>
				</div>
				<!-- End Topics -->
			<?php
			
			include_once("templates/bottom.php");
			die();
		}

		if(!is_numeric($page) || $page < 1)
			$page = 1;

		if($page == 1)
			$start = 0;
		else
			$start = ($page * $articlesPerPage) - $articlesPerPage;

		// Get the number of records in the table
		$numRows = mysql_num_rows(mysql_query("select pk_aId from tbl_Articles where aTopicIds = '$topicId' or aTopicIds like '$topicId,%' or aTopicIds like '%,$topicId,%' or aTopicIds like '%,$topicId'"));
		$aResult = mysql_query("select pk_aId from tbl_Articles where aTopicIds = '$topicId' or aTopicIds like '$topicId,%' or aTopicIds like '%,$topicId,%' or aTopicIds like '%,$topicId' order by pk_aId desc limit $start, $articlesPerPage");

		if(mysql_num_rows($aResult) > 0)
		{
			// Show the page header
			?>
				<!-- Start Topics -->
				<div align="center">
					<center>
					<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
						<tr>
							<td width="100%" colspan="2" class="BodyHeader2">
								<span class="BodyHeading">
									<br><?php echo $topicName . " Articles"; ?>
								</span>
							</td>
						</tr>
						<tr>
							<td width="100%" height="20" colspan="2" align="right" class="BodyText" valign="top">
							<?php

								if($page > 1)
								  $nav .= "<a href='topics.php?topicId=$topicId&page=" . ($page-1) . "'><u>« Prev</u></a> | ";

								for($i = 1; $i <= ceil($numRows / $articlesPerPage); $i++)
								  if($i == $page)
								    $nav .= "<a href='topics.php?topicId=$topicId&page=$i'><b>$i</b></a> | ";
								  else
								    $nav .= "<a href='topics.php?topicId=$topicId&page=$i'>$i</a> | ";

								if(($start+$articlesPerPage) < $numRows && $numRows > 0)
								  $nav .= "<a href='topics.php?topicId=$topicId&page=" . ($page+1) . "'><u>Next »</u></a>";

								if(substr(strrev($nav), 0, 2) == " |")
								  $nav = substr($nav, 0, strlen($nav)-2);

								echo $nav . "<br>&nbsp;";
							?>
							</td>
						</tr>
					</table>
					</center>
				</div>
				<!-- End Topics -->
			<?php
			
			while($aRow = mysql_fetch_array($aResult))
			{
				DisplayArticleHeader($aRow["pk_aId"], true);
			}
			?>
				<div align="center"><center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tr>
						<td width="100%" height="20" colspan="2" align="right" valign="top">
							<?php echo $nav . "<br>&nbsp;"; ?>
						<br>&nbsp;
						</td>
					</tr>
				</table>
				</center></div>
			<?php
		}
		else
		{
			// No articles found
			?>
			<!-- Start Topics -->
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" class="BodyHeader2">
							<span class="BodyHeading">
								<br>No Articles Found
							</span>
							<span class="Text1">
								<br><br>
								No articles were found in the database for the selected topic. Please use
								the link below to return to our home page.
								<br><br>
							</span>
							<a href="index.php">Return Home</a>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<!-- End Topics -->
			<?php
		}
	}
	else
	{
		// Invalid topic specified
		?>
			<!-- Start Topics -->
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" class="BodyHeader2">
							<span class="BodyHeading">
								<br>Invalid Topic Requested
							</span>
							<span class="Text1">
								<br><br>
								The topic ID that you have selected is invalid. Please use
								the link below to return to our home page.
								<br><br>
							</span>
							<a href="index.php">Return Home</a>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<!-- End Topics -->
		<?php
	}
?>

<?php include_once("templates/bottom.php"); ?>