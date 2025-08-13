<?php 
/*********************************************************
 * Author: John Astill (c)
 * Date  : 10th December 2001
 * File  : SystemVars.php
 * Desc  : Global data definitions. This is where the 
 *       : prediction league is configured for the 
 *       : specific installation.
 ********************************************************/
require "UserClass.php";
require "Error.php";

//////////////////////////////////////////
// System variable and configuration
//////////////////////////////////////////
//////////////////////////////////////////
// Constants
//////////////////////////////////////////
define("VERSION","0.38");

define("CORRECT_HOME_SCORE_POINTS","1");
define("CORRECT_AWAY_SCORE_POINTS","1");
define("CORRECT_MARGIN_POINTS","1");
define("CORRECT_RESULT_POINTS","1");
define("CORRECT_SCORE_POINTS","3");


//////////////////////////////////////////////////////////////
// Modify the values from here to where you are told to stop.
// The numbers match those in the installation steps in
// the file INSTALL.TXT.
//////////////////////////////////////////////////////////////
//////////////////////////////////////////
// 1.Prediction League Title
// The title of the Prediction League.
// Change the value between the "" to 
// give the prediction league the title
// of your choice
//////////////////////////////////////////
$PredictionLeagueTitle = "Prediction Football.com";

//////////////////////////////////////////////////////////////
// 2. Header Row
// This is the header to be displayed in 
//all the pages. It can contain HTML code.
//////////////////////////////////////////////////////////////
$HeaderRow = "<table width=\"800\"><tr><td align=\"center\" class=\"TBLHEAD\"><font class=\"TBLHEAD\"><img src=\"banner.jpg\"></font></td></tr></table>";

//////////////////////////////////////////////////////////////
// 3. Database hostname
// Database hostname
// The fqdn of the host containing the
// database
//////////////////////////////////////////////////////////////
$dbaseHost = "localhost";

//////////////////////////////////////////////////////////////
// 4. Base Directory Name
// The directory storing the prediction
// league files
//////////////////////////////////////////////////////////////
$baseDirName = "PredictionLeague";

//////////////////////////////////////////////////////////////
// 5. Username
// User name
// The username to be used for logging
// into the database
//////////////////////////////////////////////////////////////
$dbaseUsername = "username";

//////////////////////////////////////////////////////////////
// 6. Password
// Password to be used for logging into
// the database
//////////////////////////////////////////////////////////////
$dbasePassword = "password";

//////////////////////////////////////////////////////////////
// 7. Database Name.
// This is the name of the database. This *MUST* be the same
// name as the name you used when creating the database.
//////////////////////////////////////////////////////////////
$dbaseName = "PredictionLeague";

//////////////////////////////////////////////////////////////
// 8. The email address of the administrator. Set this to your 
// own address
//////////////////////////////////////////////////////////////
$adminEmailAddr = "john@predictionfootball.com";

//////////////////////////////////////////////////////////////
// 9. The signature of the admin to use at the end of the 
//    email welcoming the user. This can be a simple name,
//    or something more complex.
//////////////////////////////////////////////////////////////
$adminSignature = "john\nhttp://www.predictionfootball.com/";

//////////////////////////////////////////////////////////////
// 10. The default icon to use for a new user. The icons are
// displayed when the user is logged on. If you have an icon
// named default.gif, you can leave this as default.gif.
//////////////////////////////////////////////////////////////
$defaulticon = "default.gif";

//////////////////////////////////////////////////////////////
// 11. The URL of the associated chat room.
// This link can be used to point to chatroom, or discussion
// area you may have for your prediction league.
// If this is empty, the menu link is not shown.
//////////////////////////////////////////////////////////////
$chatRoomURL = "";

//////////////////////////////////////////////////////////////
// 12. The URL of the associated home page
// Add the URL of your home page. This is shown in the menu.
//////////////////////////////////////////////////////////////
$homePage = "";
$homePageTitle = "";

//////////////////////////////////////////////////////////////
// 13. The name of the log file. 
// "" disables the logfile functionality.
//////////////////////////////////////////////////////////////
$logfile = "";

//////////////////////////////////////////////////////////////
// 14. To allow more than one user per email address set
//     this variable to TRUE .
//     e.g. $allowMultipleUserPerEmail = TRUE;
//////////////////////////////////////////////////////////////
$allowMultipleUserPerEmail = FALSE;

//////////////////////////////////////////////////////////////
// 15. To enable password encryption set this to TRUE.
//     To disable set to FALSE
//     e.g. $useEncryption = TRUE;
//     Note: If you are using version 0.37 or below, you need
//     to encrypt your users passwords after enabling
//     encryption. See Upgrade.txt
//////////////////////////////////////////////////////////////
$useEncryption = FALSE;

/*************************************************************
**************************************************************
* The following values should not be modified unless you
* REALLY know what you are doing.
**************************************************************
**************************************************************/
/*************************************************************
// Data Tables
// The following is where you define the names of your
// database tables.
*************************************************************/
/*************************************************************
// The name of the table to be used for the User Data.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbaseUserData = "UserData";

/*************************************************************
// The name of the table to be used for the Prediction Data.
// This value *MUST* be the same as the value defined when 
// creating the tables.
*************************************************************/
$dbasePredictionData = "PredictionData";

/*************************************************************
// The name of the table to be used for the Match Data. This
// value *MUST* be the same as the value defined when creating
// the tables.
*************************************************************/
$dbaseMatchData = "MatchData";

/*************************************************************
** The home page for the Prediction League
*************************************************************/
$PLHome = "http://www.predictionfootball.com/";

/*************************************************************
** The number of users to display on each page of the 
** prediction league. This is the default value. Each user
** can select their own.
*************************************************************/
define("USERS_PER_PAGE","80");

/*************************************************************
** The maximum allowed number of admin users.
** If this value is increased it is essential that 
** the user is created as this could present a security 
** hole.
*************************************************************/
$maxAdminUsers = 1;

/*************************************************************
** User parameters used in sessions. This
** needs to be global
*************************************************************/
$User = 0;
?>
