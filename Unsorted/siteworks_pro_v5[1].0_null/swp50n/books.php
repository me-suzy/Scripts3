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

	<!-- Start Books -->
	<div align="center">
		<center>
		<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center">
			<tr>
				<td width="100%" colspan="2" class="BodyHeader2">
					<span class="BodyHeading">
						<br><?php echo $siteName . " Recommended Books"; ?><br><br>
					</span>
				</td>
			</tr>
			<tr>
				<td width="100%" height="20" colspan="2" valign="top">
				<?php
				
					$tResult = mysql_query("select pk_tId, tName from tbl_Topics order by tName");
					
					while($tRow = mysql_fetch_array($tResult))
					{
						$bResult = mysql_query("select bTitle, bURL from tbl_Books where bTopicIds = '{$tRow["pk_tId"]}' or bTopicIds like '{$tRow["pk_tId"]},%' or bTopicIds like '%, {$tRow["pk_tId"]},%' or bTopicIds like '%, {$tRow["pk_tId"]}' order by bTitle asc");

						if(mysql_num_rows($bResult) > 0)
						{
							echo "<img src='images/gd.gif'><span class='BodyHeading4'> " . $tRow["tName"] . "</span><br>";
							while($bRow = mysql_fetch_array($bResult))
							{
								echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src='images/join.gif'>&nbsp;&nbsp;<a href='" . $bRow["bURL"] . "' target='_blank'><span class='Link2'>" . $bRow["bTitle"] . "</span></a><br>";
							}
							echo "<br>";
						}
					}
				?>
				</td>
			</tr>
		</table>
		</center>
	</div>
	<!-- End Books -->

<?php include_once(realpath("templates/bottom.php")); ?>