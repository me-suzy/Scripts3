
<!-- START OF customerBackNextControl.php -->

<form name="backNextControlsForm" method="post" action="workWithCustomer.php">
<input type="hidden" name="task" value="<?php echo $task;?>">
<input type="hidden" name="offset" value="<?php echo $offset;?>">
<input type="hidden" name="formuser" value="<?php print($formuser);?>">
<input type="hidden" name="formpassword" value="<?php print($formpassword);?>">
<table bgcolor="silver" cellpadding="5" border="3" align="center"><tr><td>

<?php 
echo"<input class=\"input\" type=\"button\" name=\"goBack\" value=\"&lt;= Back\" onclick=\"history.back(1)\">";
if($customerid > $recordsPerPage):?>
<input class="input" type="submit" name="submit" value="Next =>"><?php endif;?>

</td></tr></table>
</form>

<!-- END OF customerBackNextControl.php -->