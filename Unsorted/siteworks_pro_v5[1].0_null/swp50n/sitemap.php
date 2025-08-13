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
include_once(realpath("templates/top.php")); 
?>

        <!-- Start Sitemap -->
        <div align="center">
                <center>
                <table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                                <td width="100%" colspan="2" class="BodyHeader2">
                                        <span class="BodyHeading">
                                                <br><?php echo $siteName; ?> Sitemap
                                        </span>
                                </td>
                        </tr>
                        <tr>
                                <td width="100%" height="20" colspan="2" class="BodyText" valign="top">
									<br><span class="Text3">General:</span>
									<ul>
										<li><a href="index.php"><?php echo $siteName;?> home</a></li>
										<li><a href="about.php">About</a></li>
										<li><a href="authors.php">Authors</a></li>
										<li><a href="news.php">News</a></li>
										<li><a href="books.php">Books</a></li>
										<li><a href="rss.php">XML Feed</a></li>
										<li><a href="sitemap.php">Sitemap</a></li>
										<li><a href="privacy.php">Privacy</a></li>
										<li><a href="contact.php">Contact Us</a></li>
									</ul>
									<span class="Text3">Articles:</span>
									<ul>
									<?php
									
										$aResult = mysql_query("select pk_tId, tName from tbl_Topics order by tName asc");
										
										while($aRow = mysql_fetch_row($aResult))
										{
										?>
											<li><a href="topics.php?topicId=<?php echo $aRow[0]; ?>"><?php echo $aRow[1]; ?></a></li>
										<?php
										}
									?>
									</ul>
									<span class="Text3">News:</span>
									<ul>
									<?php
									
										$aResult = mysql_query("select pk_dnId, nTitle from tbl_News order by nTitle asc");
										
										while($aRow = mysql_fetch_row($aResult))
										{
										?>
											<li><a href="news.php#<?php echo $aRow[0]; ?>"><?php echo $aRow[1]; ?></a></li>
										<?php
										}
									?>
									</ul>
                                </td>
                        </tr>
                </table>
                </center>
        </div>
        <br><br>
        <!-- End Sitemap -->

<?php include_once(realpath("templates/bottom.php")); ?>