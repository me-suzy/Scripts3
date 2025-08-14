<?
set_time_limit(100);
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: cc_processusa.php,v 1.11 2002/05/28 14:01:58 verbic Exp $
#

# this line protect us from empty orders
$bill_error=true; 

#
# Finish transaction
#
if (($REQUEST_METHOD == "POST") && (!empty($ret_val)))
{

	require "../smarty.php";
	require "../config.php";
	session_id("$PHPSESSID");
    session_start();
    session_register("cart");
    session_register("order_secureid");
	session_register("login");
	session_register("login_type");
	
	$userinfo = func_userinfo($login, $login_type);

	#	$fp=fopen("log/wp.log","a");
    #    fputs($fp, "-------------------------------------");
    #    while (list ($key, $value) = each($HTTP_POST_VARS))
    #    {
    #       fputs($fp, "$key=$value\n");
    #   }
    #   fclose($fp);
    if (empty($PHPSESSID) ||  empty($cart) || empty($userinfo) || (!empty($err)) || ($order_secureid != $ret_val))
    {
		?>
		<HEAD>
		<TITLE>Unable to process your order</TITLE>
		</HEAD>
		<BODY>
		<CENTER>
		<H3>There was an error while processing your order:</H3>
		<BR>
		<BR>
		<H4><?echo $err;?></H4>
		<A href=<?echo "$base_addr/customer/cart.php";?>?PHPSESSID=<?echo $PHPSESSID;?>>Please click this link to return to to the shop and revise your order data</A>	
		<BR>
		</CENTER>
		</BODY>
		<?
		exit;
    }
    else
    {
#       $order_secureid=$cartId;
        include "../include/payment_method.php";
		$orderids = func_place_order("$payment_method (ProcessUSA)", "P","");
	    $_orderids = func_get_urlencoded_orderids ($orderids);

        #
        # Remove all from cart
        #
        $cart="";
		//Relocate customer to x-cart site
        header("Location: $base_addr/customer/cart.php?mode=order_message&orderids=$_orderids&PHPSESSID=$PHPSESSID");
        
    }
exit();
}

?>
<html>
<body onLoad="document.process.submit();">
  <form action="https://secure.paymentclearing.com/cgi-bin/rc/ord.cgi" method=POST name=process>
	 
	 <input type=hidden name=vendor_id value="<?echo $module_params["param01"];?>">
	 <input type=hidden name=home_page value="<?echo $module_params["param02"];?>">
	 <input type=hidden name=ret_addr value="<?echo $module_params["param03"];?>">
	<?
	session_register("userinfo");
$symbolic_months = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "November", "11" => "October", "12" => "December");
	$bill_exp_month = $symbolic_months[substr($userinfo["card_expire"],0,2)];
	$bill_exp_year = "20".substr($userinfo["card_expire"],2,2);	
	$prod_list = "";
	$order_txt = "Ordered products:";
    $cart_products = func_products_in_cart($cart, $userinfo["membership"]);
    while(list($key,$cart_product) = each($cart_products))
		$order_txt .= " ".$cart_product["product"]." Price: ".$cart_product["price"]." Qty: ".$cart_product["amount"]."; ";

	if ($cart["discount"] != 0) 
		$order_txt .= " Discount: ".$cart["discount"].";";

	if ($cart["coupon_discount"] != 0) 
		$order_txt .= " Coupon discount: ".$cart["coupon_discount"].";";
		
	if ($cart["tax_cost"] != 0) 
		$order_txt .= " Shipping charge: ".$cart["shipping_cost"].";";

	if ($cart["tax_cost"] != 0) 
		$order_txt .= " Tax cost: ".$cart["tax_cost"].";";

	$order_txt .= " Total: ".$cart["total_cost"];
	
	?>	
	<input type=hidden name="1_desc" value="<?echo $order_txt;?>">
	<input type=hidden name="1_cost" value="<?echo $cart["total_cost"];?>">
	<input type=hidden name="1_qty" value="1">
	
	 <input type=hidden name=ccnum value="<?echo $userinfo["card_number"];?>">
	 <input type=hidden name=ccmo value="<?echo $bill_exp_month;?>">
	 <input type=hidden name=ccyr value="<?echo $bill_exp_year;?>">
	 <input type=hidden name=first_name value="<?echo addslashes($userinfo["firstname"]);?>">
	 <input type=hidden name=last_name value="<?echo addslashes($userinfo["lastname"]);?>">
	 <input type=hidden name=phone value="<?echo $userinfo["phone"];?>">
	 <input type=hidden name=email value="<?echo $userinfo["email"];?>">
	 <input type=hidden name=address value="<?echo addslashes($userinfo["b_address"]);?>">
	 <input type=hidden name=city value="<?echo $userinfo["b_city"];?>">
	 <input type=hidden name=state value="<?echo ($userinfo["b_state"]?$userinfo["b_state"]:"n/a");?>">
	 <input type=hidden name=zip value="<?echo $userinfo["b_zipcode"];?>">
	 <input type=hidden name=country value="<?echo $userinfo["b_country"];?>">

	 <input type=hidden name=passback value=ret_val>
	 <input type=hidden name=ret_val value="<?echo $order_secureid;?>">
	 <input type=hidden name=passback value=PHPSESSID>
	 <input type=hidden name=PHPSESSID value="<?echo $PHPSESSID;?>">
	 <input type=hidden name=passback value=base_addr>
	 <input type=hidden name=base_addr value="<?if (substr($HTTP_REFERER, 0, 8) == "https://") echo $https_location; else echo $http_location;?>">

	 <input type=hidden name=ret_mode value="post">
	 <input type="hidden" name="post_back_on_error" value="1">
	</form>
	<table width=100% height=100%>
	 <tr><td align=center valign=middle>Please wait while connecting to <b>ProcessUSA</b> payment gateway...</td></tr>
	</table>
 </body>
</html>
<? exit; ?>
