<?php

	/*
		Name: functions.php
		Desc: Various functions that are used throughout the site.
			  They do everything from displaying articles to news,
			  tallying ratings, etc, etc, etc.
	*/
	
	define("MEDIUM_BLUE_TITLE", 0);
	define("BIG_BLACK_TITLE", 1);
	define("SR_ARTICLE", 0);
	define("SR_NEWS", 1);
	define("SESSION_LENGTH", 20);
	
	require_once("admin/config.php");
	require_once("admin/includes/php/variables.php");
	
	// Has the user executed the setup script?
	if($isSetup == 'no')
		header("Location: admin/setup.php");

	// Make a connection to the database first off
	$dbVars = new dbVars();
	$svrConn = @mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);

	if($svrConn)
	{
		$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
			
		if(!$dbConn)
		{
			die("A connection to the database couldn't be established");
		}
	}
	else
	{
		die("A connection to the database couldn't be established");
	}
	
	function ShowTopics($ShowArrows = true)
	{
		// Grab the topics from the database and list them
		$tResult = mysql_query("select * from tbl_Topics order by tName asc");
		while($tRow = mysql_fetch_array($tResult))
		{
		?>
			<?php if($ShowArrows == true) { ?>
				<img border="0" height="9" src="images/gd.gif" width="9">
			<?php } else { ?>
				<img border="0" height="5" src="blank.gif" width="1">
			<?php } ?>
			<a href="topics.php?topicId=<?php echo $tRow["pk_tId"]; ?>">
				<span class="Link1"><?php echo $tRow["tName"]; ?></span><br>
			</a>
		<?php
		}
		echo "<br>";
	}
	
	function ShowHandyTip()
	{
		// Grab the handy tip from the database and list it
		$hResult = mysql_query("select nValue from tbl_Personal where nType = 2");
		?>
			<span class="Text1">
				<br>
					<?php if($hRow = mysql_fetch_array($hResult)) { echo $hRow["nValue"]; } ?>
				<br>&nbsp;
			</span>		
		<?php
	}
	
	function Show2Cents()
	{
		// Grab the 2 cents from the database and list it
		$tResult = mysql_query("select nValue from tbl_Personal where nType = 1");
		?>
			<span class="Text1">
				<br>
					<?php if($tRow = mysql_fetch_array($tResult)) { echo $tRow["nValue"]; } ?>
				<br>&nbsp;
			</span>
		<?php
	}
	
	function ShowAffiliates($Extended = true)
	{
		// Display the affiliates for this site
		global $siteName;
		global $adminEmail;
		?>
			<span class="Text1">
				<br><?php echo $siteName; ?> is proud to be affiliated with the following quality web sites:<br><br>
				<?php
				
					$aResult = mysql_query("select * from tbl_Affiliates order by aName asc");
					while($aRow = mysql_fetch_array($aResult))
					{
						if($Extended == true)
						{
						?>
							<img src="images/gd.gif"> <a href="<?php echo $aRow["aURL"]; ?>" target="_blank"><span class="Link2"><?php echo $aRow["aName"]; ?></span></a><br>
						<?php
						}
						else
						{
						?>
							<a href="<?php echo $aRow["aURL"]; ?>" target="_blank"><span class="Link4"><?php echo $aRow["aName"]; ?></span></a><br><img src="blank.gif" width="1" height="3"><br>
						<?php
						}
					}
					?>
				<br>
				<?php if($Extended == true) { ?>
					<a href="mailto:<?php echo $adminEmail; ?>?subject=Affiliate"><span class="Link1">Affiliate With Us &gt;&gt;</span></a>
				<?php } else { ?>
					<a href="mailto:<?php echo $adminEmail; ?>?subject=Affiliate"><span class="Link2">Affiliate With Us</span></a>
				<?php } ?>
				<br>&nbsp;
			</span>
		<?php
	}
	
	function MakeDate($Date)
	{
		return substr($Date, 4, 2) . "/" . substr($Date, 6, 2) . "/" . substr($Date, 0, 4);
	}
	
	function ShowRecentNews($NumPosts = 10, $Full = true)
	{
		// Grab the $NumPosts most recent news posts from the database and display
		// their title and description
		
		$nResult = mysql_query("select nTitle, nURL, nContent, nDateAdded from tbl_News order by pk_dnId desc limit $NumPosts");
		
		while($nRow = mysql_fetch_array($nResult))
		{
			if($Full == true)
			{
			?>
			    <br><img border=0 height=9 src="images/gd.gif" width=9>
			    <span class="Text3"><?php echo MakeDate($nRow["nDateAdded"]); ?></span>
			    <a href="<?php echo $nRow["nURL"]; ?>" target="_blank"><span class="Link3"><br><?php echo $nRow["nTitle"]; ?>
			    </a></span>
			    <span class="Text2">
					<br><br><?php echo $nRow["nContent"]; ?>
					<br><br>
				</span>
			<?php
			}
			else
			{
				// Only show the headings
			?>
				<a href="<?php echo $nRow["nURL"]; ?>" target="_blank"><span class="Link4"><?php echo $nRow["nTitle"]; ?></span></a><br><img src="blank.gif" width="1" height="3"><br>
			<?php
			}
		}
		
		if($Full == true)
		{
		?>
			<a href="news.php"><span class="Link3">See All News &gt;&gt;</a></span>
			<br>&nbsp;
		<?php
		}
		else
		{
			echo "<img src='blank.gif' width='1' height='10'>";
		}
	}
	
	function GetAuthorName($AuthorId)
	{
		// Get the name of the author with ID of $AuthorId
		$aResult = mysql_query("select alFName, alLName, alEmail from tbl_AdminLogins where pk_alId = '$AuthorId'");
		if($aRow = mysql_fetch_array($aResult))
		{
			echo "<a href='mailto:" . $aRow["alEmail"] . "'>" . $aRow["alFName"] . " " . $aRow["alLName"] . "</a>";
		}
		else
		{
			echo "Unknown";
		}
	}
	
	function GetAType($TypeId)
	{
		// Return the name type of article
		switch($TypeId)
			{
				case 1:
					return "Tutorial";
					break;
				case 2:
					return "Review";
					break;
				case 3:
					return "Summary";
					break;
				case 4:
					return "Tip";
					break;
				case 5:
					return "Interview";
					break;
				default:
					return "Unknown";
			}
	}
	
	function GetRating($ArticleId)
	{
		// Get the tally of votes for this article and shown them.
		// If there's less than three votes then dont show the tally.
		$vResult = mysql_query("select sum(aRatingTotal) as aTotal, aNumRatings from tbl_Articles where pk_aId = '$ArticleId' group by aNumRatings");
		if(mysql_num_rows($vResult) == 1)
		{
			$vRow = mysql_fetch_array($vResult);
			
			if($vRow["aNumRatings"] >= 3)
			{
				$avgRating = round($vRow["aTotal"] / $vRow["aNumRatings"], 1);
				
				for($i = 0; $i < floor($avgRating); $i++)
					echo "<img alt='Average visitor rating of $avgRating/10' border='0' height='10' src='images/ratingbaron.gif' width='10'>";
					
				// Is there a half rating?
				if($avgRating - floor($avgRating) > 0)
					echo "<img alt='Average visitor rating of $avgRating/10' border='0' height='10' src='images/ratingbarhalf.gif' width='10'>";

				if(floor($avgRating) < 9)
					for($i = floor($avgRating); $i < 9; $i++)
						echo "<img alt='Average visitor rating of $avgRating/10' border='0' height='10' src='images/ratingbaroff.gif' width='10'>";
				else
					for($i = floor($avgRating); $i < 10; $i++)
						echo "<img alt='Average visitor rating of $avgRating/10' border='0' height='10' src='images/ratingbaroff.gif' width='10'>";
					
			}
			else
			{
				echo "<span class='Text1'>[ Not Rated Yet ]</span>";
			}
		}
		else
		{
			echo "<span class='Grey'>[ Not Rated Yet ]</span>";
		}
	}
	
	function ShowRecentArticles($NumArticles = 10)
	{
		// Grab recent articles from the database and display them
		$aResult = mysql_query("select pk_aId, aTitle, aDocType, aAuthorId, aSummary, aDateCreated, aNumRatings/aNumVotes as aAvgRating from tbl_Articles where aActive = 1 and aStatus = 1 order by pk_aId desc limit $NumArticles");
		
		while($aRow = mysql_fetch_array($aResult))
		{
		?>
            <tr>
            <td width="3%">
				<img src="imageview.php?what=getAuthorPic&authorId=<?php echo $aRow["aAuthorId"]; ?>">&nbsp;
            </td>
            <td width="97%" valign="top">
				<a href="articles.php?articleId=<?php echo $aRow["pk_aId"]; ?>"><span class="BodyHeading1"><?php echo $aRow["aTitle"]; ?></a><br></span>
				<span class="BodyHeading2">Author:</span><span class="BodyHeading3"> <?php echo GetAuthorName($aRow["aAuthorId"]); ?><br></span>
				<span class="BodyHeading2">Added:</span><span class="BodyHeading3"> <?php echo MakeDate($aRow["aDateCreated"]); ?><br></span>
				<span class="BodyHeading2">Type:</span><span class="BodyHeading3"> <?php echo GetAType($aRow["aDocType"]); ?><br></span>
				<?php echo GetRating($aRow["pk_aId"]); ?>
            </td>
            </tr>
            <tr>
            <td width="100%" colspan="2">
            <span class="Text1">
				<br><?php echo $aRow["aSummary"]; ?><br><br>
				<div align="right">
					<img border="0" src="images/gd.gif">&nbsp;
					<a href="articles.php?articleId=<?php echo $aRow["pk_aId"]; ?>"><span class="Link1">Read Full Article</span></a>
					|
					<a href="javascript:OpenWin('printpage.php?articleId=<?php echo $aRow["pk_aId"]; ?>', 'prnArt', '650', '550', 'yes')"><span class="Link1">Print Article</span></a>
					<br>&nbsp;<hr>
				</div>
			</td>
			</tr>
		<?php
		}
	}
	
	function DisplayArticleHeader($ArticleId, $ShowSummary = false, $TitleType = MEDIUM_BLUE_TITLE)
	{
		// This function will display the header for the article
		// whose articleId field is $ArticleId
		
		$aResult = mysql_query("select pk_aId, aTitle,aSummary, aDocType, aAuthorId, aDateCreated, aNumViews from tbl_Articles where aActive = 1 and aStatus = 1 and pk_aId = '$ArticleId'");

		if(mysql_num_rows($aResult) == 1)
		{
			$aRow = mysql_fetch_array($aResult);
		?>
            <div align="center"><center>
            <table width="96%" cellspacing="0" cellpadding="0" border="0">
			<?php if($TitleType == BIG_BLACK_TITLE) { ?>
				<tr>
				<td width="100%" colspan="2">
					<span class="BodyHeading"><br><?php echo $aRow["aTitle"]; ?><br></span>
				</td>
				</tr>
				<tr>
				<td width="3%">
					<img src="imageview.php?what=getAuthorPic&authorId=<?php echo $aRow["aAuthorId"]; ?>">&nbsp;
				</td>
				<td width="97%" valign="top">
					<span class="BodyHeading2">Author:</span><span class="BodyHeading3"> <?php echo GetAuthorName($aRow["aAuthorId"]); ?><br></span>
					<span class="BodyHeading2">Added:</span><span class="BodyHeading3"> <?php echo MakeDate($aRow["aDateCreated"]); ?><br></span>
					<span class="BodyHeading2">Type:</span><span class="BodyHeading3"> <?php echo GetAType($aRow["aDocType"]); ?><br></span>
					<span class="BodyHeading2">Viewed:</span><span class="BodyHeading3"> <?php echo ($aRow["aNumViews"]); ?> time(s)<br></span>
					<?php echo GetRating($aRow["pk_aId"]); ?>
				</td>
				</tr>
			<?php } else { ?>
				<tr>
				<td width="3%">
					<img src="imageview.php?what=getAuthorPic&authorId=<?php echo $aRow["aAuthorId"]; ?>">&nbsp;
				</td>
				<td width="97%" valign="top">
					<a href="articles.php?articleId=<?php echo $aRow["pk_aId"]; ?>"><span class="BodyHeading1"><?php echo $aRow["aTitle"]; ?></a><br></span>
					<span class="BodyHeading2">Author:</span><span class="BodyHeading3"> <a href="mailto:<?php echo $auEmail; ?>"><?php echo GetAuthorName($aRow["aAuthorId"]); ?></a><br></span>
					<span class="BodyHeading2">Added:</span><span class="BodyHeading3"> <?php echo MakeDate($aRow["aDateCreated"]); ?><br></span>
					<span class="BodyHeading2">Type:</span><span class="BodyHeading3"> <?php echo GetAType($aRow["aDocType"]); ?><br></span>
					<?php echo GetRating($aRow["pk_aId"]); ?>
				</td>
				</tr>
			<?php } ?>
				<?php if($ShowSummary == true) { ?>
					<tr>
					<td width="100%" colspan="2">
					<span class="Text1">
						<br><?php echo $aRow["aSummary"]; ?><br><br>
						<div align="right">
							<img border="0" src="images/gd.gif">&nbsp;
							<a href="articles.php?articleId=<?php echo $aRow["pk_aId"]; ?>"><span class="Link1">Read Full Article</span></a>
							|
							<a href="javascript:OpenWin('printpage.php?articleId=<?php echo $aRow["pk_aId"]; ?>', 'prnArt', '650', '550', 'yes')"><span class="Link1">Print Article</span></a>
							<br>&nbsp;<hr>
						</div>
					</td>
					</tr>
				<?php } ?>
            </table>
            </center></div>
		<?php
		}
	}
	
	function ShowPrintArticle($ArticleId)
	{
		// This function will simply output all of the pages contained
		// within the article whose ID is $ArticleId
		
		global $siteName;
		global $siteURL;
		
		$pResult = mysql_query("select apTitle, apContent from tbl_ArticlePages where apArticleId = '$ArticleId' order by pk_apId asc");
		
		if(mysql_num_rows($pResult) == 0)
		{
			// This article doesn't exist, show an error message
			?>
				<span class="BodyHeading">
					Article Not Found
				</span>
				<span class="Text1">
					<br><br>
					The selected article was not found in the database. Please use the link
					below to close this window.
					<br><br>
				</span>
				<a href="javascript:window.close()">Close Window</a>
			<?php
		}
		else
		{
			// This article exists and has at least one page, display them
			echo "<span class='BodyHeading'>Print Article Content<br></span>";
			DisplayArticleHeader($ArticleId);
			echo "<br><a href='javascript:window.print()'><img border='0' src='images/printarticle.gif'></a>";
			echo "<span class='Text1'><br><br>This article is also available online at $siteName [$siteURL]</span>";
			echo "<hr>";
			
			// We will now display each page for the article
			while($pRow = mysql_fetch_array($pResult))
			{
				echo "<span class='BodyHeading'>{$pRow["apTitle"]}<br></span>";
				echo "<span class='Text1'>{$pRow["apContent"]}<br></span>";
				echo "<hr><br>";
			}
		}
	}
	
	function ShowNav($ArticleId, $Page)
	{
		// This function will grab the next/previous links for the selected article
		$nav = "";
		$nResult = mysql_query("select pk_apId from tbl_ArticlePages where apArticleId = '$ArticleId'");
		$numRows = mysql_num_rows($nResult);
		$i = 1;
		
		echo "<div align='right'>";
		echo "<span class='Text3'>Article Pages: &nbsp;</span>";
		echo "<span class='BlackSmall'>";
		
		if($Page > 1)
			$nav .= "<a href='articles.php?articleId=$ArticleId&page=" . ($Page-1) . "'><span class='BodyHeading1'>« Prev</span></a> | ";
		
		while($nRow = mysql_fetch_row($nResult))
		{
			if($i == $Page)
				$nav .= "<span class='Text3'>" . $i++ . "</span> | ";
			else
				$nav .= "<a href='articles.php?articleId=$ArticleId&page=" . $i . "'><span class='BodyHeading1'>" . $i++ . "</span></a> | ";
		}

		if($Page < $numRows)
			$nav .= "<a href='articles.php?articleId=$ArticleId&page=" . ($Page+1) . "'><span class='BodyHeading1'>Next »</span></a>";
		
		// Strip the trailing slash from the nav
		if(substr(strrev($nav), 1, 1) == "|")
			$nav = substr($nav, 0, strlen($nav)-3);
		
		echo "$nav</span>&nbsp;&nbsp;</div>";
		
		// Do we need to show the related articles, forum link, etc for this article?
		if($Page == $numRows)
		{
			$aResult = mysql_query("select aSupportFile, aForumLink, aLink1, aLink2, aLink3, aBookIds from tbl_Articles where pk_aId = '$ArticleId'");
			
			if($aRow = mysql_fetch_array($aResult))
			{
				echo "<hr width='96%'>";
				echo "<table width='96%' align='center' border='0' cellspacing='0' cellpadding='0'>";
				echo "<tr>";
				echo "  <td>";
				echo "    <span class='BodyHeading'><br>Support Material</span>";
				echo "    <br><br><span class='Text1'>";
				
				if($aRow["aSupportFile"] > 0)
				{
					echo "This article contains some support material in a .zip file format.<br>";
					echo "Please <a href='supportfile.php?articleId=$ArticleId'>click here</a> to download the file.<br><br>";
				}
				else
				{
					echo "There is no support material available for this article.<br><br>";
				}
				
				echo "<span class='BodyHeading'>Related Links</span>";
				
				if($aRow["aLink1"] != "" || $aRow["aLink2"] != "" || $aRow["aLink3"] != "")
				{
					echo "<ul>";
					
					if($aRow["aLink1"] != "")
						echo "<li><a target='_blank' href='{$aRow["aLink1"]}'>{$aRow["aLink1"]}</a></li>";

					if($aRow["aLink2"] != "")
						echo "<li><a target='_blank' href='{$aRow["aLink2"]}'>{$aRow["aLink2"]}</a></li>";

					if($aRow["aLink3"] != "")
						echo "<li><a target='_blank' href='{$aRow["aLink3"]}'>{$aRow["aLink3"]}</a></li>";
						
					echo "</ul>";
				}
				else
				{
					echo "<br><br>There are no related links for this article.<br><br>";
				}

				echo "<span class='BodyHeading'>Forum Link<br><br></span>";
				
				if($aRow["aForumLink"] != "")
				{
					echo "This article contains a link to a related forum thread.<br>";
					echo "Please <a href='{$aRow["aForumLink"]}'>click here</a> to view the thread.<br><br>";
				}
				else
				{
					echo "There is no forum link available for this article.<br><br>";
				}
				
				echo "<span class='BodyHeading'>Related Books<br><br></span>";
				
				if($aRow["aBookIds"] != "")
				{
					$bResult = mysql_query("select bTitle, bURL from tbl_Books where pk_bId in ({$aRow["aBookIds"]})");
					$numBooks = 0;
					
					if(mysql_num_rows($bResult) > 0)
						echo "<ul>";
					
					while($bRow = mysql_fetch_array($bResult))
					{
						echo "<li><a target='_blank' href='{$bRow["bURL"]}'>{$bRow["bTitle"]}</a></li>";
						$numBooks++;
					}

					if(mysql_num_rows($bResult) > 0)
						echo "</ul>";
						
					if($numBooks == 0)
						echo "There are no related books available for this article.<br><br>";
				}
				else
				{
					echo "There are no related books available for this article.<br><br>";
				}
				
				echo "    <br><br></span>";
				echo "  </td>";
				echo "</tr>";
				echo "</table>";
			}
		}
	}
	
	function ShowRatingBar($ArticleId)
	{
		// This function shows the rating bar which users can
		// use to rate an article on a scale of 1 to 10
		
		?>
			<div align="center"><center>
			<table width="96%" align="center" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<form name="frmRating" action="rate.php" method="get">
					<input type="hidden" name="articleId" value="<?php echo $ArticleId; ?>">
					<td width="100%" height="23" bgcolor="#EEEEEE">
						<div align="right">
							<span class="Text1">
								How would you rate this article:&nbsp;&nbsp;&nbsp;
								Bad <input type="radio" name="rating" value="2">
								<input type="radio" name="rating" value="4">
								<input type="radio" name="rating" value="6">
								<input type="radio" name="rating" value="8">
								<input type="radio" name="rating" value="10" checked> Good&nbsp;&nbsp;
							</span>
							<a href="javascript:document.frmRating.submit()"><span class="BodyHeading1">Go »</span></a>&nbsp;
						</div>
					</td>
					</form>
				</tr>
			</table>
			</center></div>
		<?php
	}
	
	function ShowFeaturedBook($ShowHeading = true)
	{
		// This function grabs one book from the tbl_Books table
		// and displays it
		
		global $showFeaturedBook;
		
		if($showFeaturedBook == true)
		{
			$bResult = mysql_query("select pk_bId, bTitle, bURL from tbl_Books order by rand() limit 1");
		
			if($bRow = mysql_fetch_array($bResult))
			{
				if($ShowHeading == true)
				{
			?>
				<tr>
					<td width="100%" class="SideHeader1">
						<span class="SideHeading">&nbsp;&nbsp;Featured Book</span>
					</td>
				</tr>
				
				<tr>
				  <td width="100%" valign="top" class="SideBody1">
					<p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
				<?php } ?>
						<div align="center">
							<br><a href="<?php echo $bRow["bURL"]; ?>" target="_blank"><img alt="<?php echo $bRow["bTitle"]; ?>" border="0" src="imageview.php?what=getBookPic&bookId=<?php echo $bRow["pk_bId"]; ?>"></a>
							<br><img src="blank.gif" width="1" height="5"><br>
							<a href="<?php echo $bRow["bURL"]; ?>" target="_blank"><span class="Link1"><?php echo $bRow["bTitle"]; ?></a></span>
							<br><img src="blank.gif" width="1" height="5"><br>
						</div>
				<?php if($ShowHeading == true) { ?>
					</p>
				  </td>
				</tr>
			<?php
				}
			}
		}
	}
	
	function UpdateViews($ArticleId)
	{
		// This function updates the number of times that an article
		// has been viewed
		
		@mysql_query("update tbl_Articles set aNumViews = aNumViews + 1 where pk_aId = $ArticleId");
	}
	
	function ShowRecentForumPosts($NumPosts = 10)
	{
		global $isVBulletinForum, $isPHPBBForum, $forumDBServer, $forumDBUser, $forumDBPass, $forumDBName, $forumPath;
		
		if($isVBulletinForum == true || $isPHPBBForum == true)
		{
			?>
			<tr>
				<td width="100%" class="SideHeader2">
					<span class="SideHeading">&nbsp;&nbsp;Forum Posts</span>
				</td>
			</tr>
			<?php
		
			$vbDB = @mysql_connect($forumDBServer, $forumDBUser, $forumDBPass) or die("<tr><td>Cant connect to forum server</td></tr>");
			$vbConn = @mysql_select_db($forumDBName, $vbDB) or die("<tr><td>Cant connect to forum database</td></tr>");
        
			if($isVBulletinForum == true)
				$vbQuery = @mysql_query("select threadId, title from thread order by threadId desc limit $NumPosts");
			else if($isPHPBBForum == true)
				$vbQuery = @mysql_query("select topic_id, topic_title from phpbb_topics order by topic_id desc limit $NumPosts");

			$fpc = 0;
			echo "<tr><td class='SideBody1'>";
			echo "<p style='margin-left:10; margin-right:10'>";
			echo "<img src='blank.gif' width='1' height='10'><br>";
        
			if($isVBulletinForum == true)
			{
				while($frow = @mysql_fetch_row($vbQuery))
				{
				?>
					<a href="<?php echo $forumPath; ?>/showthread.php?threadid=<?php echo $frow[0]; ?>"><span class="Link5"><?php echo $frow[1]; ?></span></a><br><img src="blank.gif" width="1" height="10"><br>
				<?php
				}
			}
			else if($isPHPBBForum == true)
			{
				while($frow = @mysql_fetch_row($vbQuery))
				{
				?>
					<a href="<?php echo $forumPath; ?>/viewtopic.php?t=<?php echo $frow[0]; ?>"><span class="Link5"><?php echo $frow[1]; ?></span></a><br><img src="blank.gif" width="1" height="10"><br>
				<?php
				}
			}
			echo "</td></tr>";
		
			// Reconnect back to the SiteWorks MySQL database
			$dbVars = new dbVars();
			$svrConn = @mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
			$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
		}
	}
	
	function ShowSearchResults($Query, $Page, $Articles, $News, $Extended)
	{
		// This function will query the articles and or news table to return search
		// results on a page-by-page basis.
		?>
			<form name="frmSearch" action="search.php" method="get">
				<span class="Text1">
					<input type="text" name="query" value="<?php echo stripslashes($Query); ?>" size="25" maxlength="25"> <input type="submit" value="Go!"><br>
					<input type="checkbox" name="srchArticles" value="1" <?php if($Articles == true) echo " CHECKED "; ?>> Search Articles<br>
					<input type="checkbox" name="srchNews" value="1" <?php if($News == true) echo " CHECKED "; ?>> Search News<br>
					<input type="checkbox" name="extdSrch" value="1" <?php if($Extended == true) echo " CHECKED "; ?>> Search Entire Article(Slower)<br>
				</span>
			</form>
		<?php
		
		// Setup the results associative array
		$saResult = array();
		$sapResult = array();
		$snResult = array();
		$results = array();
		
		$sCounter = 0;
		$spCounter = 0;
		$nCounter = 0;
		$aCounter = 0;
		
		// Now we need to query the articles table for a list of articles relating
		// to the search topic. If extended search is on, then we will also search
		// the pages of each article too.
		
		// Do we want to search the articles titles and summaries?
		if($Articles == true)
		{
			$aQuery = "select pk_aId from tbl_Articles where aTitle like '%$Query%' or aSummary like '%$Query%'";
			$aResult = mysql_query($aQuery);
		
			while($aRow = mysql_fetch_array($aResult))
			{
				$saResult[$sCounter++] = $aRow["pk_aId"];
			}
		}

		if($News == true)
		{
			$nQuery = "select pk_dnId from tbl_News where nTitle like '%$Query%' or nContent like '%$Query%'";
			$nResult = mysql_query($nQuery);
		
			while($nRow = mysql_fetch_array($nResult))
			{
				$snResult[$nCounter++] = $nRow["pk_dnId"];
			}
		}
		
		// Do we want to search all of the articles pages?
		if($Extended == true)
		{
			$eQuery  = "select pk_apId from tbl_ArticlePages where apTitle like '%$Query%' or apContent like '%$Query%'";
			$eResult = mysql_query($eQuery);
			
			while($eRow = mysql_fetch_array($eResult))
			{
				// We must make sure that this article isn't already in the $sResult array
				if(!in_array($eRow["pk_apid"], $sapResult))
					$sapResult[$spCounter++] = $eRow["pk_apId"];
			}
		}
		
		// We now have two arrays containing articles and news. We will combine them into
		// one array so that we can "page" through them
		for($i = 0; $i < sizeof($saResult); $i++)
		{
			$saQuery = "select pk_aId, aTitle, aSummary from tbl_Articles where pk_aId = " . $saResult[$i];
			if($row = mysql_fetch_array(mysql_query($saQuery)))
			{
				$results[$aCounter++] = array("URL" => "articles.php?articleId=" . $row["pk_aId"],
											  "Title" => $row["aTitle"],
											  "Summary" => $row["aSummary"] . "...",
											  "Type" => SR_ARTICLE);
			}
		}

		if($Extended == true && sizeof($sapResult) > 0)
		{
			// We will get the pages title, URL and summary into an array
			for($i = 0; $i < sizeof($sapResult); $i++)
			{
				$sapQuery = "select pk_apId, apPage, apArticleId, apTitle, left(apContent, 300) as apSummary from tbl_ArticlePages where pk_apId = " . $sapResult[$i];
				if($row = mysql_fetch_array(mysql_query($sapQuery)))
				{
					$results[$aCounter++] = array("URL" => "articles.php?articleId=" . $row["apArticleId"] . "&page=" . $row["apPage"],
												  "Title" => $row["apTitle"],
												  "Summary" => strip_tags($row["apSummary"]) . "...",
												  "Type" => SR_ARTICLE);
				}
			}
		}

		for($i = 0; $i < sizeof($snResult); $i++)
		{
			$snQuery = "select pk_dnId, nTitle, left(nContent, 300) as nSummary from tbl_News where pk_dnId = " . $snResult[$i];
			if($row = mysql_fetch_array(mysql_query($snQuery)))
			{
				$results[$aCounter++] = array("URL" => "news.php#" . $row["pk_dnId"],
											  "Title" => $row["nTitle"],
											  "Summary" => strip_tags($row["nSummary"]) . "...",
											  "Type" => SR_NEWS);
			}
		}
		
		$numResults = sizeof($results);

		?>
			<span class="BodyHeading">
				<?php echo sizeof($results); ?> Result<?php if($numResults != 1) echo "s"; ?> Found
			</span>
			<br><br>
		<?php
		
			// We will now loop through the array and display the results
			for($i = 0; $i < sizeof($results); $i++)
			{
			?>
				<?php echo $i+1; ?>. <a href="<?php echo $results[$i]["URL"]; ?>"><span class='BodyHeading1'><?php echo $results[$i]["Title"]; ?></span></a>
				<?php
					if($results[$i]["Type"] == SR_ARTICLE)
						echo " [Article]";
					else
						echo " [News Post]";
				?>
				<p style="margin-left:20">
					<?php echo $results[$i]["Summary"]; ?>
				</p>
			<?php
			}
		echo "<a href='search.php'>Search Again >></a><br><br>";
	}
	
	function ShowVotingPoll($ShowTitle = true)
	{
		// This function checks to see if there is a "visible"
		// voting poll in the tbl_Polls table. If there is then
		// it's displayed and its results are shown as well, etc.
		
		$pResult = mysql_query("select * from tbl_Polls where pVisible = 1 limit 1");
		
		if(mysql_num_rows($pResult) == 1)
		{
			// There is a poll that should be shown, do we show the poll or its
			// results?
			if($ShowTitle == true)
			{
			?>
				<tr>
					<td width="100%" colspan="2" class="SideHeader">
					        <span class="SideHeading">&nbsp;&nbsp;Voting Poll</span>
					</td>
				</tr>
			<?php
			}
			
			$pRow = mysql_fetch_array($pResult);
			$vTotal = 0;
			
			if(@$_COOKIE["poll_" . $pRow["pk_pId"]] != "" || @$_GET["poll_" . $pRow["pk_pId"]] != "")
			{
				// Show the results of the poll
				?>
					<tr>
						<td width="100%" colspan="2" valign="top" class="SideBody">
							<table width="90%" border="0" cellspacing="0" cellpadidng="0" align="center">
							<tr>
								<td width="100%" colspan="3">
									<img src="blank.gif" width="1" height="10"><br>
									<span class="Text3"><?php echo $pRow["pQuestion"]; ?></span><br>
									<img src="blank.gif" width="1" height="10"><br>
								</td>
							</tr>
							<?php
							
								// We will get each answer from the database as well as draw an image and
								// the percentage that answer has
								
								$pollAnswers = array();
								$pa = 0;
								
								for($i = 1; $i <= 10; $i++)
								{
									eval("
										if(\$pRow['pAnswer$i'] != '')
										{
											\$pollAnswers[$pa] = \$pa+1;
											++\$pa;
										}
									");
								}
								
								// Firstly, we work out the total number of votes for
								// this poll
								$tResult = mysql_query("select count(distinct(paVisitorIP)) from tbl_PollAnswers where paPollId = " . $pRow["pk_pId"]);
								$tRow = mysql_fetch_row($tResult);
								$vTotal = $tRow[0];
								
								// Now that we have the answers for this poll in an array,
								// we will loop through them and show them in the column
								for($i = 0; $i < sizeof($pollAnswers); $i++)
								{
									$vResult = mysql_query("select count(*) from tbl_PollAnswers where paPollId = " . $pRow["pk_pId"] . " and paAnswer = " . $pollAnswers[$i]);
									$vRow = mysql_fetch_row($vResult);
									$vNum = $vRow[0];
									
									if($vTotal > 0)
										$vPer = floor(($vNum / $vTotal) * 100);
									else
										$vPer = 0;
									?>
										<tr>
											<td width="100%" colspan="3">
												<span class="Text1"><?php echo $pRow["pAnswer" . ($i+1)]; ?> [<?php echo $vNum; ?>]</span>
											</td>
										</tr>
										<tr>
											<td width="80%" colspan="1">
												<img src="images/vote.gif" width="<?php echo $vPer; ?>%" height="10">
											</td>
											<td width="5%">&nbsp;</td>
											<td width="15%" colspan="1">
												<span class="Text1"><?php echo $vPer; ?>%</span>
											</td>
										</tr>
									<?php
								}
							?>
								<tr>
									<td width="100%" colspan="3">
										<span class="Text1">Total of <?php echo $vTotal; ?> vote(s)</span>
									</td>
								</tr>
							</table>&nbsp;
						</td>
					</tr>
				<?php
			}
			else
			{
				// Show the actual poll and accept the vote
				?>
					<form name="frmPoll" action="poll.php" method="post">
					<input type="hidden" name="pollId" value="<?php echo $pRow["pk_pId"]; ?>">
					<tr>
						<td width="100%" colspan="2" valign="top" class="SideBody">
							<table width="90%" border="0" cellspacing="0" cellpadidng="0" align="center">
								<tr>
									<td width="100%" colspan="2">
										<span class="Text3">
											<img src="blank.gif" width="1" height="10"><br><?php echo $pRow["pQuestion"]; ?>
											<br><img src="blank.gif" width="1" height="10">
										</span>
									</td>
								</tr>
								<?php
								
									for($i = 0; $i < 10; $i++)
									{
										if($pRow["pAnswer$i"] != "")
										{
										?>
											<tr>
												<td width="10%">
													<?php if($pRow["pType"] == 0) { ?>
														<input type="radio" name="vote" value="<?php echo $i; ?>">
													<?php } else { ?>
														<input type="checkbox" name="vote[]" value="<?php echo $i; ?>">
													<?php } ?>
												</td>
												<td width="90%">
													<span class="Text1">
														<?php echo $pRow["pAnswer$i"]; ?>
													</span>
												</td>
											</tr>
										<?php
										}
									}
								?>
									<tr>
										<td width="10%" colspan="2">
											<br><img src="blank.gif" width="1" height="10">
											<input type="submit" value="Vote >>">
											<br><img src="blank.gif" width="1" height="10">
										</td>
									</tr>
							</table>
						</td>
					</tr>
					</form>
				<?php
			}
		}
	}
	
	function CheckShowUsersOnline($DoTable = true)
	{
		global $showNumUsers;
		
		if($showNumUsers == true)
		{
			// Do we need to add this user to the tbl_UsersOnline table?
			$userIP = $_SERVER["HTTP_X_FORWARDED_FOR"];
			
			if($userIP == "")
				$userIP = $_SERVER["REMOTE_ADDR"];
			
			$timeMax = time() - (60 * SESSION_LENGTH);
			$result = mysql_query("select count(id) from tbl_UsersOnline where unix_timestamp(dateAdded) >= '$timeMax' and userIP = '$userIP'");
			$recordExists = mysql_result($result, 0, 0) > 0 ? true : false;

			if(!$recordExists)
			{
				// Add a record for this user
				mysql_query("insert into tbl_UsersOnline(userIP) values('$userIP')");
			}

			$timeMax = time() - (60 * SESSION_LENGTH);
			$result = mysql_query("select count(id) from tbl_UsersOnline where unix_timestamp(dateAdded) >= '$timeMax'");
			$usersOnline = mysql_result($result, 0, 0);
			
			if($DoTable == true)
			{
			?>
				<tr>
					<td width="100%" class="SideHeader1">
						<span class="SideHeading">&nbsp;&nbsp;Users Online</span>
					</td>
				</tr>
				<tr>
				  <td width="100%" valign="top" class="SideBody1">
					<p style="MARGIN-LEFT: 10px; MARGIN-RIGHT: 10px">
				  		<span class="Text2">
				  			<img src="blank.gif" width="1" height="10"><br>
				  			<?php echo "There " . ($usersOnline != 1 ? "are" : "is") . " currently $usersOnline user" . ($usersOnline != 1 ? "s" : "") . " online. "; ?>
				  			<br><img src="blank.gif" width="1" height="10"><br>
				  		</span>
					</p>
				  </td>
				</tr>
			<?php
			}
			else
			{
				// Just output the text
				echo "There " . ($usersOnline != 1 ? "are" : "is") . " $usersOnline user" . ($usersOnline != 1 ? "s" : "") . " online";
			}
			
			// We now want to delete all redundant records in the tbl_UsersOnline table
			// if there is currently no one online
			
			if($usersOnline == 1)
			{
				@mysql_query("delete from tbl_UsersOnline");
			}
			
		}
	}
?>