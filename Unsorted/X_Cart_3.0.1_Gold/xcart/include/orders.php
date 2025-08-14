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
# $Id: orders.php,v 1.14 2002/05/31 13:30:15 zorg Exp $
#

#
# Generate timestamp for first day of current month
#
$start_date = mktime (0,0,0,date("m",time()),1,date("Y",time()));
$end_date = time();

if($QUERY_STRING) {
#
# Set % as reserved name for list mode of search script
# Substring condition
#
    $substring_condition = ($substring==""?"":" and orders.login='$substring'");

        $status_condition = ($status?" and orders.status='$status'":"");
        $orderid_condition = ($orderid?" and orders.orderid='$orderid'":"");

#
# Generate timestamps and assign then to smarty
#
        if($StartMonth) {
                $start_date=mktime(0,0,0,$StartMonth,$StartDay,$StartYear);
                $end_date=mktime(23,59,59,$EndMonth,$EndDay,$EndYear);
        }

        $orders = func_query("select orders.*, order_details.provider from orders, order_details where orders.orderid=order_details.orderid ".($provider?" and order_details.provider='$provider'":"")." and orders.date>=$start_date and orders.date<=$end_date".$substring_condition.$status_condition.$orderid_condition." order by orders.date desc");

		if(!$provider)
			$orders = array_merge($orders, func_query("select orders.*, giftcerts.gcid, giftcerts.purchaser, giftcerts.recipient, giftcerts.message, giftcerts.amount, giftcerts.debit, giftcerts.add_date, giftcerts.status as gc_status FROM orders, giftcerts where orders.orderid=giftcerts.orderid and orders.date>=$start_date and orders.date<=$end_date ".$substring_condition.$status_condition.$orderid_condition." order by orders.date desc"));

#
# Do array multisort by orderid
#
		$orderids = array();

		if(!empty($orders)) {
			foreach($orders as $order)
				$orderids[]=$order["orderid"];

			array_multisort($orderids, SORT_DESC, $orders);
		}
#
# This routine removes duplications from $orders array
#
	$orders_string = array();
	if(!empty($orders))
		foreach($orders as $key=>$uniq_order)
			if ($uniq_order["orderid"] != $orders[$key+1]["orderid"]) {
				$new_orders[]=$uniq_order;
				$orders_string [] = $uniq_order["orderid"];
			}

	$orders=$new_orders;

#
# Make orders_full array, that contains detailed information about orders
#
    $orders_full = array ();
 
    if ($orders) {
        foreach ($orders as $key=>$value) {
            $products_result = func_query ("SELECT * FROM order_details WHERE orderid='$value[orderid]'");
            if ($products_result) {
                foreach ($products_result as $subkey=>$subvalue) {
                    $result = array_merge ($value, $subvalue);
                    $product_result = func_query_first ("SELECT * FROM products WHERE productid='$subvalue[productid]'");
                    if ($product_result)
                        $result = array_merge ($result, $product_result);
 
                    $orders_full [] = $result;
                }
            }
 
            $gifts_result = func_query_first ("SELECT * FROM giftcerts WHERE orderid='$value[orderid]'");
 
            if ($gifts_result) {
                $result = array_merge ($value, $gifts_result);
                $orders_full [] = $result;
            }
        }
    }

	if ($orders_string)
		$smarty->assign ("orders_string", urlencode(implode (",", $orders_string)));
	$smarty->assign("orders",$orders);
	$smarty->assign("items_count",count($orders));
}

$smarty->assign("orders_full", $orders_full);

$smarty->assign("start_date",$start_date);
$smarty->assign("end_date",$end_date);
$smarty->assign("main","orders");

?>
