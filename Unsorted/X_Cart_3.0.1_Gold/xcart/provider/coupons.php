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
# $Id: coupons.php,v 1.12 2002/04/25 12:41:03 zorg Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

include "../include/categories.php";

#
# Use this condition when single mode is disabled
#

$provider_condition=($single_mode?"":"and provider='$login'");

if ($REQUEST_METHOD=="POST") {
	while(list($key,$val)=each($HTTP_POST_VARS))
	{
#
# Update discount table
#
    	if (strstr($key,"-")) {
        	list($field,$coupon)=split("-",$key);
        	db_query("update discount_coupons set $field='$val' where coupon='$coupon' $provider_condition");
    	}
	}
#
# Add new coupon
#
    if ($coupon_new) {
		#
		# Generate timestamp
		#
    	$expire_new=mktime(0,0,0,$new_Month,$new_Day,$new_Year);

switch ($apply_to) {
	case '':
	case 'any':
		$productid_new=0;
		$categoryid_new=0;
		break;
	case 'product':
		$categoryid_new=0;
		break;
	case 'category':
		$productid_new=0;
		break;
}

		db_query("insert into discount_coupons (coupon, discount, coupon_type, minimum, times, expire, status, provider, productid, categoryid) values ('$coupon_new', '$discount_new', '$coupon_type_new', '$minimum_new', '$times_new', '$expire_new', '$status_new', '$login', '$productid_new', '$categoryid_new')");
	}
	header("Location: coupons.php");
    
}

if ($mode=="delete") {
#
# Delete coupon
#
    db_query("delete from discount_coupons where coupon='$coupon' $provider_condition");
	header("Location: coupons.php");
	exit;
}

$coupons = func_query("select * from discount_coupons where 1 $provider_condition");

$smarty->assign("coupons", $coupons);
$smarty->assign("main","coupons");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
