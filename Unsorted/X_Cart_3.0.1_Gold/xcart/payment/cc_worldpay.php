<?
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
# $Id: cc_worldpay.php,v 1.7 2002/05/29 06:24:09 lucky Exp $
#

# this line protect us from empty orders
$bill_error=true; 

#
# Finish transaction
#

if ($REQUEST_METHOD == "POST")
{
	if (!empty($transStatus))
	{
		session_id($cartId);
		session_register("cart");
		session_register("order_secureid");
		session_register("userinfo");

		#$fp=fopen("log/wp.log","a");
		#fputs($fp, "-------------------------------------");
		#while (list ($key, $value) = each($HTTP_POST_VARS))
		#{
	#		fputs($fp, "$key=$value\n");
	#	}
	#	fclose($fp);
	}
	if ($transStatus && $transStatus == "C")
	{
		header("Location: ../customer/error_message.php?error_ccprocessor_error");
		exit;
	}
	elseif($transStatus && $transStatus == "Y")
	{
#		$order_secureid=$cartId;
		include "../include/payment_method.php";
		$orderids = func_place_order("Credit Card (WorldPay)", "P","");
		$_orderids = func_get_urlencoded_orderids ($orderids);

		#
		# Remove all from cart
		#
		$cart="";
		header("Location: ../customer/cart.php?mode=order_message&orderids=$_orderids");

		exit;
	}
}

$worldpay_login = $module_params["param01"];
$worldpay_currency = $module_params["param02"];
$worldpay_workmode = $module_params["param03"];
session_register("order_secureid");
?>
<html>
<body onLoad="document.process.submit();">
  <form action="https://select.worldpay.com/wcc/purchase" method=POST name=process>
	 <input type=hidden name=instId value="<?echo $worldpay_login;?>">
	 <input type=hidden name=cartId value="<?echo session_id();?>">
	 <input type=hidden name=amount value="<?echo $cart["total_cost"];?>">
	 <input type=hidden name=currency value="<?echo $worldpay_currency;?>">
	 <input type=hidden name=testMode value="<?echo $worldpay_workmode;?>">
	 <input type=hidden name=name value="<?echo addslashes($userinfo["firstname"])." ". addslashes($userinfo["lastname"]);?>">
	 <input type=hidden name=tel value="<?echo $userinfo["phone"];?>">
	 <input type=hidden name=email value="<?echo $userinfo["email"];?>">
	 <input type=hidden name=desc value="<?
					$cart_products = func_products_in_cart($cart, $userinfo["membership"]);
					while (list($key, $cart_product) = each($cart_products))
					{
						$desc.=$cart_product["product"];
					}
					echo $desc;
					?>">
	 <input type=hidden name=address value="<?echo addslashes($userinfo["b_address"])." ".$userinfo["b_city"];?>">
	 <input type=hidden name=postcode value="<?echo $userinfo["b_zipcode"];?>">
	 <input type=hidden name=country value="<?echo $userinfo["b_country"];?>">
	</form>
	<table width=100% height=100%>
	 <tr><td align=center valign=middle>Please wait while connecting to <b>worldpay.com</b> payment gateway...</td></tr>
	</table>
 </body>
</html>
<? exit; ?>
