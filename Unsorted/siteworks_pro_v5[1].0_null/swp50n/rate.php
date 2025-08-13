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
	// This page accepts a visotors rating for a particular article
	// and then redirects them back to that article. If the user
	// has already voted, then his vote isn't counted
	
	//error_reporting(0);
	
	$articleId = @$_GET["articleId"];
	
	if($articleId == "")
		$articleId = @$_POST["articleId"];
	
	$rating = @$_GET["rating"];
	
	if($rating == "")
		$rating = @$_POST["rating"];

	// Do we have a valid vote?
	if(!is_numeric($rating) || $rating % 2 != 0 || $rating < 2 || $rating > 10)
	{
		// Bad rating
		?>
			<?php include_once(realpath("templates/top.php")); ?>
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
							<span class="BigBlackBold">
								<br>Invalid Rating
							</span>
							<span class="BlackSmall">
								<br><br>
								The article rating that you have selected is invalid. Please use
								the link below to return to the article.
								<br><br>
							</span>
							<a href="javascript:history.go(-1)">Return to Article</a>
						</td>
					</tr>
				</table>
				</center>
			</div>
		<?php
	}
	else
	{
		// We have a valid article ID and rating, has the
		// user already voted?
		require_once(realpath("includes/php/functions.php"));
			
		$visitorIP = @$_SERVER["REMOTE_HOST"];
			
		if($visitorIP == "")
			$visitorIP = @$_SERVER["REMOTE_ADDR"];
			
		$cookieVote = @$_COOKIE["rating_$articleId"] == "" ? false : true;
		$ipVote = mysql_num_rows(mysql_query("select pk_riId from tbl_RatingIps where riIP = '$visitorIP' and riArticleId = '$articleId'")) == 0 ? false : true;
		
		if($cookieVote || $ipVote)
		{
			// The user has already voted, send them back to the article
			header("Location: articles.php?articleId=$articleId");
		}
		else
		{
			// This user hasn't rated, let's add their rating
			mysql_query("insert into tbl_RatingIps values(0, '$visitorIP', $articleId)");
			mysql_query("update tbl_Articles set aRatingTotal = aRatingTotal + $rating, aNumRatings = aNumRatings + 1 where pk_aId = $articleId");
				
			// It *would* be good to use header here, but we have to flush
			// the page, so we use a meta tag with refresh
			?>
				<html>
				<head>
					<meta http-equiv="refresh" content="0; url=articles.php?articleId=<?php echo $articleId; ?>">
				</head>
				</html>
			<?php
			die();
		}
	}
?>

<?php include_once(realpath("templates/bottom.php")); ?>