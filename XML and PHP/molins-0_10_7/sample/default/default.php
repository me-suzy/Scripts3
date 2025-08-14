<?php

import('sample.NewsGroup');

$newsList = array();

$newsGroup = new NewsGroup();
$newsGroup->setOrderBy('creation_date', DbGroup::DESC);
while($newsGroup->hasNext()) {
	$news = $newsGroup->next();

	array_push($newsList, $news->toArray());
}
unset($newsGroup);

$languages = array('es_ES', 'en_US', 'ca_ES');

$template->assign('title', 'Sample application');
$template->assign('styleSheets', array('/styleSheets/sample.css'));
$template->assign('javaScripts', array('/javaScripts/sample.js'));
$template->assign('languages', $languages);

$template->assign('newsList', $newsList);

$template->assign('txt_welcome', $resources->getString('welcome'));
$template->assign('txt_add_news', $resources->getString('add_news'));
$template->assign('txt_author', $resources->getString('author'));

?>
