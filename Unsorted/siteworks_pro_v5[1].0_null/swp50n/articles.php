<?php include_once(realpath("templates/top.php")); ?>

<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	// This page displays a page of an article as well as
	// a drop down list with each page for that article

	$articleId = @$_GET["articleId"];
	$page = @$_GET["page"];
	
	if(!is_numeric($page))
		$page = 1;
	
	if(is_numeric($articleId))
	{
		$aResult = mysql_query("select apTitle, apContent from tbl_ArticlePages where apArticleId = '$articleId' order by pk_apId asc limit " . ($page-1) . ", 1");
		
		if($aRow = mysql_fetch_array($aResult))
		{
			// Display the details of this article
			DisplayArticleHeader($articleId, false, BIG_BLACK_TITLE);
			
			echo "<hr width='96%'>";

			// Show the ratings bar
			ShowRatingBar($articleId);
			
			// Update the numer of times that this article has been shown
			if($page == 1)
				UpdateViews($articleId);

			echo "<div align='right'><br><a href=\"javascript:OpenWin('printpage.php?articleId=$articleId', 'prnArt', '650', '550', 'yes')\"><img border='0' src='images/printarticle.gif'></a>&nbsp;&nbsp;</div>";
			echo "<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			echo "<tr>";
			echo "  <td>";
			echo "    <span class='BodyHeading4'>{$aRow["apTitle"]}</span>";
			echo "    <br><br><span class='Text1'>{$aRow["apContent"]}</span>";
			echo "  </td>";
			echo "</tr>";
			echo "</table>";
			echo "<hr width='96%'>";

			// Show the next/previous links
			ShowNav($articleId, $page);

			//Show the article Comments
			ShowComments($articleId,"article");
			echo "<br><br>";
		}
		else
		{
		// Page not found
		?>
			<!-- Start Article -->
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
							<span class="BodyHeading">
								<br>Invalid Article Page Requested
							</span>
							<span class="Text1">
								<br><br>
								The page number that have selected is invalid. Please use
								the link below to return to this article.
								<br><br>
							</span>
							<a href="articles.php?articleId=<?php echo $articleId; ?>">Return to Article</a>
						</td>
					</tr>
				</table>
				</center>
			</div>
			<!-- End Article -->
		<?php
		}
	}
	else
	{
	// Invalid article specified
	?>
		<!-- Start Article -->
		<div align="center">
			<center>
			<table width="96%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
						<span class="BigBlackBold">
							<br>Invalid Article Requested
						</span>
						<span class="BlackSmall">
							<br><br>
							The article ID that you have selected is invalid. Please use
							the link below to return to our home page.
							<br><br>
						</span>
						<a href="index.php">Return Home</a>
					</td>
				</tr>
			</table>
			</center>
		</div>
		<!-- End Article -->
	<?php
	}
?>

<?php include_once(realpath("templates/bottom.php")); ?>
