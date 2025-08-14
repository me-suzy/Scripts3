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
# $Id: shipping_rates.php,v 1.14 2002/04/22 17:10:31 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

$provider_condition=($single_mode?"":"and provider='$login'");

if ($REQUEST_METHOD=="POST") {
	while(list($key,$val)=each($HTTP_POST_VARS))
	{
#
# Update shipping table
#
    	if (strstr($key,"-")) {
        	list($field,$rateid)=split("-",$key);
        	db_query("update shipping_rates set $field='$val' where rateid='$rateid' $provider_condition");
    	}
	}
#
# Add new shipping rate
#
    if ($shippingid_new) 
		db_query("insert into shipping_rates (shippingid, maxweight, maxamount, maxtotal, rate, item_rate, rate_p, weight_rate, provider, zoneid) values ('$shippingid_new','$maxweight_new','$maxamount_new','$maxtotal_new','$rate_new','$item_rate_new','$rate_p_new','$weight_rate_new','$login','$zoneid_new')");
    
}

if ($mode=="delete") {
#
# Delete shipping option
#
    db_query("delete from shipping_rates where rateid='$rateid' $provider_condition");
}

$zone_condition = ($zoneid!=""?"and shipping_rates.zoneid='$zoneid'":"");
$method_condition = ($shippingid!=""?"and shipping_rates.shippingid='$shippingid'":"");

$shipping_rates = func_query("select shipping_rates.*, shipping.shipping_time from shipping, shipping_rates where shipping_rates.shippingid=shipping.shippingid $provider_condition $zone_condition $method_condition order by shipping.orderby, shipping_rates.maxweight");

#
# Prepare zones list
#
$zones = func_shipping_zones();

$shipping = func_query("select * from shipping where active='Y' ".($realtime_shipping=="Y"?"and shipping.code=''":"")." order by orderby");
$smarty->assign("shipping", $shipping);

$smarty->assign("zones", $zones);
$smarty->assign("shipping_rates", $shipping_rates);
$smarty->assign("main","shipping_rates");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
