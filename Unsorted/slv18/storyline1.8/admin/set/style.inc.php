<font class='catdis'> > Edit Stylesheet </font><p>
<?
if( !$_POST["submitstyle"] ) {
	$sheet = implode("",file(SL_ROOT_PATH."/base/html/Default/style.css"));
?>

	<form method='post' action='main.php?style'>
	<textarea name='style' style='height:400px;width:100%'><?=$sheet?></textarea><br>
	<input type='submit' name='submitstyle' value='edit stylesheet'>
	</form>

<?
} else {
	$fp = fopen(SL_ROOT_PATH."/base/html/Default/style.css","w",0777);
	fwrite($fp, $_POST["style"]);
	fclose($fp);
	$sheet = implode("",file(SL_ROOT_PATH."/base/html/Default/style.css"));
?>
	<form method='post' action='main.php?style'>
	<textarea name='style' style='height:400px;width:100%'><?=$sheet?></textarea><br>
	<input type='submit' name='submitstyle' value='edit stylesheet'>
	</form>	
<?
}
?>