<?
// faqedit.php
// funkydunk 2003


require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

if (($mode == 'delete') and ($faqid)) {
    db_query ("DELETE FROM `xcart_faq` WHERE faqid='$faqid'");
    func_header_location("faqedit.php");
}
if ($mode == 'add') {
    $query = "INSERT INTO `xcart_faq` ( `faqid` , `faqHead` , `faqBody` , `enabled` , `orderby` ) VALUES ('','" . $new_faq_head . "','" . $new_faq_body . "','" . $new_faq_active . "','" . $new_faq_order . "')";
    // echo $query;
	db_query ($query);
	func_header_location("faqedit.php");
}

if ($REQUEST_METHOD == "POST") {
	if ($faq_head) {
		foreach ($faq_head as $key=>$value) {
			db_query ("UPDATE `xcart_faq` SET faqHead='$value' WHERE faqid='$key'");
		}
	}
	if ($faq_body) {
		foreach ($faq_body as $key=>$value) {
			db_query ("UPDATE `xcart_faq` SET faqBody='$value' WHERE faqid='$key'");
		}
	}
	if ($faq_active) {
		foreach ($faq_active as $key=>$value) {
			db_query ("UPDATE `xcart_faq` SET enabled='".$value."' WHERE faqid='$key'");
		}
	}
	if ($faq_order) {
		foreach ($faq_order as $key=>$value) {
			db_query ("UPDATE `xcart_faq` SET orderby='".$value."' WHERE faqid='$key'");
		}
	}

	func_header_location("faqedit.php#updated");
}

$faq = func_query ("SELECT * FROM `xcart_faq` ORDER BY faqid");
$smarty->assign ("faq", $faq);

$smarty->assign("main","faqedit");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
