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
	require_once(realpath("includes/php/functions.php"));

	$strXML  = "<?xml version=\"1.0\" ?>" . chr(10);
	$strXML .= "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:fr=\"http://ASPRSS.com/fr.html\" xmlns:pa=\"http://ASPRSS.com/pa.html\" xmlns=\"http://purl.org/rss/1.0\">" . chr(10);
	$strXML .= "<channel rdf:about=\"$siteName\">" . chr(10);
	$strXML .= "  <title>$siteName</title>" . chr(10);
	$strXML .= "  <link>$siteURL</link>" . chr(10);
	$strXML .= "  <description>$siteDescription</description>" . chr(10);
	$strXML .= "  <dc:publisher>$siteName</dc:publisher>" . chr(10);
	$strXML .= "  <dc:creator>$adminName (mailto:$adminEmail)</dc:creator>" . chr(10);
	$strXML .= "  <image rdf:resource=\"$siteURL/$siteLogo\" />" . chr(10);
	$strXML .= "  <items>" . chr(10);
	$strXML .= "    <rdf:Seq>" . chr(10);
	
	$result = mysql_query("select pk_aId, aTitle, aSummary, aDateCreated, aAuthorId from tbl_Articles where aActive = 1 and aStatus = 1 order by pk_aId desc");
	
	while($row = mysql_fetch_array($result))
	{
		$strXML .= "    <rdf:li rdf:resource=\"$siteURL/articles.php?articleId=" . $row["pk_aId"] . "\" />" . chr(10);
	}
	
	$strXML .= "    </rdf:Seq>" . chr(10);
	$strXML .= "  </items>" . chr(10);
	$strXML .= "</channel>" . chr(10);
	$strXML .= "<image rdf:about=\"$siteURL/$siteLogo\">" . chr(10);
	$strXML .= "  <title>$siteName</title>" . chr(10);
	$strXML .= "  <url>$siteURL/$siteLogo</url>" . chr(10);
	$strXML .= "  <link>$siteURL</link>" . chr(10);
	$strXML .= "</image>" . chr(10);
	
	$result = mysql_query("select pk_aId, aTitle, aSummary, aDateCreated, aAuthorId from tbl_Articles where aActive = 1 and aStatus = 1 order by pk_aId desc");
	
	while($row = mysql_fetch_array($result))
	{
		$strXML .= "<item rdf:about=\"$siteURL/articles.php?articleId=" . $row["pk_aId"] . "\">" . chr(10);
		$strXML .= "  <title>" . ereg_replace("[^[:space:]a-zA-Z0-9]", "", $row["aTitle"]) . "</title>" . chr(10);
		$strXML .= "  <description>" . ereg_replace("[^[:space:]a-zA-Z0-9]", "", $row["aSummary"]) . "</description>" . chr(10);
		$strXML .= "  <link>$siteURL/articles.php?articleId=" . $row["pk_aId"] . "</link>" . chr(10);
		$strXML .= "  <dc:date>" . substr($row["aDateCreated"], 0, 4) . "-" . substr($row["aDateCreated"], 4, 2) . "-" . substr($row["aDateCreated"], 6, 2) . "</dc:date>" . chr(10);
		$strXML .= "  <dc:creator>" . chr(10);
		
		$aResult = mysql_query("select alFName, alLName, alEmail from tbl_AdminLogins where pk_alId = " . $row["aAuthorId"]);
		
		if($aRow = mysql_fetch_array($aResult))
			$strXML .= $aRow["alFName"] . " " . $aRow["alLName"] . " (mailto:" . $aRow["alEmail"] . ")" . chr(10);
		else
			$strXML .= "Unknown (mailto:$adminEmail)" . chr(10);
		
		$strXML .= "</dc:creator>" . chr(10);
		$strXML .= "</item>" . chr(10);
	}	
	
	$strXML .= "</rdf:RDF>" . chr(10);
	
	header("Content-type: text/xml");
	echo $strXML;
	
?>