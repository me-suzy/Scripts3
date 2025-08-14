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
# $Id: payment_paypal.php,v 1.17 2002/04/22 17:10:30 mav Exp $
#
# PayPal CC processing module
#

require "../include/payment_method.php";

if ($REQUEST_METHOD=="POST" && !empty($payment_type)) {
#
# PayPal IPN Request
#
 exec ("./paypal_ipn.pl -rec_email=$receiver_email -item_name=$item_name -item_number=$item_number -quantity=$quantity -invoice=$invoice -custom=$custom -payment_status=$payment_status -pending_reason=$pending_reason -payment_date=$payment_date -payment_gross=$payment_gross -txn_id=$txn_id -txn_type=$txn_type -firstname=$firstname -lastname=$lastname -address_street=$address_street -address_state=$address_state -address_country=$address_country -address_zip=$address_zip -address_city=$address_city -address_status=$address_status -payer_email=$payer_email -payer_status=$payer_status -payment_type=$payment_type -notify_version=$notify_version -verify_sign=$verify_sign",$bill_output);
 list($bill_errorcode,$bill_message1) = explode(",",$bill_output[0]);
 if ($bill_errorcode==2)
 {
	header("Location: ../customer/error_message.php?error_ccprocessor_error");
	exit;
 }
 else
 {
	include "../include/payment_method.php";
	$orderids = func_place_order("$payment_method (PayPal IPN)", "P","");
	$_orderids = func_get_urlencoded_orderids ($orderids);
	#
	# Remove all from cart
	# 
	$cart="";
	header("Location: ../customer/cart.php?mode=order_message&orderids=$_orderids");
	exit;
 }
}


#
# Create 'Not finished' order entry
#
#$orderid = func_place_order($payment_method, "N","");


if($mode=="success" && $secureid && $secureid==$order_secureid) {
	$orderids = func_place_order($payment_method, "P","");
	$_orderids = func_get_urlencoded_orderids ($orderids);
#
# Remove all from cart
#
	$cart="";
	header("Location: $http_location/customer/cart.php?mode=order_message&orderids=$_orderids");
	exit;
}

#
# Below is the form for POST method
#
?>
<html>
<body onLoad="document.paypal_form.submit()">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name=paypal_form>
<input type=hidden name=cmd value="_ext-enter">
<input type=hidden name=redirect_cmd value="_xclick">
<input type=hidden name=first_name value="<? echo $userinfo["firstname"] ?>">
<input type=hidden name=last_name value="<? echo $userinfo["lastname"] ?>">
<input type=hidden name=address1 value="<? echo $userinfo["b_address"] ?>">
<input type=hidden name=city value="<? echo $userinfo["b_city"] ?>">
<input type=hidden name=state value="<? echo $userinfo["b_state"] ?>">
<input type=hidden name=zip value="<? echo $userinfo["b_zipcode"] ?>">
<input type=hidden name=day_phone_a value="<? echo $userinfo["phone"] ?>">

<input type=hidden name=business value="<? echo $paypal_account ?>">
<input type=hidden name=item_name value="<? echo $paypal_product ?>">
<input type=hidden name=amount value="<? echo $cart["total_cost"] ?>">
<input type=hidden name=return value="<? echo $http_location."/payment/payment_paypal.php?mode=success&secureid=$order_secureid" ?>">
<input type=hidden name=cancel_return value="<? echo $http_location."/customer/cart.php" ?>">
<input type=hidden name=image_url value="<? echo $paypal_logourl ?>">
</form>
<table width=100% height=100%>
<tr><td align=center valign=middle>Please wait while connecting to <b>PayPal</b> payment gateway...</td></tr>
</table>
</body>
</html>
