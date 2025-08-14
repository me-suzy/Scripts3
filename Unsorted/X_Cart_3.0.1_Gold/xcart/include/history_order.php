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
# $Id: history_order.php,v 1.5 2002/04/22 17:10:29 mav Exp $
#
# Collect infos about ordered products
#

if ($mode == "invoice" or $mode == "label") {
	header ("Content-Type: text/plain");
	
	$orders = explode (",", $orderid);
	if ($orders) {
		$orders_data = array();
		foreach ($orders as $orderid) {
			$order_data = func_order_data($orderid);
			$order = $order_data["order"];
			$customer = $order_data["userinfo"];
			$products = $order_data["products"];
			$giftcerts = $order_data["giftcerts"];
			$orders_data [] = array ("order" => $order, "customer" => $customer, "products" => $products, "giftcerts" => $giftcerts);
		}
		$smarty->assign ("orders_data", $orders_data);
		if ($mode == "invoice")
			$smarty->display("main/order_invoice_print.tpl");
		elseif ($mode == "label")
			$smarty->display("main/order_labels_print.tpl");
	}

	exit;
} else {
	$order_data = func_order_data($orderid);
	$smarty->assign("order",$order_data["order"]);
	$smarty->assign("customer",$order_data["userinfo"]);
	$smarty->assign("products",$order_data["products"]);
	$smarty->assign("giftcerts",$order_data["giftcerts"]);
}
?>
