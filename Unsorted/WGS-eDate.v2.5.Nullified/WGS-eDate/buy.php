<?php
include("member.php");

if (!$type) exit;

$amount=${"mem_".$type."_cost"};

$credits[silver]=1000;
$credits[gold]=2000;
$credits[platinum]=3000;

$credits=$credits[$type];

$db = c();

$msg=" Paypal Payment :<BR> FOR : Rank $credits ($type) <br> FROM : $tm0[fname] $tm0[lname] ($tm0[login] [$tm0[email]]) <br> TO : $ADMIN_PAYPAL <br> USD $amount <br> ";

q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$pmode[id]', 'Payment : $tm0[login] [$tm0[email]], $amount (Rank : $credits - $type)', '$msg', 'payment', '$tm0[id]', '$credits', '1','".strtotime(date("d M Y H:i:s"))."')");


?>
<blockquote>
<blockquote>
<B>Buy membership using PAYPAL</B><BR>
<p>
<br>Just pay $<?php echo $amount;?> to '<?php echo $ADMIN_PAYPAL;?>' through <A href=http://www.paypal.com>Paypal</A> to be promoted to <?php echo $type;?> member :
<br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $ADMIN_PAYPAL;?>">
<input type="hidden" name="item_name" value="Rank <?php echo $credits;?>">
<input type="hidden" name="item_number" value="<?php echo $credits;?>">
<input type="hidden" name="custom" value="<?php echo $auth;?>">
<input type="hidden" name="amount" value="<?php echo $amount;?>">
<input type="image" src="http://images.paypal.com/images/x-click-but01.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
</blockquote></blockquote>

<?php include "_footer.php"; ?>