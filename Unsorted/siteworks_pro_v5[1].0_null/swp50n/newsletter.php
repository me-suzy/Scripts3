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
	/*
		This function will add a new user to the email database.
		If the user already exists, they will be informed. If not, they will
		be added to the database.	
	*/
	
	ob_start();

	$strEmail = @$_POST["strEmail"];
	$isPopup = @$_POST["isPopup"];
	$what = @$_POST["submit"];
	
	if(!$isPopup == "true")
	{
		include(realpath("templates/top.php"));
		?>
			<div align="center">
				<center>
				<table width="96%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="100%" colspan="2" class="BodyHeader2">
							<br>
		<?php
	}
	else
		{
			include(realpath("includes/php/functions.php"));
			?>
				<html>
				<head>
				<title> Our Newsletter </title>
				<link rel="stylesheet" type="text/css" href="styles/style.css">
				</head>

				<body bgcolor="#FFFFFF">
				<table width="90%" align="center" border="0">
				<tr>
				<td>
				<span class="blackMedium">
			
			<?php
		}

	if($strEmail)
		{
			if(1 == 1)
				{
					if(strstr($strEmail, ".") && strstr($strEmail, "@"))
						{
							$strQuery = "select count(*) from tbl_Newsletter where nlEmail = '" . $strEmail . "'";
							$nResult = mysql_query($strQuery);
							$nRow = mysql_fetch_row($nResult);
							
							if($nRow[0] == 0 || $what == 0)
								{
									if($what == 1)
									{
										$strQuery = "insert into tbl_Newsletter(nlEmail) values('" . $strEmail . "')";
										mysql_query($strQuery);
										?>
											<p style="margin-right:10">
											<?php if($isPopup == "true") { echo "<br>"; } ?>
											<span class="BodyHeading">Signup Successful!</span><br><br>
											<span class="Text1">Thanks for signing up for our newsletter.
										<?php if(!$isPopup == "true") { ?>
									
										 Please click on the link below to return to our home page.<br><br>
											<div align="right">
												<p style="margin-right:10">
												<a href="index.php">Return home</a>
											</div>
									
										<?php } else { ?>
									
										 Please click on the link below to close this window.<br><br>
											<div align="right">
												<p style="margin-right:10">
												<a href="javascript:window.close();">Close Window</a>
											</div>
									
										<?php }
									}
									else
									{
										$strQuery = "delete from tbl_Newsletter where nlEMail = '$strEmail'";
										mysql_query($strQuery);
										?>
											<p style="margin-right:10">
											<?php if($isPopup == "true") { echo "<br>"; } ?>
											<span class="BodyHeading">Email Address Removed!</span><br><br>
											<span class="Text1">You have successfully removed yourself from our mailing list.
										<?php if(!$isPopup == "true") { ?>
									
										 Please click on the link below to return to our home page.<br><br>
											<div align="right">
												<p style="margin-right:10">
												<a href="index.php">Return home</a>
											</div>
									
										<?php } else { ?>
									
										 Please click on the link below to close this window.<br><br>
											<div align="right">
												<p style="margin-right:10">
												<a href="javascript:window.close();">Close Window</a>
											</div>
									
										<?php }
									}
								}
							else
								{
									?>
										<p style="margin-right:10">
										<?php if($isPopup == "true") { echo "<br>"; } ?>
										<span class="BodyHeading">Already Registered!</span><br><br>
										<span class="Text1">The email address that you have entered is already
										registered in our database. 
									
									<?php if(!$isPopup == "true") { ?>
									
									Please click on the link below to return to our home page.<br><br>
										<div align="right">
											<p style="margin-right:10">
											<a href="index.php">Return home</a>
										</div>
									
									<?php } else { ?>
									
									
									Please click on the link below to try again.<br><br>
										<div align="right">
											<p style="margin-right:10">
											<a href="popupnl.php">Try Again</a>
										</div>
									<?php } ?>
									<?php
								}
						}
					else
						{
							// The user entered an invalid email address
							?>
								<p style="margin-right:10">
								<span class="BOdyHeading">Invalid Email!</span><br><br>
								<span class="Text1">Unfortunately the email address that you have entered
								is invalid. Please enter a valid email address in the box below and click on the re-submit button.<br><br>
									
								<form name="frmEmail" action="newsletter.php" method="post">
							<?php
							
								if($isPopup == "true")
									echo "<input type='hidden' name='isPopup' value='true'>";
							?>
									<p style="margin-right:10">
									Email Address: <input type="text" name="strEmail" maxlength="250" size="10"><input type="submit" value="Re-Submit">
								</form><br><br>
							<?php
						}
				}
			else
				{
					//header("location: index.php");
				}
		}
	else
		{
			//header("location: index.php");
		}
		
	if(!$isPopup == "true")
	{
	?>
						</td>
					</tr>
				</table>
				</center>
			</div>
	<?php
		include(realpath("templates/bottom.php"));
	}
	else
	{
	?>
				</td>
			</tr>
		</table>
	<?php
	
	}

	ob_end_flush();

?>