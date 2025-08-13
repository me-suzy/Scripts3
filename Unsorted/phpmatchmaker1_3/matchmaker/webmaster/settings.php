<? require("admheader.php"); ?>

<h3>Settings</h3>

<table border="0" width="550"><tr><td>
<font face='Verdana' size='1'>
<?


// Filewriting
if ($submit)
{
 	$file_pointer = fopen("settings_inc.php", "w");
 	
 	$string = $string . "<?\n";
	fwrite($file_pointer,$string);
	
 	while (list($key,$value) = each($HTTP_POST_VARS))
	{
		if ($key <> "submit")
		{
			$string = "$" . $key . "=\"" . $value . "\";\n";
			fwrite($file_pointer,$string);
		}
	}
	$string = $string . "?>";
	fwrite($file_pointer,$string);
	fclose($file_pointer);
	print "Saved";
	//print "<script>javascript:alert('Settings saved!');</script>";
}


if (file_exists("settings_inc.php"))
{
 include("settings_inc.php");
}

if (!$submit)
{
	if (file_exists("settings_inc.php"))
	{
 		include("settings_inc.php");
	}
}	
?>

<form method="post" action="settings.php">

<b>Webmaster emailaddress</b><br />
Please write your emailaddress here. It will be in the From: field in all emails
sent from your side.<br />
<input type="text" name="from_adress" value="<? echo $from_adress ?>" />
<p />

<b>URL to PHP MatchMaker</b><br />
Type in URL like http://mydomain.com/matchmake/. Include trailing slash (/).<br />
<input type="text" name="url_of_site" value="<? echo $url_of_site ?>" />
<p />

<b>Name of site</b><br />
Your website name...<br />
<input type="text" name="phpmmname" value="<? echo $phpmmname ?>" />
<p />

<b>Thumbnail size</b><br />
Please state what size you want on your thumbnails, displayed in
search view and listings. Type it like 200x200, it will then scale down.<br />
<input type="text" name="set_thmbsize" value="<? echo $set_thmbsize ?>" />
<p />

<b>Original size</b><br />
Please state what size you want on your original pictures, displayed on ads
and in search view. Type it like 200x200, it will then scale down.<br />
<input type="text" name="set_orgsize" value="<? echo $set_orgsize ?>" />
<p />

<b>Picture limit</b><br />
How large can the picture be ? Type it in bytes, like 47000 for 47 kb.<br />
<input type="text" name="set_piclimit" value="<? echo $set_piclimit ?>" />
<p />



<b>Email validation</b><br />
If you want users to get an email where they need to go to a spesified url in order to confirm their
emailaddress, check the option below.<br />
<input type="checkbox" value="on" name="set_evalidation" <? if ($set_evalidation=="on") print "checked"; ?> />
<p />

<b>Ban control</b><br />
Here you can block unwanted emailaddresses or emaildomains. For instance, <b>*@domain.com</b> 
will block all users with @domain.com in their emailaddress, while <b>tom@domain.com</b> only 
will block that particular address. NOTE: Seperate each banned email with the <b>,</b> character.<br />
<input type="text" size="100" name="set_banlist" value="<? echo $set_banlist ?>" />
<p />

<!--
<b>Cencor control</b><br />
This will deny the user to save his/her profile before the bad words listed below is removed.
Seperate each word with <b>,</b> to have it check for each word.
<input type="text" size="100" name="set_censorlist" value="<? echo $set_cencorlist ?>" />
<p />
-->



<b>Use ImageMagick</b><br />
Please state what size you want on your original pictures, displayed on ads
and in search view. Type it like 200x200, it will then scale down. 
Note: <a href='http://www.imagemagick.org/' target='_blank'>ImageMagick software</a> (external program) must be installed on your
server. It will then compress images for better performance.
<br />
<input type="checkbox" name="set_magic" <? if ($set_magic=="on") print "checked"; ?> />&nbsp&nbsp;
Quality (0-100): <input type="text" name="set_magic_q" size="3" value="<? echo $set_magic_q ?>" />
<p />

<b>ImageMagick path</b><br />
As a safety procedure, the ImageMagick path must be set directly in the file <b>upload_image.php</b>.
Look for comments in the code.
<p />




<p />
<p />
<input type="submit" name="submit" value="Save">
</form>

</font>
</td></tr></table>
<? require("admfooter.php"); ?>