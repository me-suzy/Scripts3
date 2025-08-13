<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td bgcolor="lightgrey">
     &nbsp; Update 
	</td>
</tr>

<tr bgcolor="white">
	<td width="100%">
     
<b>Languagefiles on server</b><p>
Languagefiles will be updated whenever needed, often in conjunction with relase of new PHP Classifieds versions.
If you do not have the lates language file, and upgrade the program and still using old language file, there will
be problems.<p>

<p>

IF there is a updated language file, the word  NEW  will be shown beside the new files. Note also
that this program only check date agains the active languagefile. 
<p>
<? 
print "<a href='?check_upd=1'><b>Check for update</b></a><p>";
?>


<p>
<table>
<tr><td valign=top>
<?
if ($check_upd) 
{
	if ($install)
	{
		$WebFile = "http://www.deltascripts.com/phpclassifieds/lang/download/$install";
		$handle = fopen ($WebFile, "r");
		$outhandle=fopen ("..//language/$install.php","w");
		while (!feof($handle)) {
     		$buffer=fread($handle,4096);
     		fputs($outhandle,$buffer);
		}
		fclose($handle);
		fclose($outhandle);
		print " <b>$install.php</b> language file has now been created/overwritten. ";
		print "Go to <a href='set.php?file_name=config/general.inc.php'>Settings</a> to activate this language, ";
		print "if not already selected. <p>";
	}
	
	if (!$install)
	{
		print " <b>Your selected language</b> <br />";
		$WebFile = "http://www.deltascripts.com/phpclassifieds/lang/check_up.php?generated=$generated";
		$fp = fopen ($WebFile, "r");
		while (!feof($fp)) {
    		$buffer=fgets($fp, 4096);
			$tmp_buffer = substr($buffer,0,3);
    		$tmp_lng = substr($language_file,-3,3);
    	
    		if ($tmp_buffer == $tmp_lng)
    		{
				print " $buffer <a href='?install=$tmp_lng&check_upd=1'>Install/Upgrade</a> from DeltaScripts.com <br />";
    		}
		}
		fclose($fp);
	
		print "<p><p> <b>Other language files</b> <br />";
		$fp = fopen ("http://www.deltascripts.com/phpclassifieds/lang/check_up.php?plain=1", "r");
		while (!feof($fp)) 
		{
     		$buffer=fgets($fp,4096);
     		if ($buffer<> "")
     		{
     			print " <a href='?install=$buffer&check_upd=1'>Install/Upgrade $buffer</a> from DeltaScripts.com <br />";
     		}
		}
	}
}

?>


    <p>
	</td>
</tr>
</table>
<? require("admfooter.php"); ?>
