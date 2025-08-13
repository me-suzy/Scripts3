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
    require_once(realpath("config.php"));
    require_once(realpath("includes/php/funcs.php"));
    require_once(realpath("includes/php/variables.php"));

    $dbVars = new dbVars();
    @$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
    $dbConn = mysql_select_db($dbVars->strDb, $svrConn);

	$helpId = $_GET["helpId"];
	?>
		<html>
		<head>
			<title> :: Admin Help :: </title>
			<link rel="stylesheet" type="text/css" href="styles/style.css">
		</head>
		<body bgcolor="#C6E7C6">
	
	<?php
	if(is_numeric($helpId))
	{
		// See if we can retrieve the help details from the tbl_AdminHelp table
		$result = mysql_query("select hTitle, hAnswer from tbl_AdminHelp where pk_hId = $helpId");
		
		if($row = mysql_fetch_array($result))
		{
			// The help item exists, let's show it
			echo "<span class='BodyHeading'>" . $row["hTitle"] . "</span><br><br>";
			echo "<span class='BodyText'>" . $row["hAnswer"] . "<br><br></span>";
			echo "<a href='javascript:window.close()'>Close Window</a><br><br>";
		}
		else
		{
			// Help item not found
			echo "<span class='BodyHeading'>Invalid Help Topic</span><br><br>";
			echo "<span class='BodyText'>The selected help topic was not found in the database<br><br></span>";
			echo "<a href='javascript:window.close()'>Close Window</a><br><br>";
		}
	}
?>

</body>
</html>