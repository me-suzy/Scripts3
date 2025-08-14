<?
// newsedit.php
// funkydunk 2003


require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

if (($mode == 'delete') and ($newsid)) {
    db_query ("DELETE FROM `xcart_news` WHERE newsid='$newsid'");
    func_header_location("newsedit.php");
}
if ($mode == 'add') {
    $query = "INSERT INTO `xcart_news` ( `newsid` , `newsHead` , `newsBody` , `enabled` , `orderby` ) VALUES ('','" . $new_news_head . "','" . $new_news_body . "','" . $new_news_active . "','" . $new_news_order . "')";
    // echo $query;
	db_query ($query);
	func_header_location("newsedit.php");
}

if ($REQUEST_METHOD == "POST") {
	if ($news_head) {
		foreach ($news_head as $key=>$value) {
			db_query ("UPDATE `xcart_news` SET newsHead='$value' WHERE newsid='$key'");
		}
	}
	if ($news_body) {
		foreach ($news_body as $key=>$value) {
			db_query ("UPDATE `xcart_news` SET newsBody='$value' WHERE newsid='$key'");
		}
	}
	if ($news_active) {
		foreach ($news_active as $key=>$value) {
			db_query ("UPDATE `xcart_news` SET enabled='".$value."' WHERE newsid='$key'");
		}
	}
	if ($news_order) {
		foreach ($news_order as $key=>$value) {
			db_query ("UPDATE `xcart_news` SET orderby='".$value."' WHERE newsid='$key'");
		}
	}

	func_header_location("newsedit.php#updated");
}

$news = func_query ("SELECT * FROM `xcart_news` ORDER BY newsid");
$smarty->assign ("news", $news);

$smarty->assign("main","newsedit");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
