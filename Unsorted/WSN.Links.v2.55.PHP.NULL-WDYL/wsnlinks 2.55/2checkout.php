<?php
require 'start.php';
while(list($key, $value) = each($_REQUEST)) 
{
 $$key = urlencode(addslashes($value));
} 
$linkid = $_COOKIE['sponsorlink'];
$payer_email = $email;
$item_number = $product_id;
$ip = $_SERVER['REMOTE_ADDR'];
if ($linkid == '') 
{
 $getid = $db->select('id', 'linkstable', "ip='buy $ip'", 'ORDER BY time DESC', '');
 $linkid = $db->rowitem($getid);
 $db->update('linkstable', "ip", "$ip", "ip='buy $ip'"); // set back to norm
}
$payment_gross = $quantity * $settings->sponsorcharge;
if ($item_number == $settings->sponsoritem)
{
 if ($settings->sponsortype == 'promotion')
 {
  $thelink = new onelink('id', $linkid);
  $thelink->type = $settings->sponsorlinktype;
  $thelink->funds += $payment_gross;
  $thelink->update('funds,type');
 }
 else if ($settings->sponsortype == 'PPC')
 {
  $themem = new member('id', $buyerid);
  $themem->funds += $payment_gross;
  $themem->update('funds');
 }
}
$sid = $settings->checkoutnum;
$merchant_order_id = $linkid;
header("Location: https://www.2checkout.com/cgi-bin/ccbuyers/purchase.2c?sid=$sid&product_id=$product_id&quantity=$quantity&merchant_order_id=$merchant_order_id");
?>