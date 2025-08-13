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
	require_once(realpath("conf.php")); 
	require_once(realpath("$admindir/config.php")); 

?>
<html>
<head>
<title> <?php echo $siteName; ?> Newsletter </title>
<link rel="stylesheet" type="text/css" href="templates/<?php echo $template; ?>/styles/style.css">
</head>
<body onLoad="document.frmEmail.strEmail.focus()" bgcolor="#FFFFFF">
	<table border="0" width="90%" align="center">
		<tr>
			<td>
				<span class="Text1">
					<br>Please enter your email address in the text box below to subscribe to our newsletter.
					<form onSubmit="if(strEmail.value == '') {alert('You forgot to enter your email address!'); strEmail.focus(); return false; }" name="frmEmail" action="newsletter.php" method="post">
						<input type="hidden" name="isPopup" value="true">
						Your Email Address:<br>
						<input type="text" name="strEmail" size="20" maxlength="250">
						<input type="submit" value="Subscribe"><br>
						<a href="privacy.php" target="_blank">Read our privacy policy</a>
					</form>
				</span>
			</td>
		</tr>
	</table>
</body>
</html>