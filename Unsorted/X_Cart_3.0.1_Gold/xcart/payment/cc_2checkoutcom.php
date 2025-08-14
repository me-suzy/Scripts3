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
# $Id: cc_2checkoutcom.php,v 1.5 2002/04/22 17:10:30 mav Exp $
#

session_register("order_secureid");

# this line protect us from empty orders
$bill_error=true; 

#
# Finish transaction
#
if ($x_response_code && $x_response_code!=1)
{
    header("Location: ../customer/error_message.php?error_ccprocessor_error");
	exit;
}
elseif($x_response_code && $secureid && $secureid=$order_secureid)
{
	include "../include/payment_method.php";
	$orderids = func_place_order("$payment_method (2Checkout)", "P","");
	$_orderids = func_get_urlencoded_orderids ($orderids);
#
# Remove all from cart
#
	$cart="";
	header("Location: ../customer/cart.php?mode=order_message&orderids=$_orderids");
	exit;
}

$checkout_login = $module_params["param01"];
	 
?>
<html>
<body onLoad="document.process.submit();">
  <form action="https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c" method=POST name=process>
	 <input type=hidden name=x_login value="<?echo $checkout_login;?>">
	 <input type=hidden name=x_amount value="<?echo $cart["total_cost"];?>">
	 <input type=hidden name=x_Card_Num value="<?echo $userinfo["card_number"];?>">
	 <input type=hidden name=x_Exp_Date value="<?echo $userinfo["card_expire"];?>">
	 <input type=hidden name=x_invoice_num value="<?echo $order_secureid;?>">
	 <input type=hidden name=x_First_Name value="<?echo addslashes($userinfo["firstname"]);?>">
	 <input type=hidden name=x_Last_Name value="<?echo addslashes($userinfo["lastname"]);?>">
	 <input type=hidden name=x_Phone value="<?echo $userinfo["phone"];?>">
	 <input type=hidden name=x_Email value="<?echo $userinfo["email"];?>">
	 <input type=hidden name=x_Address value="<?echo addslashes($userinfo["b_address"]);?>">
	 <input type=hidden name=x_City value="<?echo $userinfo["b_city"];?>">
	 <input type=hidden name=x_State value="<?echo ($userinfo["b_state"]?$userinfo["b_state"]:"n/a");?>">
	 <input type=hidden name=x_Zip value="<?echo $userinfo["b_zipcode"];?>">
	 <input type=hidden name=x_Country value="<?echo $userinfo["b_country"];?>">
	 <input type=hidden name=secureid value="<?echo $order_secureid;?>">
	 <input type=hidden name=payment_method value="<?echo $payment_method;?>">
	</form>
	<table width=100% height=100%>
	 <tr><td align=center valign=middle>Please wait while connecting to <b>2checkout.com</b> payment gateway...</td></tr>
	</table>
 </body>
</html>
<? exit; ?>
