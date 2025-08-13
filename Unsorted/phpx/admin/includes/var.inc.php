<?php
#$Id: var.inc.php,v 1.21 2003/10/20 14:59:47 ryan Exp $
$filter = array("on" => "1", "off" => "0");

$progTitle = "PHPX";

$image_types = array(".gif" => "image/gif", ".jpg" => "image/jpeg", ".png" => "image/png", ".jpg" => "image/pjpeg");
$menuTypeArray = array("Vertical", "Horizontal", "Tree", "Basic Text");
$loginCodes["s"] = "Account Suspended";
$loginCodes["i"] = "Invalid Login";
$loginCodes["a"] = "Unauthorized Action";
$loginCodes["l"] = "Logged Out";

$forumLevel = array("Guest Post", "Guest Read Only", "Members Post", "Members Read Only", "Moderator Post", "Moderator Read Only", "Admin Only");

//Error messages
$errorHeader["401"] = "<tr><td class=mainbold>Error 401 - UnAuthorized Access</td></tr>";
$errorHeader["403"] = "<tr><td class=mainbold>Error 403 - Forbidden</td></tr>";
$errorHeader["404"] = "<tr><td class=mainbold>Error 404 - Not Found</td></tr>";
$errorHeader["500"] = "<tr><td class=mainbold>Error 500 - Internal Server Error</td></tr>";
$errorHeader["503"] = "<tr><td class=mainbold>Error 503 - Service Unavailable</td></tr>";

$errorMessage["401"] = "<tr><td class=main>You do not have permission to access this document.  Please check your URL and try again.</td></tr>";
$errorMessage["403"] = "<tr><td class=main>This page and/or directory is forbidden.  Please check your URL and try again.</td></tr>";
$errorMessage["404"] = "<tr><td class=main>This page and/or directory could not be found.  Please check your URL and try again.</td></tr>";
$errorMessage["500"] = "<tr><td class=main>There was an Server Error.  Please email us using the link below and indicated what you were attempted to do when you encountered this page.</td></tr>";
$errorMessage["503"] = "<tr><td class=main>There was an Server Error.  The server is currently unavailable.  We are working to restore it.  Please check back.</td></tr>";

$errorFooter = "<tr><td class=main><a href=index.php>Click Here to Return to Site</a></td></tr>";

$pageFooter = " ";


$featureInsert["News"] = ":NEWS:";
$featureInsert["Stats"] = ":STATS:";
$featureInsert["Contact"] = ":CONTACT:";

$subFeatureInsert["Who Is Online"] = ":ONLINE:";
$subFeatureInsert["Search"] = ":SEARCH:";


$menuArray["Users"] = "users.php";
$menuArray["Forums"] = "forums.php";
$menuArray["FAQ"] = "faq.php";
$menuArray["My Profile"] = "users.php?action=view";
$menuArray["News Categories"] = "news.php?action=list_cat";
$menuArray["News Submission"] = "news.php?action=submit";




$commentType[1] = "News";
$commentType[2] = "Gallery";
$commentType[3] = "Links";
$commentType[4] = "Downloads";
$commentType[5] = "Reviews";
$commentType[6] = "Clubs";
$commentType[7] = "FAQ";


?>
