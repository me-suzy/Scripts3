<b>FINISHED!</b>
<small><p>PHP CLASSIFIEDS is now almost completely installed. To finish, you <b>must</b>
go to <a href="admin/set.php">Setconfig</a>, and save your settings in order to initialize the program
with your own options !<p>

You can access <a href="admin/">the admin</a> directory to administer the program further,
or see your pages at <a href="index.php">your classified</a> pages.
<p>
<b>Security:</b>
<ol>
<li>Remember to first check if you are asked for username and password when accessing admin dir.
If you are not asked for this, anyone can access and use your admin pages.<p></li>

<li><font color=red><b>MAJOR WARNING:</b></font> Another important thing is to deactivate the install file. This can be done by pushing this button:
<br /><a href="?level=5&deact=1">Delete install file</a>, or by manually delete it. By leaving it as it is, anyone can delete
your <u>entire</u> database !
</il>

</ol>
<?
if ($deact == 1)
{
     unlink("install.php");
     print "<p>Install.php deleted !<p>";
}
?>
</p></small>
