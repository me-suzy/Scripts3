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
# $Id: https.php,v 1.12 2002/05/22 10:27:27 verbic Exp $
#
# HTTP-HTTPS redirection mechanism code
#

$https_messages= array("mode=order_message&orderids", "error_message.php");
$https_scripts = array();

#
# create payment scripts entries in $https_scripts
#
$payment_data = func_query("select * from payment_methods");

if($payment_data)
	foreach($payment_data as $payment_method_data)
   		if ($payment_method_data["protocol"]=="https") $https_scripts[]="paymentid=".$payment_method_data["paymentid"]."&mode=checkout";

function is_https_link($link, $https_scripts) {
		

		if(!$https_scripts) return(false);

        $result=false;

        foreach($https_scripts as $https_script)
                if (strstr($link, $https_script)) $result=true;

	   return $result;
}

$xcart_host = $HTTPS == "on" ? $xcart_https_host : $xcart_http_host;
$pos = strpos($xcart_host, "/");
$dir = $pos !== false ? substr($xcart_host, $pos) : "";
$current_script = substr($REQUEST_URI, strlen($dir) + strlen($xcart_web_dir));

#
# Generate additional PHPSESSID var
#
$additional_query = ($QUERY_STRING?"&":"?").(strstr($QUERY_STRING,"PHPSESSID")?"":"PHPSESSID=$PHPSESSID");

if($REQUEST_METHOD=="GET")
	if($HTTPS != "on" && is_https_link($current_script, $https_scripts)) {
        header("Location: $https_location".$current_script.$additional_query);
        exit;
	}
	elseif($HTTPS != "on" && is_https_link($current_script, $https_messages) && (substr($HTTP_REFERER, 0, 8) == "https://")) {
        header("Location: $https_location".$current_script.$additional_query);
        exit;
	}
	elseif($HTTPS == "on" && !is_https_link($current_script, $https_scripts) && !is_https_link($current_script, $https_messages)) {
        header("Location: $http_location".$current_script.$additional_query);
        exit;
	}

?>
