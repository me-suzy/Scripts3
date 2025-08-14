<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2003 Ruslan R. Fazliev <rrf@rrf.ru>                      |
| All rights reserved.                                                        |
+-----------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
| AT THE FOLLOWING URL: http://www.x-cart.com/license.php                     |
|                                                                             |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  RUSLAN  R. |
| FAZLIEV (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev             |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2003           |
| Ruslan R. Fazliev. All Rights Reserved.                                     |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: inv_update.php,v 1.7.2.1 2003/06/02 11:57:57 svowl Exp $
#

@set_time_limit(1800);

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

if ($REQUEST_METHOD=="POST") {

	$provider_condition=($single_mode?"":" AND $sql_tbl[products].provider='$login'");
	
	if($fp = @fopen($userfile,"r")) {
		while ($columns = fgetcsv ($fp, 65536, $delimiter)) {
			if ($columns[0]) {
				if ($what == "p") {
					$pid = func_query_first ("SELECT * FROM $sql_tbl[products] WHERE productcode='$columns[0]' $provider_condition");
					$f = "UPDATE $sql_tbl[pricing] SET price='$columns[1]' WHERE productid='$pid[productid]' AND quantity=1";
					echo $f."<BR>";
					db_query ($f);
				} else {
					$pid = func_query_first ("SELECT * FROM $sql_tbl[products] WHERE productcode='$columns[0]' $provider_condition");
					$watchers = db_query("SELECT email from `xcart_notify` WHERE productid = '$pid[productid]'");
					$mailproduct = func_query_first("SELECT * from `xcart_products` WHERE productid = '$pid[productid]'");
					$oldavail = intval($product['avail']);
					// echo $key . "<br>";
					// echo $oldavail . "now";
					// echo $value . "<br>";
					if ($columns[1] > $oldavail){			
						// echo "SELECT * from `xcart_notify` WHERE productid = '$productid'";
						$mail_smarty->assign ("product", $mailproduct); // put in to assign product info to smarty
							while ($row = db_fetch_array($watchers)){
								$email = $row['email'];
								// echo $email;
								func_send_mail($email, "mail/".$prefix."stock_notify_subj.tpl", "mail/".$prefix."stock_notify.tpl", $config["Company"]["orders_department"], false);
								// delete watchers from table
								db_query("delete from `xcart_notify` where email='" . $email . "' and productid = $key");
							}
						// $product = "";
						$watchers = "";
					}
					db_query ("UPDATE $sql_tbl[products] SET avail='$columns[1]' WHERE productcode='$columns[0]' $provider_condition");
				}
			}
		}
		$smarty->assign("main", "inv_updated");
	} else {
		$smarty->assign("main", "error_inv_update");
	}
} else {
	$smarty->assign ("main", "inv_update");
}

require "../include/categories.php";

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
