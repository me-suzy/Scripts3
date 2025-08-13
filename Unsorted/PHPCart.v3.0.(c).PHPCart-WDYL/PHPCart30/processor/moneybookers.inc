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
<form action=https://www.moneybookers.com/app/send.pl name=form1 method=post>
<input type="hidden" name="merchant_payment" value="1">
<input type="hidden" name="merchant_id" value="<?php echo $moneyb_merchant_id?>">
<input type="hidden" name="amount" value="<?php echo $total?>">
<input type="hidden" name="currency" value="<?php echo $moneyb_currency?>">
<input type="hidden" name="transaction_id" value="<?php echo $order_id?>">
<input type="hidden" name="merchant_fields" value="email">
<input type="hidden" name="email" value="<?php echo $email?>">
<input type="hidden" name="return_url" value="<?php echo $moneyb_return_url?>">
<input type="hidden" name="status_url" value="<?php echo $moneyb_status_url?>">
<input type="hidden" name="cancel_url" value="<?php echo $moneyb_cancel_url?>">
<input type=hidden name=form1 value=Submit>
</form>
<CENTER><FONT FACE="Verdana" SIZE=2 COLOR="<?php echo $TextColor?>">Loading Payment Gateway...</FONT></CENTER>
