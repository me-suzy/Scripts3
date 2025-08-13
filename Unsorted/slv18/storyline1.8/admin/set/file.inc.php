<font class='catdis'> > Edit Files </font><p>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin";
function fileWrite(sel) {
	var str = sel.options[sel.selectedIndex].value;
	var inArray = str.split("|||");
	document.form.fname.value = inArray[0];
	document.form.fbody.value = inArray[1];
}
//  End -->
</script>

<?
$banner = implode("" ,file(SL_ROOT_PATH."/base/html/Default/banner.inc.php"));
$menu = implode("" ,file(SL_ROOT_PATH."/base/html/Default/menu.inc.php"));
$footer = implode("" ,file(SL_ROOT_PATH."/base/html/Default/footer.inc.php"));
$stats = implode("" ,file(SL_ROOT_PATH."/base/html/Default/stats.inc.php"));
$copyright = implode("" ,file(SL_ROOT_PATH."/base/html/Default/copyright.inc.php"));

if (!$_POST["submitfile"]) {
?>
	<select name='chapterlist' onChange='fileWrite(this)'>
	<option value=''> -- Choose File -- </option>
	<option value='banner.inc.php|||<?=htmlspecialchars($banner, ENT_QUOTES)?>'>banner.inc.php</option>
	<option value='menu.inc.php|||<?=htmlspecialchars($menu, ENT_QUOTES)?>'>menu.inc.php</option>
	<option value='footer.inc.php|||<?=htmlspecialchars($footer, ENT_QUOTES)?>'>footer.inc.php</option>
	<option value='stats.inc.php|||<?=htmlspecialchars($stats, ENT_QUOTES)?>'>stats.inc.php</option>
	<option value='copyright.inc.php|||<?=htmlspecialchars($copyright, ENT_QUOTES)?>'>copyright.inc.php</option>
	</select>
	<p>
	<form name='form' action='main.php?file' method='post'>
	<textarea style='height:300px; width:100%' name='fbody'></textarea>
	<input type='hidden' name='fname'>
	<input type='submit' name='submitfile' value='edit file'>
	</form>
<?
} else {

	$fp = fopen(SL_ROOT_PATH."/base/html/Default/".$_POST["fname"],"w",0777);
	fwrite($fp, stripslashes($_POST["fbody"]));
	fclose($fp);

	print "File has been edited to:<br>";
	print $_POST["fbody"];

}
?>