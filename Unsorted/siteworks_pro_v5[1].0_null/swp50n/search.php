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
	$query = @$_GET["query"];
	$articles = @$_GET["srchArticles"] != "" ? true : false;
	$news = @$_GET["srchNews"] != "" ? true : false;
	$extended = @$_GET["extdSrch"] != "" ? true : false;
	$page = @$_GET["page"];
	
	if(!is_numeric($page))
		$page = 1;
	
	if($query != "")
	{
		// Filter the search expression to remove useless characters, etc
		$query = trim(strip_tags($query));
		
		if(strlen($query) >= 3)
		{
		?>
			<div align="center">
			    <center>
			    <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
			        <tr>
			            <td width="100%" colspan="2" class="BodyHeader2">
			                    <span class="BodyHeading">
			                            <br><?php echo "Search Results For \"" . stripslashes($query) . "\""; ?>
			                    </span>
			            </td>
			        </tr>
			        <tr>
			            <td width="100%" height="20" colspan="2" class="BodyText" valign="top">
							<span class="Text1"><br>
								<?php ShowSearchResults($query, $page, $articles, $news, $extended); ?>
							</span>
			            </td>
			        </tr>
			    </table>
			    </center>
			</div>
			<?php
		}
		else
		{
		?>
			<div align="center">
			    <center>
			    <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
			        <tr>
			            <td width="100%" colspan="2" class="BodyHeader2">
			                    <span class="BodyHeading">
			                            <br>Invalid Query "<?php echo $query; ?>"
			                    </span>
			            </td>
			        </tr>
			        <tr>
			            <td width="100%" height="20" colspan="2" class="BodyText" valign="top">
							<span class="Text1"><br>
								The search query that you entered is invalid. It must be at least
								three characters in length. Please use the link below to search
								again.
								<br><br>
								<a href="search.php">Search Again >></a>
							</span>
			            </td>
			        </tr>
			    </table>
			    </center>
			</div>
		<?php
		}
	}
	else
	{
	?>
		<div align="center">
		    <center>
		    <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
		        <tr>
		            <td width="100%" colspan="2" class="BodyHeader2">
		                <span class="BodyHeading">
		                        <br><?php echo "Search " . $siteName; ?>
		                </span>
		            </td>
		        </tr>
		        <form name="frmSearch" action="search.php" method="get">
		        <tr>
		            <td width="100%" height="20" colspan="2" class="BodyText" valign="top">
						<span class="Text1">
							To search our site please enter your search query in the text box
							shown below and then click on the "Go!" button:
							<br><br>
							<input type="text" name="query" value="" size="25" maxlength="25"> <input type="submit" value="Go!"><br>
							<input type="checkbox" name="srchArticles" value="1" checked> Search Articles<br>
							<input type="checkbox" name="srchNews" value="1" checked> Search News<br>
							<input type="checkbox" name="extdSrch" value="1"> Search Entire Article (Slower)<br>
						</span>
		            </td>
		        </tr>
		        </form>
		    </table>
		    </center>
		</div>
	<?php
	}
	?>

<?php include_once(realpath("templates/bottom.php")); ?>