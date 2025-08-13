<?
$amount = $total;
$amount = str_replace (',', '', $amount);
?>
<script>
<!--
function wait(delay){
string="pauseforalert("+delay+");";
setTimeout(string,delay);
}
function pauseforalert(delay){
document.form1.submit();
}
// -->
</script>
<body onload="wait(2000);">
<form method="post" action="https://www.eway.com.au/gateway/payment.asp" name=form1 method=post>
<input type="hidden" name="ewayCustomerID" value="<?php echo $ewayCustomerID?>">
<input type="hidden" name="ewayTotalAmount" value="<?php echo $amount?>">
<input type="hidden" name="ewayCustomerFirstName" value="<?php echo $name?>">
<input type="hidden" name="ewayCustomerLastName" value=" ">
<input type="hidden" name="ewayCustomerEmail" value="<?php echo $email?>">
<input type="hidden" name="ewayCustomerAddress" value="<?php echo $address?>">
<input type="hidden" name="ewayCustomerPostcode" value="<?php echo $postcode0?>">
<input type="hidden" name="ewayCustomerInvoiceDescription" value="Shopping%20Cart%20Contents%20from%20<?php echo $SERVER_NAME?>">
<input type="hidden" name="ewayCustomerInvoiceRef" value="<?php echo $order_id?>">
<input type="hidden" name="ewayURL" value="<?php echo $ewayRURL?>">
<input type="hidden" name="ewaySiteTitle" value="<?php echo $SERVER_NAME?>">
<input type=hidden name=form1 value=Submit>
</form>