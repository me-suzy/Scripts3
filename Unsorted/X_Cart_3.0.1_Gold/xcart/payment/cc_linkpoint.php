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
# $Id: cc_linkpoint.php,v 1.3 2002/04/26 08:40:04 zorg Exp $
#
# Merchant Manager CC processing module
#

$merchantid = $module_params["param01"];
$linkpoint_binary = "linkpoint_".$module_params["param02"];
$cc_action = $module_params["param03"];
$card_expire = substr($userinfo["card_expire"],0,2)."/".substr($userinfo["card_expire"],2,2);
#
# To use authorize.net module please correctly enter -referer parameter
#
if(!file_exists($linkpoint_binary)) {
	header("Location: ../customer/error_message.php?error_ccprocessor_notfound");
	exit;
}

#
# Execute binary
#
exec("./$linkpoint_binary -configfile=$merchantid -ccnum=\"".$userinfo["card_number"]."\" -ccexpdate=$card_expire -ccamount=".$cart["total_cost"]." -name=\"".addslashes($userinfo["firstname"])." ".addslashes($userinfo["lastname"])."\" -address=\"".addslashes($userinfo["b_address"])."\" -state=\"".addslashes($userinfo["b_state"])."\" -zip=\"".$userinfo["zipcode"]."\" -city=\"".addslashes($userinfo["b_city"])."\" -country=\"".addslashes($userinfo["b_country"])."\" -action=auth_only 2>&1",$bill_output);

list($bill_errorcode,$bill_message,$bill_id) = explode(",",$bill_output[0],3);

if($bill_errorcode=="1" && $authorizenet_action=="process") {
    $bill_avscode=$bill_message;
#
# check AVS for security reasons goese here
#
	exec("./$linkpoint_binary -configfile=$merchantid -ccnum=\"".$userinfo["card_number"]."\" -ccexpdate=$card_expire -ccamount=\"".$cart["total_cost"]."\" -name=\"".addslashes($userinfo["firstname"])." ".addslashes($userinfo["lastname"])."\" -address=\"".addslashes($userinfo["b_address"])."\" -state=\"".addslashes($userinfo["b_state"])."\" -zip=\"".$userinfo["zipcode"]."\" -city=\"".addslashes($userinfo["b_city"])."\" -country=\"".addslashes($userinfo["b_country"])."\" -action=process -trid=$bill_id 2>&1",$bill_output);

    list($bill_errorcode1,$bill_message) = explode(",",$bill_output[0],2);

	if($bill_errorcode1!="1") $bill_error="error_ccprocessor_error";
}
elseif ($bill_errorcode=="2") {
	$bill_error="error_ccprocessor_error";
}
elseif($bill_errorcode!="1") {
    $bill_error="error_ccprocessor_unavailable";
	$bill_message=$bill_output[0];
}

?>
