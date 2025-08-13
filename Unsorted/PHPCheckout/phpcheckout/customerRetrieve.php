<!-- 	start of customerRetrieve.php -->
<?php $customerid = !isset($customerid)?NULL:$_REQUEST['customerid']; // initialize or get customerid ?>

<?php /* this form is used to prompt admin for a unique record number 
         This key number is then loaded into an editable form */ ?>
<form name="obtainKeyForm" method="post" action="workWithCustomer.php" onsubmit="return checkForm(this)">
	<input type="hidden" name="task" value="<?php echo $task;?>">
<table class="form" align="center">
<tr><th colspan=2><?php echo $task;?></th></tr>

<tr><td>&nbsp;#&nbsp;</td><td><input type="text" name="customerid" value="<?php echo $customerid;?>" size=20></td></tr>

<tr><td>&nbsp;</td>
<td><input class="submit" type="submit" name="submit" value="<?php echo $task;?>"></td></tr>
</table>
<script language="Javascript">
<!-- hide This script sets the focus to the data entry text box for easy input of the key number
window.document.obtainKeyForm.customerid.focus();
-->
</script>
</form>
<!-- end of	customerRetrieve.php -->

