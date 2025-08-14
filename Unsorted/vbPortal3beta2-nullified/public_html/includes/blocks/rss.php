<?php
$blocks_modules[rss] = array(
	'title' => 'RSS News Feed',
	'func_display' => 'blocks_rss_block',
	'func_add' => 'blocks_rss_test',
	'func_update' => 'blocks_rss_update',
	'text_type' => 'RSS',
	'text_type_long' => 'RSS Newsfeed',
	'text_content' => 'RSS Newsfeed',
	'support_nukecode' => false,
	'allow_create' => true,
	'allow_delete' => true,
	'form_url' => true,
	'form_content' => false,
	'form_refresh' => true,
	'url_text' => 'RSS File URL',
	'show_preview' => true,
    'has_templates' => false
    );
function blocks_rss_block($row) {
	advheadlines($row);
}
function blocks_rss_update($row) {
	global $nuketable,$DB_site;
	if($row[rssurl]) {
		$row[url] = $row[rssurl];
		blocks_rss_test($row);
		if(!$row[title]) {
			$row2 = $DB_site->fetch_array($DB_site->query("SELECT sitename FROM $nuketable[advheadlines] WHERE rssurl='$row[url]'"));
			$row[title] = $row2[sitename];
		}
	}
	else {
		blocks_rss_test($row);
		$vars = array('sitename'=>$row[title],'rssurl'=>$row[url]);
		HeadlinesAdd($vars);
	}
	return $row;
}
function blocks_rss_test($row) {
	if(!ereg('http://' , $row[url])) {
		$row[url] = 'http://' . $row[url];
	}
	if($row[url] == 'http://') {
		$row[url] = '';
	}
	return $row;
}
?>