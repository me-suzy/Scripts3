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
# $Id: discount_coupons.php,v 1.5 2002/04/22 17:10:30 mav Exp $
#

#
# Check discount
# Discount coupons
# Status: A - active, D - disabled, U - used
#
function func_is_valid_coupon ($coupon) {
	global $cart, $products, $single_mode;

	$my_coupon = func_query_first("select * from discount_coupons where coupon='$coupon' and status='A' and expire>".time());
	if (!$my_coupon)
		return 1;
	if (!$single_mode) {
		$products_providers = func_get_products_providers ($products);
		if (!in_array ($my_coupon["provider"], $products_providers))
			return 2;
	}
	if ($my_coupon["coupon_type"] == 'free_ship')
		return 0;

	if ($my_coupon["productid"] > 0) {
		$found = false;

		foreach ($products as $value) {
			if ((!$single_mode) and ($my_coupon["provider"] != $value["provider"]))
				next;
			
			if ($value["productid"] == $my_coupon["productid"])
				$found = true;
		}
		
		return ($found ? 0 : 1);
	} elseif ($my_coupon["categoryid"] > 0) {
		$found = false;

		foreach ($products as $value) {
			if ((!$single_mode) and ($my_coupon["provider"] != $value["provider"]))
				next;
				
			if ($value["categoryid"] == $my_coupon["categoryid"])
				$found = true;
		}
		
		return ($found ? 0 : 1);
	} else {
		$total = 0;
		
		foreach ($products as $value) {
			if (($single_mode) or ((!$single_mode) and ($my_coupon["provider"] == $value["provider"])))
				$total += $value["price"]*$value["amount"];
		}

		if ($total < $my_coupon["minimum"])
			return 1;
		else
			return 0;
	}

	return 0;
}

if (func_is_valid_coupon($cart["discount_coupon"]) > 0)
	$cart["discount_coupon"]="";

if ($mode=="add_coupon" && $coupon) {
#
# Check if coupon is valid
#
	$my_coupon = func_is_valid_coupon($coupon);

	if ($my_coupon == 2) {
			$cart["discount_coupon"]="";
			header("Location: error_message.php?bad_coupon_provider");
			exit;
		}

#
# Add discount coupon
#
	if($my_coupon==0) {
		$cart["discount_coupon"]=$coupon;
	} else {
        $cart["discount_coupon"]="";
		header("Location: error_message.php?bad_coupon");
		exit;
	}
	header("Location: cart.php");
	exit;
}
elseif ($mode=="unset_coupons") {
	$cart["discount_coupon"]="";
	header("Location: cart.php");
	exit;
}

?>
