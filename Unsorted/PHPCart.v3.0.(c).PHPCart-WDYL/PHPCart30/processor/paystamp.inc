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
<form action=http://www.paystamp.com/creditcards.asp name=form1 method=post>
<input type=hidden name=affid value=<?php echo $affid?>>
<input type=hidden name=transaction_amount value=<?php echo $currency?><?php echo $total?>>
<input type=hidden name=description value=Shopping%20Cart%20Contents>
<input type=hidden name=reference value=<?php echo $order_id?>>
<input type=hidden name=returnurl value=<?php echo $returnurl?>>
<input type=hidden name=email value=<?php echo $email?>
<input type=hidden name=address value=<?php echo $address?>
<input type=hidden name=telephone value=<?php echo $telephone?>
<input type=hidden name=postcode value=<?php echo $postcode0?>
<input type=hidden name=form1 value=Submit>
</form>
<CENTER><FONT FACE="Verdana" SIZE=2 COLOR="<?php echo $TextColor?>">Loading Payment Gateway...</FONT></CENTER>
