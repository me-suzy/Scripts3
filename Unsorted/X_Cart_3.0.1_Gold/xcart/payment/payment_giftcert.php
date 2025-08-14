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
# $Id: payment_giftcert.php,v 1.4 2002/04/22 17:10:30 mav Exp $
#
# Gift certificate processing payment module
#

require "../include/payment_method.php";

#
# Generate $order_notes
#
$order_details="GC: $gcid";

$gc = func_query_first("SELECT * FROM giftcerts WHERE gcid='$gcid' AND status='A'");
if (empty($gc))
{
#
# Non existing Gift certificate
#
	header("Location: ../customer/error_message.php?error_giftcert_notfound");
	exit;
}
else {
#
# Gift certificate exists
#
	if ($gc["debit"]>=$cart["total_cost"]) {
#
# Process order
# $payment_method is variable which ss POSTed from checkout.tpl
#
		$orderids = func_place_order($payment_method,"P",$order_details);
		$_orderids = func_get_urlencoded_orderids ($orderids);
		db_query("UPDATE giftcerts SET debit=debit-$cart[total_cost] WHERE gcid='$gcid'");
#
# Remove all from cart
#
		$cart="";
		header("Location: $http_location/customer/cart.php?mode=order_message&orderids=$_orderids");
		exit;
	} 
	else {
			#
			# Not enough money
			#
			header("Location: ../customer/error_message.php?error_giftcert_notenough");
			exit;
	}
}
?>
