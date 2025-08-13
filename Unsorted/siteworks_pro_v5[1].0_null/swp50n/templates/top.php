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
	include(realpath("conf.php"));

	error_reporting(E_WARNING);
	require_once(realpath("$admindir/config.php"));

	if(!$siteClosed)
		{

			if($template == "")
				$template = "template1";
				
			// Simply include the required template file
			include(realpath("templates/$template/top.php"));

		}
	else
		{

			?>

				<div align="center">

					<br><br>

					<table border="0" width="600">

						<tr>

							<td align="center"><img border="0" src="<?php echo $siteLogo; ?>"></td>

						</tr>

						<tr>

							<td>
								<span style="font-family: verdana; font-size: 10pt;">
									<?php echo nl2br(str_replace("<<email>>", "<a href=\"mailto:$adminEmail\">$adminEmail</a>", $siteCloseMessage)); ?>
								</span>
							</td>

						</tr>

					</table>

				</div>			

			<?php
			exit;

		}
	
?>
