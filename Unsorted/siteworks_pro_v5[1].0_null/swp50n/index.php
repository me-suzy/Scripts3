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

	<!-- Start Main Content -->
	<div align="center">
		<center>
		<table width="96%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100%" colspan="2" class="BodyHeader2">
					<br><span class="BodyHeading">Recent Articles</span><br>&nbsp;
				</td>
			</tr>
			<?php ShowRecentArticles(10); ?>
		</table>
		</center>
	</div>
	<!-- End Main Content -->

<?php include_once(realpath("templates/bottom.php")); ?>
