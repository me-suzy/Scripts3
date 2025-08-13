<?
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.0                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Tested by            : WTN Team                                         //
// Packaged by          : WTN Team                                         //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
$amount = $total;
$amount = str_replace (',', '', $amount);

if (!empty($worldpay_demo)) {
$demo = "&testMode=100";
} else {
$demo = "";
}
?>
<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='https://select.worldpay.com/wcc/purchase?desc=Shopping%20Cart%20Contents&cartId=<?php echo $order_id?>&instId=<?php echo $instid?>&currency=<?php echo $worldpay_currency?>&amount=<?php echo $amount?>&email=<?php echo $email?>$demo'}</script>
<CENTER><FONT FACE="Verdana" SIZE=2 COLOR="<?php echo $TextColor?>">Loading Payment Gateway...</FONT></CENTER>



