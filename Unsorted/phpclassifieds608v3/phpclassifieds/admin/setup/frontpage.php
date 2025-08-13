<p>
Welcome to PHP Classifieds install.
This install program will take you through all you have to do to get
PHP Classifieds up and running.</p>

<textarea rows="5" name="S1" cols="70">
<? include("admin/setup/licence.txt");  ?>
</textarea>
<p>

<h4>Error-checking:</h4>
<?
global $error;
// Install dir
if ($dir)
{
        print("<b>$dir</b> is reported to be your full install-path.");
}
else
{
        print(" Please fix : <small><br />There seems to be an error, because php4 doesnt report your                 full path.</small><br />");
}
        print("<br />");

// Image dir
if ($imgedir_perm == 16895)
{
        print("<b>$imagedir</b> is <font color=green>correctly</font> set to CHMOD 777 <br />");
}
else
{
        print(" Please fix : <small><br />You must set the directory <i>$imagedir</i> to all writeable         (chmod 777 $file). </small><br />");

}

// Admin dir
if ($admindir_perm==16895)
{
        print("<b>$admindir</b> is <font color=green>correctly</font> set to CHMOD 777 <br />");

}
else
{
        print(" Please fix : <small><br />You must set the directory <i>$admindir</i> to all writeable         (chmod 777 $file). </small><br />");
        $error = 1;
}

if ($configdir_perm == 16895)
{
        print("<b>$configdir</b> is <font color=green>correctly</font> set to CHMOD 777 <br />");
}
else
{

        print(" Please fix : <small><br />You must set the directory <i>$configdir</i> to all writeable         (chmod 777 $file).</small><br />");
        $error = 1;
}

// Wap dir
if ($wapdir_perm == 16895)
{
        print("<b>$wapdir</b> is <font color=green>correctly</font> set to CHMOD 777 <br />");
}
else
{
        print(" Please fix : <small><br />You must set the directory <i>$wapdir</i> to all writeable                 (chmod 777 $file). </small><br />");
        $error = 1;
}


if (!$error)
{     print "<p>All is OK, you may continue:<br />";
      print("<a href='install.php?level=1'>START INSTALLATION</a>");
}
elseif ($error)
{
      print "<p>One or more of the directories above is not writeable, you cant install beforde its fixed!<br />";
}

?>
