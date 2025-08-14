<?
// ************************************************************************************
// Program File Name : instantfeedreader.config.inc.php
// Programmer 		 : Benjamin Lim
// Email             : ben@benruth.com
//
// Purpose :
// Configuration settings for the instant feed reader
//
// Tested Specifications of RSS
// Rich Site Summary (RSS 0.91)
// RDF Site Summary (RSS 0.9 and 1.0)
// Really Simple Syndication (RSS 2.0)
//
// Version :
// Stable Build 1.6
//
// Date First Completed : 15 Jul 2004 (Version 1.0)
//
// Revision History
//
// 20 Jul 2004 (Version 1.1)
// Added number of news items to display option
// Corrected and updated the comments within this configuration file
// Added support to recognise GUID tag elements in ITEM
// Added URL version
//
// 30 Jul 2004 (Version 1.2)
// Added Formatting Tags for customisation of output
// Allow dual output of Plain Text or Javascript Include Codes
//
// 07 Aug 2004 (Version 1.3)
// Added config option for defining stylesheet class to the <A> tag for the title
//
// 25 May 2005 (Version 1.4)
// Added support to display the published date for the feed item
// Allow the restriction of the total number of characters in one feed item
// Custom Error Message
//
// 08 Jun 2005 (Version 1.5)
// Added Caching Support to ensure make the display of feeds faster,
// also reduces the bandwidth suck of the site providing the feed.
//
// 17 Sep 2005 (Version 1.6)
// Added option to choose whether to strip away HTML tags before display.
// Added option to cchoose whether to convert all applicable characters to HTML entities
//
// Copyright 2005 Lim Hock Leong Benjamin ( www.benruth.com )
// ************************************************************************************

// You change the formatting of the news feed by either, placing CSS inside your head tag...
//
//  <HEAD>
//	<style>
//	A:link          {BACKGROUND: none; COLOR: #363636; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}
//	A:active        {BACKGROUND: none; COLOR: #363636; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}
//	A:visited       {BACKGROUND: none; COLOR: #363636; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}
//	A:hover         {BACKGROUND: none; COLOR: RED; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
//	p { color: black; font-family: verdana,sans-serif; font-size: 12px; }
//	</style>
//  </HEAD>
//
// OR you can surround the <SCRIPT> tag with <FONT> Tags
//
// <FONT face="Verdana, Arial, Helvetica, sans-serif">
//		<SCRIPT language=JavaScript src="http://benruth.com/instantfeedreader.php"></ SCRIPT>
// </FONT>
//
// OR you can change the presentation output, the configuration options are listed further down in this configuration file.
//
// A Cool Tip
// You can display MULTIPLE News Feeds on your website if you have a few installations of this in seperate folders.
//
// eg.
// /newsfeed1/instantfeedreader.php
// /newsfeed2/instantfeedreader.php
// /newsfeed3/instantfeedreader.php
// /newsfeed4/instantfeedreader.php
//
// And your javascript in your website would look something like
//
// <SCRIPT language=JavaScript src="http://benruth.com/newsfeed1/instantfeedreader.php"></ SCRIPT>
// <SCRIPT language=JavaScript src="http://benruth.com/newsfeed2/instantfeedreader.php"></ SCRIPT>
// <SCRIPT language=JavaScript src="http://benruth.com/newsfeed3/instantfeedreader.php"></ SCRIPT>
// <SCRIPT language=JavaScript src="http://benruth.com/newsfeed4/instantfeedreader.php"></ SCRIPT>
//
// Please email me at   ben AT benruth DOT com
// if you think there's something breaking the display.
// Let me know which RSS/XML specification that you are using, and the location of your feed, and
// the location of the instant feed reader.

// The configurations to play around with.
//
// XML/RSS Feed to parse
//
// Example
// $sFilename = "http://www.benruth.com/feed.xml";
//
// Leave it as "" to use the URL version
// example
//
// in config file
// $sFilename = "";
//
// in the javascript
// <SCRIPT language=JavaScript src="http://benruth.com/newsfeed4/instantfeedreader.php?feed=http://www.benruth.com/feed.xml"></ SCRIPT>
//
// The reasoning behind this is to prevent other people from supply any feed in the url and use up your precious server
// resources, if you only want specific news feeds to be read.
//
// So, either supply a filename in the config file, and ENSURE that it is the only feed that you serve
// OR, leave the filename blank in the config file, and allow possibly anyone to type in any feed url.

// ********************************** EDIT THIS **********************************
// $sFilename = "http://www.benruth.com/feed.xml";
$sFilename = "";
// *******************************************************************************

//
// Number of news items to display
//
// Example
// $iDisplay = 10;
//
// Leave it as 0 to display everything!

// ********************************** EDIT THIS **********************************
$iDisplay = 0;
// *******************************************************************************

//
// Output To be written in
//
// Example for Javascript Output
// $iOutputType = 1;
//
// will produce
//
// document.write("<p><a href='http://benruth.com/subpage.php?PageID=35'>BENRUTH INSTANT RSS/XML FEED READER!</a><br>Reads RSS/XML compliant news feeds via a client-side Javascript Script Include in your web pages! Configure the source of the news feed via a single configuration file. Simple, free and easy to use!</p>");
// document.write("<p><a href='http://www.benruth.com/subpage.php?PageID=34'>BENRUTH INSTANT RSS/XML FEED GENERATOR!</a><br>Generate a downloadable RSS/XML compliant news feed! This script is for non-technical people using WYSIWYG page building tools or personal home page building systems. Let the online form guide you through the various information that you need to supply, and upon a click of the Generate Feed button, a copy/paste version as well as a ready formatted downloadable XML file is ready for your use! Provided Free for usage. Enjoy!</p>");
//
// Which is necessary for the Javascript Include Script
//
// However, I also allow the option to display as Plain Text!
//
// <p><a href='http://benruth.com/subpage.php?PageID=35'>BENRUTH INSTANT RSS/XML FEED READER!</a><br>Reads RSS/XML compliant news feeds via a client-side Javascript Script Include in your web pages! Configure the source of the news feed via a single configuration file. Simple, free and easy to use!</p>
// <p><a href='http://www.benruth.com/subpage.php?PageID=34'>BENRUTH INSTANT RSS/XML FEED GENERATOR!</a><br>Generate a downloadable RSS/XML compliant news feed! This script is for non-technical people using WYSIWYG page building tools or personal home page building systems. Let the online form guide you through the various information that you need to supply, and upon a click of the Generate Feed button, a copy/paste version as well as a ready formatted downloadable XML file is ready for your use! Provided Free for usage. Enjoy!</p>
//
// The interesting idea behind this implemntation is to allow php-include, server side includes via the use of this script.
// Also, since i write solveware that caters to accessibility, i generally would prefer to do away with the use of javascript.
//
// Specify anything else to display as Plain Text!

// ********************************** EDIT THIS **********************************
$iOutputType = 1;
// *******************************************************************************

//
// Whether the links, upon clicking, will open up a new window or not.
//
// Example
// $iLinksOpenInNewWindow = 1;
//
// Leave it as 0 to display within the same window!

// ********************************** EDIT THIS **********************************
$iLinksOpenInNewWindow = 0;
// *******************************************************************************

//
// Whether the description will be shown under the title or not.
//
// Example
// $iShowDescription = 1;
//
// Leave it as 0 so as to hide the description.

// ********************************** EDIT THIS **********************************
$iShowDescription = 1;
// *******************************************************************************

//
// Whether the Published Date will be shown
//
// Example
// $iShowPubDate = 1;
//
// Leave it as 0 so as to hide the date.

// ********************************** EDIT THIS **********************************
$iShowPubDate = 0;
// *******************************************************************************

//
// Formatting of the Output
//
// Example
// $sStartLineTag = "<p>";
// $sStartTitleTag = "";
// $sTitleAnchorClass = ""; // $sTitleAnchorClass = "myCoolAnchorStyle";
// $sEndTitleTag = "";
// $sStartPubDateTag = ""; // $sStartPubDateTag = " - ";
// $sEndPubDateTag = "";
// $sStartDescriptionTag = "<br />";
// $sEndDescriptionTag = "";
// $sEndLineTag = "</p>";
//
// You may want to tweak this to customise the display
//
// The Output Structure
//
// $sStartLineTag
//     $sStartTitleTag  $sTitleAnchorClass SHOW_THE_TITLE_HERE $sEndTitleTag $sStartPubDateTag SHOW_THE_DATE_HERE $sEndPubDateTag
//     $sStartDescriptionTag  SHOW_THE_DESCRIPTION_HERE $sEndDescriptionTag
// $sEndLineTag
//

// ********************************** EDIT THESE **********************************
$sStartLineTag = "<p>";
$sStartTitleTag = "";
$sTitleAnchorClass = "";
$sEndTitleTag = "";
$sStartPubDateTag = "";
$sEndPubDateTag = "";
$sStartDescriptionTag = "<br />";
$sEndDescriptionTag = "";
$sEndLineTag = "</p>";
// ********************************************************************************

//
// Custom Error Message
//
// Some of you might want to have your very own error message, in case of an invalid feed file.
//
// Example:
//
// $sCustomErrorMessage = "No feed file specified! <br /><br />Possible action is  to check that there is a feed url, for example <br />http://www.benruth.com/instantfeedreader/instantfeedreader.php?<b>feed=http://www.benruth.com/feed.xml</b> <br /><br />or the sFilename in the configuration file is pointing to a feed url.<br /><br />You can contact the developer ( ben AT benruth DOT com ) for help!";
//
// or to have no error messages:
//
// $sCustomErrorMessage = "";

// ********************************** EDIT THESE **********************************
$sCustomErrorMessage = "No feed file specified! <br /><br />Possible action is  to check that there is a feed url, for example <br />http://www.benruth.com/instantfeedreader/instantfeedreader.php?<b>feed=http://www.benruth.com/feed.xml</b> <br /><br />or the sFilename in the configuration file is pointing to a feed url.<br /><br />You can contact the developer ( ben AT benruth DOT com ) for help!";
// ********************************************************************************

//
// Limit the no. of characters in Title
//
// Example
// $iMaxTitleChars = 0;
//
// Leave it as 0 as no limits.

// ********************************** EDIT THIS **********************************
$iMaxTitleChars = 0;
// *******************************************************************************

//
// Limit the no. of characters in Description
//
// Example
// $iMaxDescriptionChars = 0;
//
// Leave it as 0 as no limits.

// ********************************** EDIT THIS **********************************
$iMaxDescriptionChars = 0;
// *******************************************************************************

//
// Location of cache (absolute path)
// IMPORTANT TO CHMOD THIS DIRECTORY MAKE IT WRITABLE! CHMOD 777
//
// Example
// $sCacheLocation = "/home/benruth/public_html/instantfeedreader/mycache";
//
// Leave it as "" to represent that you don't want caching.
//
$sCurrentDirectory = dirname(__FILE__) . "/"; // ignore this, please.
//
// If you don't mind the cache directory being in the same level as this script,
// You can use $sCurrentDirectory in front of the $sCacheLocation for simplicity
// Just replace "mycache" with your preferred name
//
// Example
// $sCacheLocation = $sCurrentDirectory . "mycache";
//
//
// ********************************** EDIT THIS **********************************
$sCacheLocation = $sCurrentDirectory . "mycache";
// *******************************************************************************

//
// Length of time in seconds to cache
//
// Example
// $iTimeInSecondsToCache = 0;
//
// Leave it as 0 or something negative to set it to default 20 mins (1200 seconds).

// ********************************** EDIT THIS **********************************
$iTimeInSecondsToCache = 0;
// *******************************************************************************

//
// Choose whether to strip away HTML tags before display
//
// Example
// $iStripAwayHTMLTags = 1;
//
// Leave it as 0 or something negative to set it to NO (Don't strip HTML Tags).

// ********************************** EDIT THIS **********************************
$iStripAwayHTMLTags = 0;
// *******************************************************************************

//
// Choose whether to convert all applicable characters to HTML entities
//
// Example
// $iConvertToHTMLEntities = 1;
//
// Leave it as 0 or something negative to set it to NO (Don't Convert).

// ********************************** EDIT THIS **********************************
$iConvertToHTMLEntities = 0;
// *******************************************************************************


// End of configuration file. That's it folks! :-)
?>