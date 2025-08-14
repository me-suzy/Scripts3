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
# $Id: cc_verisign.php,v 1.2 2002/04/22 17:10:30 mav Exp $
#
# Merchant Manager CC processing module
#

$vs_user = $module_params["param01"];
$vs_vendor = $module_params["param02"];
$vs_partner = $module_params["param03"];
$vs_pwd = $module_params["param04"];
$vs_host = $module_params["param05"];

#
# To use authorize.net module please correctly enter -referer parameter
#
if(!file_exists("verisign.pl")) {
	header("Location: ../customer/error_message.php?error_ccprocessor_notfound");
	exit;
}

#
# Execute perl script
#
#echo "./verisign.pl -user=$vs_user -partner=$vs_partner -vendor=$vs_vendor -pwd=$vs_pwd -ccnum=\"".$userinfo["card_number"]."\" -exp_date=\"".$userinfo["card_expire"]."\" -amount= \"".$cart["total_cost"]."\" 2>&1";
exec ("./verisign.pl -host=$vs_host -user=$vs_user -partner=$vs_partner -vendor=$vs_vendor -pwd=$vs_pwd -ccnum=\"".$userinfo["card_number"]."\" -exp_date=\"".$userinfo["card_expire"]."\" -amount=".$cart["total_cost"]." 2>&1",$bill_output);
$res1 = explode ('&',$bill_output[0]);

while (list($k,$tmp) = each($res1))
{
	list ($key,$val) = explode ('=',$tmp);
	$response[$key]=$val;
}

if (sizeof($response)==1)
{
	$bill_error="error_ccprocessor_error";
}elseif ($response['RESULT']>0)
{
	$bill_error="error_ccprocessor_error";
}	
?>
