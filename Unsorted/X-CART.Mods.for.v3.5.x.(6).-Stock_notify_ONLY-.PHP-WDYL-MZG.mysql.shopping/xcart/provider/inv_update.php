<?php

#
# $Id: inv_update.php,v 1.10.2.4 2004/03/25 11:44:11 max Exp $
#

@set_time_limit(1800);

require "./auth.php";
require $xcart_dir."/include/security.php";

if ($REQUEST_METHOD=="POST") {

        $provider_condition=($single_mode?"":" AND $sql_tbl[products].provider='$login'");

        $userfile = func_move_uploaded_file("userfile");    
        if ($fp = func_fopen($userfile,"r",true)) {
                while ($columns = fgetcsv ($fp, 65536, $delimiter)) {
                        if ($columns[0]) {
                                if ($what == "p") {
                                        $pid = func_query_first ("SELECT productid FROM $sql_tbl[products] WHERE productcode='$columns[0]' $provider_condition");
                                        db_query ("UPDATE $sql_tbl[pricing] SET price='$columns[1]' WHERE productid='$pid[productid]' AND quantity=1");
                                } else {
					// Added by funkydunk
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
					// end funkydunk
                                        db_query ("UPDATE $sql_tbl[products] SET avail='$columns[1]' WHERE productcode='$columns[0]' $provider_condition");
                                }
                        }
                }
                $smarty->assign("main", "inv_updated");
                @fclose($fp);
        } else {
                $smarty->assign("main", "error_inv_update");
        }
        @unlink($userfile);
} else {
        $smarty->assign ("main", "inv_update");
}

$smarty->assign("upload_max_filesize", ini_get("upload_max_filesize"));

//require $xcart_dir."/include/categories.php";

@include $xcart_dir."/modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
