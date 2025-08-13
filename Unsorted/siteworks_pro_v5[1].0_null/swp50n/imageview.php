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

	$what = @$_GET["what"];
	$authorId = @$_GET["authorId"];
	$imageId = @$_GET["imageId"];
	$bookId = @$_GET["bookId"];

	switch($what)
	{
		case "getAuthorPic":
			ShowAuthorPic($authorId);
			break;
		case "getBookPic":
			ShowBookPic($bookId);
			break;
		default:
			ShowImage($imageId);	
	}
	
	function ShowAuthorPic($AuthorId)
	{
		// Grabs the authors pic from the database and displays it
		$dbVars = new dbVars();
		@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
		if($svrConn)
		{
			$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
						
			if($dbConn)
			{
				$iResult = mysql_query("select alPic from tbl_AdminLogins where pk_alId = '$AuthorId'");

				if($iRow = mysql_fetch_array($iResult))
				{
					header("Content-type: image/gif");
					
					if(strlen($iRow["alPic"]) > 0)
					{
						echo $iRow["alPic"];
					}
				}
			}
		}
	}
	
	function ShowImage($ImageId)
	{
		$dbVars = new dbVars();
		@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
		if($svrConn)
		{
			$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
						
			if($dbConn)
			{
				$iResult = mysql_query("select iType, iBlob from tbl_Images where pk_iId = '$ImageId'");

				if($iRow = mysql_fetch_array($iResult))
				{
					header("Content-type: {$iRow["iType"]}");
					echo $iRow["iBlob"];
				}
			}
		}
	}
	
	function ShowBookPic($BookId)
	{
		$dbVars = new dbVars();
		@$svrConn = mysql_connect($dbVars->strServer, $dbVars->strUser, $dbVars->strPass);
				
		if($svrConn)
		{
			$dbConn = @mysql_select_db($dbVars->strDb, $svrConn);
						
			if($dbConn)
			{
				$iResult = mysql_query("select bPic from tbl_Books where pk_bId = '$BookId'");

				if($iRow = mysql_fetch_array($iResult))
				{
					header("Content-type: image/gif");
					echo $iRow["bPic"];
				}
			}
		}
	}
?>