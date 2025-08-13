

<!-- start of adminConfirmDeleteForm.php -->
<table class="form">
<form name="adminConfirmDeleteForm" method="post" action="adminresult.php">
<tr><th>Confirm Delete</th></tr>
<tr><td>Yes, confirm delete of <br>
<input type="radio" name="recordsToDelete" value="One" CHECKED> category #<?php echo $ckey;?>, ONE record only.<br>
<input type="radio" name="recordsToDelete" value="All"> contact #<?php echo $yps;?>, AND all related category records<br>
<input type="submit" name="submit" value="<?php echo $goal;?>" class="input"> 
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="ckey" value="<?php echo $ckey;?>">
<input type="hidden" name="yps" value="<?php echo $yps;?>">
<input type="hidden" name="formuser" value="<?php echo $formuser;?>">
<input type="hidden" name="formpassword" value="<?php echo $formpassword;?>">
</form></td></tr>
</table>
<!-- end of adminConfirmDeleteForm.php -->
