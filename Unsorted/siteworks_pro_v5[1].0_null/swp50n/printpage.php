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
	require_once(realpath("$admindir/includes/php/variables.php")); 
	require_once(realpath("includes/php/functions.php")); 

?>

<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title><?php echo $siteName . " :: " . $siteURL; ?></title>
<link rel="stylesheet" type="text/css" href="templates/<?php echo $template; ?>/styles/style.css">
</head>
<body class="PrintBody">

<?php

	$articleId = @$_GET["articleId"];
	
	if(is_numeric($articleId))
	{
		ShowPrintArticle($articleId);
	}
	else
	{
	?>
		<span class="BodyHeading">
			Invalid Article Requested
		</span>
		<span class="Text1">
			<br><br>
			The article ID that you have selected is invalid. Please use
			the link below to close this window.
			<br><br>
		</span>
		<a href="javascript:window.close()">Close Window</a>
	<?php
	}

?>

</body>
</html>
