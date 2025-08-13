<script>window.setTimeout('changeurl();',2000); function changeurl(){window.location='https://secure.authorize.net/gateway/transact.dll?x_login=<?php echo $sid?>&x_amount=<?php echo $total?>&x_invoice_num=<?php echo $order_id?>&x_email=<?php echo $email?>'}</script>
<CENTER><FONT FACE="Verdana" SIZE=2 COLOR="<?php echo $TextColor?>">Loading Payment Gateway...</FONT></CENTER>


