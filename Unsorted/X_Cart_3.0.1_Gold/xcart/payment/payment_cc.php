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
# $Id: payment_cc.php,v 1.16 2002/06/05 11:06:42 verbic Exp $
#
# CC processing payment module
#

require "../include/payment_method.php";

if ($REQUEST_METHOD=="POST") {

	$order_details = "Card type: $card_type\nCard number: $card_number\nExp. date: $card_expire";
#
# Only logged users can submit orders
#
	if ($active_payment_processor) {
#
# Get active processor's data
#
    	$processor_data = func_query_first("select * from ccprocessors where module_name='$active_payment_processor'");
#
# Get module parameters
#
		$module_params = func_query_first("select * from ccprocessors where processor='".$processor_data["processor"]."'");

#
# This line includes cc_processor's code so that it
# executes inside _this_ script
#
       	require $processor_data["processor"];
#
# This code reports error and inserts order
#
    	if ($bill_error) $order_status="F"; else $order_status="P";
		if (!$store_cc) $order_details = "";
		$orderids = func_place_order("$payment_method (".$processor_data["module_name"].")",$order_status,$order_details);
		$_orderids = func_get_urlencoded_orderids ($orderids);

		if ($bill_error) {
        	header("Location: ../customer/error_message.php?error=$bill_error&bill_message=".urlencode($bill_message));
			exit;
		} else {
#
# If successful - Store CC number in database
#
			if ($store_cc)
				db_query("update customers set card_type='$card_type', card_number='$card_number', card_expire='$card_expire' where login='$login' and usertype='$login_type'");

#
# Unset cart
#
			$cart="";
			header("Location: ../customer/cart.php?mode=order_message&orderids=$_orderids");
			exit;
		}
	}
	else {
#
# If active_processor is empty do manual processing
#

		$orderids = func_place_order("Credit Card (manual processing)","Q",$order_details);
		$_orderids = func_get_urlencoded_orderids ($orderids);
		$cart="";

		header("Location: ../customer/cart.php?mode=order_message&orderids=$_orderids");
	}
}
	
?>
