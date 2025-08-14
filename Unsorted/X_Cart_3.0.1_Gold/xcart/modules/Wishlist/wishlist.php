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
# $Id: wishlist.php,v 1.4 2002/05/20 06:55:19 lucky Exp $
#

if ($mode=="add2wl" && $productid) {
#
# Add to wish list
#
        db_query("insert into wishlist (login, productid, amount) values ('$login','$productid','$amount')");
        header("Location: cart.php?mode=wishlist");
}
elseif ($mode=="wldelete" && $productid) {
#
# Delete from wish list
#
        db_query("delete from wishlist where login='$login' and productid='$productid'");
        header("Location: cart.php?mode=wishlist");
        exit;
}
elseif ($mode=="wlclear") {
#
# Clear wish list
#
        db_query("delete from wishlist where login='$login'");
        header("Location: cart.php?mode=wishlist");
        exit;
}
elseif ($mode=="wishlist") {
#
# Obtain wishlist from database
#
	$wl_data = func_query("select productid, amount from wishlist where login='$login'");

	if($wl_data)
		foreach($wl_data as $wl_data_entry) {
				$amount = $wl_data_entry["amount"];
                $products_array = func_query_first("select products.*, pricing.price, $amount as amount, $amount*pricing.price as total from products, pricing where pricing.productid=products.productid and pricing.quantity<=$amount and products.productid='$wl_data_entry[productid]' and products.avail>='$amount' order by pricing.quantity desc");
#
# Get delivery options and add to WL products array
#
                if($products_array) {
					$products_array["delivery"]=func_select_product_delivery($wl_data_entry["productid"]);
					$wl_products[] = $products_array;
				}
        }

		$smarty->assign("wl_products",$wl_products);
	    $smarty->assign("main","wishlist");
} elseif (!empty($login) and ($mode=='send_friend') and (!empty ($friend_email))
) {
	$product = func_select_product($productid, $user_account['membership']);
	$mail_smarty->assign ("product", $product);
	$mail_smarty->assign ("userinfo", $userinfo);
	func_send_mail ($friend_email, "mail/wishlist_send2friend_subj.tpl", "mail/wishlist_send2friend.tpl", $userinfo["email"], false);
	header ("Location: cart.php?mode=wishlist&send2friend=success");
	exit;
}

?>
