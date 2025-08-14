<?php

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 1;
global  $Pmenu,$breadcrumb;
//Only Enter a menu into the var if you actually created one, otherwise leave blank.
// ie. $Pmenu="Sample";
$Pmenu="";
$breadcrumb="Sample";
function one() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"one\"<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=Addon_Sample&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}

function two() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"two\"";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=Addon_Sample&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}


function AddonSample() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php)<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=Addon_Sample&amp;file=index&amp;func=one\">Function One</a>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=Addon_Sample&amp;file=index&amp;func=two\">Function Two</a>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=Addon_Sample&amp;file=f2\">Call to file f2.php</a>";
    echo "</ul>";
    echo "This is a Sample Addon a new menu link is added automaticaly when you create as new" 
	     ."sub-directory with-in the modules sub-directory. To remove just rename the modules"
		 ." Addon_Sample sub-directory to NS-Addon_Sample";
    CloseTable();
    include("footer.php");
}

switch($func) {

    default:
    AddonSample();
    break;
    
    case "one":
    one();
    break;

    case "two":
    two();
    break;

}

?>