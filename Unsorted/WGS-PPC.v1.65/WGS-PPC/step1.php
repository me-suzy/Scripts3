<table width="300">
<form action="setup.php" method="post">
<input type="hidden" name="Step" value="2">
<input type="hidden" name="Host"     value="<?=$StrHost     ; ?>">
<input type="hidden" name="Login"    value="<?=$StrLogin    ; ?>">
<input type="hidden" name="Password" value="<?=$StrPassword ; ?>">
<input type="hidden" name="Database" value="<?=$StrDatabase ; ?>">

<tr><td><b>Database Host:     </b></td><td><?=$StrHost     ; ?></td></tr>
<tr><td><b>Database Login:    </b></td><td><?=$StrLogin    ; ?></td></tr>
<tr><td><b>Database Password: </b></td><td><?=$StrPassword ; ?></td></tr>
<tr><td><b>Database Name: </b></td><td><?=$StrDatabase ; ?></td></tr>
<tr><td colspan="2" align="center"><br><br><b>Is it correct info?</b><br><br></td></tr>
<tr><td colspan="2" align="center"><input type="reset" value="No" onclick="history.back()"> <input type="submit" value="Yes"></td></tr>
</form>
</table>
