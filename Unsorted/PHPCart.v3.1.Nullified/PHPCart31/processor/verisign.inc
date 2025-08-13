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
<form method="POST" action="https://payflowlink.verisign.com/payflowlink.cfm" name="form1" method="post">
<input type="hidden" name="LOGIN" value="<?php echo $verisign_login?>">
<input type="hidden" name="PARTNER" value="VeriSign">
<input type="hidden" name="DESCRIPTION" value="Shopping Basket Content">
<input type="hidden" name="NAMETOSHIP" value="<?php echo $name?>" size="30" maxlength="60"> 
<input type="hidden" name="ADDRESSTOSHIP" value="<?php echo $address?>" size="30" maxlength="60"> 
<input type="hidden" name="CITYTOSHIP" value=" " size="30" maxlength="60"> 
<input type="hidden" name="STATETOSHIP" value=" " size="30" maxlength="60"> 
<input type="hidden" name="ZIPTOSHIP" value="<?php echo $postcode0?>" size="30" maxlength="60"> 
<input type="hidden" name="EMAILTOSHIP" value="<?php echo $email?>" size="30" maxlength="60"> 
<input type="hidden" name="AMOUNT" value="<?php echo $total?>">
<input type="hidden" name="TYPE" value="S">
<input type="hidden" name="TEST_TRAN" value="0">
<input type="hidden" name="EMAILCUSTOMER" value="1">
<input type="hidden" name="ORDERFORM" value="1">
<input type="hidden" name="SHOWCONFIRM" value="1">
<input type="hidden" name="RETURNMETHOD" value="1">
<input type="hidden" name="RETURNURL" value="<?php echo $verisign_returnurl?>">
<input type="hidden" name="DISPNAME" value="<?php echo $companyName?>">
<input type="hidden" name="BACKCOLOR" value="ffffff">
<input type="hidden" name="RECEIPTHEADER" value="We have successfully received your order. If you have any questions regarding your order, you may email us <?php echo $salesEmail?>.">
<input type="hidden" name="RECEIPTFOOTER" value="Thank you for shopping with <?php echo $companyName?>">
<input type="hidden" name="BUTTONTEXT" value="Return to <?php echo $companyName?>">
<input type="hidden" name="JUSTIFY" value="1">
<input type=hidden name=form1 value=Submit>
</form>


<CENTER><FONT FACE="Verdana" SIZE=2 COLOR="<?php echo $TextColor?>">Loading Payment Gateway...</FONT></CENTER>
