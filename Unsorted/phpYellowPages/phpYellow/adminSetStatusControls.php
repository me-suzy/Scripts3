<?php echo"\n\n";?>
<!-- START OF adminSetStatusControls.php -->
<form name="adminStatusControlsForm" method="post" action="adminresult.php" onsubmit="popup()" target="popup">
<table align="right" bgcolor="silver" cellpadding=3 border=3>
<tr><th>Set Status</th></tr>

<tr><td>
<select name="status" size=3>
<option value="approved"<?php if($yfstatus=="approved"){echo" SELECTED";}?>>approved</option>
<option value="pending"<?php if($yfstatus=="pending"){echo" SELECTED";}?>>pending</option>
<option value="expired"<?php if($yfstatus=="expired"){echo" SELECTED";}?>>expired</option>
</select><br>
<input type="submit" name="submit" value="Process" class="input">
<input type="hidden" name="goal" value="Update Record Status">
<input type="hidden" name="yps" value="<?php echo $yfypsid;?>">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="expirationLength" value="<?php echo $yfexpires;?>">
<input type="hidden" name="rank" value="<?php echo $yfrank;?>">
<input type="hidden" name="formuser" value="<?php print($formuser);?>">
<input type="hidden" name="formpassword" value="<?php print($formpassword);?>">
</td></tr></table>
</form>
<!-- END OF adminSetStatusControls.php -->