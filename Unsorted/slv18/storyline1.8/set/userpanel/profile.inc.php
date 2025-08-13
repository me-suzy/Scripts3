<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function previewPic(sel) {
	document.previewpic.src = "<?=SL_ROOT_URL?>/images/avatars/" + sel.options[sel.selectedIndex].value;
}
//  End -->
</script>

<?
$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;
	
$table = $dl->select("*","sl18_users",array('uid'=>$_SESSION["uid"]));
?>

<form method='post' action='base/trans.php?profile'>
<table border='0' width='100%'>
	<tr>
		<td width='20%'>User Name</td><td><?=$table[0]["urealname"]?></td>
	</tr><tr>
		<td width='20%'>Pen Name</td><td><input type='text' name='upenname' value='<?=$table[0]["upenname"]?>'></td>
	</tr><tr>
		<td width='20%'>Password</td><td><input type='text' name='upass' value='<?=$table[0]["upass"]?>'></td>
	</tr><tr>
		<td width='20%'>Email</td><td><input type='text' name='uemail' value='<?=$table[0]["uemail"]?>'></td>
	</tr><tr>
		<td width='20%'>Homepage</td><td><input type='text' name='uurl' value='<?=$table[0]["uurl"]?>'></td>
	</tr><tr>
		<td width='20%'>MSN</td><td><input type='text' name='umsn' value='<?=$table[0]["umsn"]?>'></td>
	</tr><tr>
		<td width='20%'>AIM</td><td><input type='text' name='uaol' value='<?=$table[0]["uaol"]?>'></td>
	</tr><tr>
		<td width='20%'>ICQ</td><td><input type='text' name='uicq' value='<?=$table[0]["uicq"]?>'></td>
	</tr><tr>
		<td width='20%' valign='top'>About</td><td><textarea name='ubio'><?=$table[0]["ubio"]?></textarea></td>
	</tr><tr>
		<td width='20%' valign='top'>Avatar</td>
		<td>
			<select name='uava' size='1' onChange='previewPic(this)'>
<?
			$avdir = opendir(SL_ROOT_PATH."/images/avatars/");
			while( ($av = readdir($avdir) ) !== false )  {
				if( $av != ".." && $av != "." ) {
					if(!$table[0]["uava"]) {
						if($av == "No-Avatar.gif")
							print "<option value='" . $av . "' selected>" . substr($av,0,-4) . "</option>";
						else
							print "<option value='" . $av . "'>" . substr($av,0,-4) . "</option>";
					} else {
						if($av == $table[0]["uava"])
							print "<option value='" . $av . "' selected>" . substr($av,0,-4) . "</option>";
						else
							print "<option value='" . $av . "'>" . substr($av,0,-4) . "</option>";
					}
				}
			}
			closedir($avdir);
		
?>
			</select>
<?		if( !$table[0]["uava"] )
			print "<img name='previewpic' src='".SL_ROOT_URL."/images/avatars/No-Avatar.gif' width='60' height='60' border='0' align='top'>";
		else
			print "<img name='previewpic' src='".SL_ROOT_URL."/images/avatars/".$table[0]["uava"]."' width='60' height='60' border='0' align='top'>";
?>
		</td>
	</tr><tr>
		<td colspan='2'>
			<input type='submit' value='alter profile' name='submitprofile'>
		</td>
	<tr>
</table>
</form>