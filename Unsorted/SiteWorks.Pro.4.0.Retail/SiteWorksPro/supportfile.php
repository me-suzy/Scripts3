<?php

	// This page will grab the support file for the article
	// whose pk_aId field is $articleId
	
	require_once("includes/php/functions.php");
	
	$articleId = @$_GET["articleId"];
	
	if(!is_numeric($articleId))
	{
	?>
		<?php include_once("templates/top.php"); ?>
		<div align="center">
			<center>
			<table width="96%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
						<span class="BigBlackBold">
							<br>Invalid Article
						</span>
						<span class="BlackSmall">
							<br><br>
							The article ID that you have selected is invalid. Please use
							the link below to return to the article.
							<br><br>
						</span>
						<a href="javascript:history.go(-1)">Return to Article</a>
					</td>
				</tr>
			</table>
			</center>
		</div>
		<?php include_once("templates/bottom.php"); ?>
	<?php
	}
	else
	{
		// Grab the support file for this article and send
		// it to the client using standard HTTP headers
		
		$sResult = mysql_query("select aTitle, aSupportFile from tbl_Articles where pk_aId = $articleId");

		if($sRow = mysql_fetch_row($sResult))
		{
				$articleTitle = str_replace(" ", "_", trim($sRow[0]));
				$zipId = $sRow[1];
				$asResult = mysql_query("select zBlob from tbl_TempZips where pk_zId = $zipId");
				
				if($asRow = mysql_fetch_row($asResult))
				{
					header("Content-type: application/x-zip-compressed");
					header("Content-disposition: filename=$articleTitle.zip");
					echo $asRow[0];
				}
				else
				{
					// The zip file doesn't exist
					?>
					<?php include_once("templates/top.php"); ?>
					<div align="center">
						<center>
						<table width="96%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
									<span class="BigBlackBold">
										<br>File Not Found
									</span>
									<span class="BlackSmall">
										<br><br>
										This article doesn't contain a support file. Please use
										the link below to return to the article.
										<br><br>
									</span>
									<a href="javascript:history.go(-1)">Return to Article</a>
								</td>
							</tr>
						</table>
						</center>
					</div>
					<?php include_once("templates/bottom.php"); ?>
					<?php
				}
		}
		else
		{
			// There is no support file for this article
			?>
				<?php include_once("templates/top.php"); ?>
				<div align="center">
					<center>
					<table width="96%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100%" colspan="2" bgcolor="#FFFFFF" height="21">
								<span class="BigBlackBold">
									<br>File Not Found
								</span>
								<span class="BlackSmall">
									<br><br>
									This article doesn't contain a support file. Please use
									the link below to return to the article.
									<br><br>
								</span>
								<a href="javascript:history.go(-1)">Return to Article</a>
							</td>
						</tr>
					</table>
					</center>
				</div>
				<?php include_once("templates/bottom.php"); ?>
			<?php
		}
	}
?>