<font class='catdis'> > Edit Layout </font><p>
<?
if( !$_POST["submitlayout"] ) {
	$sheet = implode("",file(SL_ROOT_PATH."/base/html/Default/index.tmpl.php"));
?>

	<form method='post' action='main.php?layout'>
	<textarea name='layout' style='height:400px;width:100%'><?=$sheet?></textarea><br>
	<input type='submit' name='submitlayout' value='edit layout'>
	</form>

<?
} else {
	$fp = fopen(SL_ROOT_PATH."/base/html/Default/index.tmpl.php","w",0777);
	fwrite($fp, stripslashes($_POST["layout"]));
	fclose($fp);
	$sheet = implode("",file(SL_ROOT_PATH."/base/html/Default/index.tmpl.php"));
?>
	<form method='post' action='main.php?layout'>
	<textarea name='layout' style='height:400px;width:100%'><?=$sheet?></textarea><br>
	<input type='submit' name='submitlayout' value='edit layout'>
	</form>	
<?
}
?>