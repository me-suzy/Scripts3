<table>
<form action="setup.php" method="post">
<input type="hidden" name="Step" value="1">
<tr><td colspan="2" align="center"><b>Fill next field to configure Ettica PPC Search Engine</b><br><br></td></tr>
<tr><td>Login of admin:   </td><td><input type="text" name="Login"    value="<?=$StrALogin    ; ?>"></td></tr>
<tr><td>Password of admin:</td><td><input type="text" name="Password" value="<?=$StrAPassword ; ?>"></td></tr>
<tr><td>Database Host:             </td><td><input type="text" name="Host"     value="<?=$StrHost      ; ?>"></td></tr>
<tr><td>Database Login:            </td><td><input type="text" name="Login"    value="<?=$StrLogin     ; ?>"></td></tr>
<tr><td>Database Password:         </td><td><input type="text" name="Password" value="<?=$StrPassword  ; ?>"></td></tr>
<tr><td>Database Name:         </td><td><input type="text" name="Database" value="<?=$StrDatabase  ; ?>"></td></tr>
<tr><td>Site URL:         </td><td><input type="text" name="SiteUrl"  value="<?=$StrSiteUrl   ; ?>">&nbsp; &nbsp; Example: http://domain/path/ </td></tr>
<tr><td>&nbsp;</td><td><input type="checkbox" name="cbcr" checked>&nbsp; &nbsp; Create new database</td></tr>
<tr><td>&nbsp;</td><td><input type="checkbox" name="cbcl">&nbsp; &nbsp; Drop database before creating</td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Apply"></td></tr>
</form>
</table>
