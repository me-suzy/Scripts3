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
# $Id: shipping_zones.php,v 1.7 2002/04/22 17:10:31 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

$provider_condition=($single_mode?"":"and provider='$login'");

#
# provider condition is always enabled potomuchto esli ee otrubit' - eto pizda
#

if($REQUEST_METHOD=="POST") {
#
# Calculate the last zone
#
	$last_country_zone=array_pop(func_query_first("select max(zoneid) from country_zones where 1 $provider_condition"));
	$last_state_zone=array_pop(func_query_first("select max(zoneid) from state_zones where 1 $provider_condition"));
	$last_zone=1+max($last_country_zone,$last_state_zone);

	if (!empty($zone_countries))
		foreach($zone_countries as $zone_country)
			if ($newzoneid!="" && $newzoneid==0) {
				db_query("delete from country_zones where code='$zone_country' and zoneid='$zoneid' $provider_condition");
			}
			elseif($newzoneid!=0 && $newzoneid!="") {
				@db_query("insert into country_zones (code,zoneid,provider) values ('$zone_country','$newzoneid','$login')");
				db_query("update country_zones set zoneid='$newzoneid' where zoneid='$zoneid' and code='$zone_country' $provider_condition");
			}
			else {
				db_query("delete from country_zones where code='$zone_country' and zoneid='$zoneid' $provider_condition");
				db_query("insert into country_zones (zoneid,code,provider) values ('$last_zone','$zone_country','$login')");
			}

	if (!empty($zone_states))
		foreach($zone_states as $zone_state)
			if ($newzoneid!="" && $newzoneid==0) {
				db_query("delete from state_zones where code='$zone_state' and zoneid='$zoneid' $provider_condition");
			}
			elseif($newzoneid!=0 && $newzoneid!="") {
				@db_query("insert into state_zones (code,zoneid,provider) values ('$zone_state','$newzoneid','$login')");
				db_query("update state_zones set zoneid='$newzoneid' where zoneid='$zoneid' and code='$zone_state' $provider_condition");
			}
			else {
				db_query("delete from state_zones where code='$zone_state' and zoneid='$zoneid' $provider_condition");
				db_query("insert into state_zones (zoneid,code,provider) values ('$last_zone','$zone_state','$login')");
			}
}

#
# Prepare zones list
#
$country_zones=func_query("select countries.code, countries.country, greatest(country_zones.zoneid,0) as zoneid from countries LEFT JOIN country_zones on countries.code=country_zones.code $provider_condition order by country");

$state_zones=func_query("select states.code, states.state, greatest(state_zones.zoneid,0) as zoneid from states LEFT JOIN state_zones on states.code=state_zones.code $provider_condition order by state");

#
# Prepare all zones list (gather zones from country_zones and state_zones
#
$all_zones = func_shipping_zones();

$smarty->assign("country_zones", $country_zones);
$smarty->assign("state_zones", $state_zones);
$smarty->assign("all_zones", $all_zones);
$smarty->assign("shipping_rates", $shipping_rates);
$smarty->assign("main","shipping_zones");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
