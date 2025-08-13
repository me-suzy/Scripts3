<html>
<head>
</head>

<body>
<h2>PHP CLASSIFIEDS INSTALL</h2>
<a href="install.php">0</a>&nbsp;|&nbsp;<a href="install.php?level=1">1</a>&nbsp;|&nbsp;<a href="install.php?level=2">2</a>&nbsp;|&nbsp;<a href="install.php?level=3">3</a>&nbsp;|&nbsp;<a href="install.php?level=4">4</a>&nbsp;|&nbsp;<a href="install.php?level=5">5</a><br />
<?




if (!$level)
{
        print("<p>Step 1&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3&nbsp; &gt;&gt; Step
4&nbsp; &gt;&gt; Step 5</p>");
}
if ($level == 1)
{
        print("<p><b>Step 1</b>&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3&nbsp; &gt;&gt; Step
4&nbsp; &gt;&gt; Step 5</p>");
}
if ($level == 2)
{
        print("<p>Step 1&nbsp; &gt;&gt; <b>Step 2</b>&nbsp; &gt;&gt; Step 3&nbsp; &gt;&gt; Step
4&nbsp; &gt;&gt; Step 5</p>");
}
if ($level == 3)
{
        print("<p>Step 1&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; <b>Step 3</b>&nbsp; &gt;&gt; Step
4&nbsp; &gt;&gt; Step 5</p>");
}
if ($level == 4)
{
        print("<p>Step 1&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3&nbsp; &gt;&gt; <b>Step
4</b>&nbsp; &gt;&gt; Step 5</p>");
}
if ($level == 5)
{
        print("<p>Step 1&nbsp; &gt;&gt; Step 2&nbsp; &gt;&gt; Step 3&nbsp; &gt;&gt; Step
4&nbsp; &gt;&gt; <b>Step 5</b></p>");
}

// NOTE: Here you can set full path to you files if the in-built php function
// getcwd doesn´t work.
// Example: $dir = "/home/user/public_html/phpclassifieds";
// Remember to remove! the $dir = getcwd(); if you do this.

// Uncomment (remove // ) from THE BELOW LINE if manually set:
// $dir = "/home/user/public_html/phpclassifieds";

// Delete $DIR = getcwd();  if FULL PATH manually is set $dir above ! ! !


$version = explode(".",phpversion());
if ($version >= 4)
{
     ##########################################################
     ## $dir = "/home/username/pubcli_html/classifieds";
     $dir = getcwd ();
     ##
     ###########################################################

     if (!$dir)
     {
           $dir = dirname($SCRIPT_FILENAME);
     }
}
else
{
    print " Your version is " . phpversion() . ". You need version 4.0 or above to use this script! ";
}


$configdir = $dir . "/admin/config";
$configdir_perm = fileperms("$configdir");
$imagedir = $dir . "/images";
$imgedir_perm = fileperms("$imagedir");
$wapdir = $dir . "/wap";
$wapdir_perm = fileperms("$wapdir");
$admindir = $dir . "/admin";
$admindir_perm = fileperms("$admindir");

?>

</p>
<table width="70%">
<tr>
<td>
<?
if (!$level)
{
     include("admin/setup/frontpage.php");
}
if ($level == "1")
{
     include("admin/setup/level1.php");
}
if ($level == "2")
{
     include("admin/setup/level2.php");
}

if ($level == "3")
{
     include("admin/setup/level3.php");
}
if ($level == "4")
{
     include("admin/setup/level4.php");
}
if ($level==5)
{
    include("admin/setup/level5.php");
}



?>
</td>
</tr>
</table>
</body>
</html>
