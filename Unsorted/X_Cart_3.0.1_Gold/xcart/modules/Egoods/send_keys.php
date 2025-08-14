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
# $Id: send_keys.php,v 1.3 2002/05/20 06:55:19 lucky Exp $
#
# This module generates download key which is sent to customer
# and inserts this key into database 
#

function keygen($productid, $key_TTL) {
	$key = md5(uniqid(rand()));
	$expires = time() + $key_TTL*3600;
	db_query("INSERT INTO download_keys (download_key, expires, productid) VALUES('$key', '$expires', '$productid')");
	return $key;
}

#
# Generate keys for all E-products
#
$send_keys = false;

foreach($products as $key=>$value){
	if ($value["distribution"]) {
		$download_key = keygen($value["productid"], $download_key_ttl);
		$products[$key]["download_key"] = $download_key;
		$products[$key]["distribution_filename"] = basename($products[$key]["distribution"]);
		$send_keys = true;
	}
}

$mail_smarty->assign("products", $products);
$mail_smarty->assign("download_key_ttl", $download_key_ttl);

#
# If there is Egoods - send them !!!
#

$customer_language = func_get_language ($userinfo[language]);

if($send_keys)
	func_send_mail($userinfo["email"], "mail/egoods_download_keys_subj.tpl", "mail/egoods_download_keys.tpl", $orders_department, false);

?>
