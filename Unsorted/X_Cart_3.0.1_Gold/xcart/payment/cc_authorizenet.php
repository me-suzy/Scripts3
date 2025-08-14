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
# $Id: cc_authorizenet.php,v 1.7 2002/04/26 09:54:09 zorg Exp $
#
# Authorize.Net CC processing module
#

$authorizenet_login = $module_params["param01"];
$authorizenet_password = $module_params["param02"];
$authorizenet_mode = $module_params["param03"];
$authorizenet_action = $module_params["param04"];
$authorizenet_referer = $module_params["param05"];

#
# To use authorize.net module please correctly enter -referer parameter
#
if(!file_exists("authorize_net.pl")) {
	header("Location: ../customer/error_message.php?error_ccprocessor_notfound");
	exit;
}

#
# Execute perl script
# Temporary disabled
#
#exec("./authorize_net.pl -login=\"$authorizenet_login\" -password=\"$authorizenet_password\" -ccnum=\"".$userinfo["card_number"]."\" -ccexpdate=\"".$userinfo["card_expire"]."\" -ccamount=\"".$cart["total_cost"]."\" -address=\"".addslashes($userinfo["b_address"])."\" -firstname=\"".addslashes($userinfo["firstname"])."\" -lastname=\"".addslashes($userinfo["lastname"])."\" -zip=\"".$userinfo["b_zipcode"]."\" -referer=\"$authorizenet_referer\" -action=auth_only -mode=$authorizenet_mode 2>&1",$bill_output);

#list($bill_errorcode,$bill_message,$bill_id) = explode(",",$bill_output[0],3);

$bill_errorcode="1";
$bill_id="1010";

if($bill_errorcode=="1" && $authorizenet_action=="process") {
	$bill_avscode=$bill_message;
#
# check AVS for security reasons goese here
#
	exec("./authorize_net.pl -login=\"$authorizenet_login\" -password=\"$authorizenet_password\" -ccnum=\"".$userinfo["card_number"]."\" -ccexpdate=\"".$userinfo["card_expire"]."\" -ccamount=\"".$cart["total_cost"]."\" -address=\"".addslashes($userinfo["b_address"])."\" -firstname=\"".addslashes($userinfo["firstname"])."\" -lastname=\"".addslashes($userinfo["lastname"])."\" -zip=\"".$userinfo["b_zipcode"]."\" -referer=\"$authorizenet_referer\" -action=process -mode=$authorizenet_mode -trid=\"$bill_id\" 2>&1",$bill_output);

    list($bill_errorcode1,$bill_message) = explode(",",$bill_output[0],2);

    if($bill_errorcode1!="1") $bill_error = "error_ccprocessor_error";

} elseif($bill_errorcode=="2") {
	$bill_error = "error_ccprocessor_error";
} elseif($bill_errorcode!="1") {
	$bill_error = "error_ccprocessor_unavailable";
	$bill_message = $bill_output[0];
}

?>
