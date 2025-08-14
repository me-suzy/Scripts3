<?php

import('poop.util.ResourceBundle');

$bundle = ResourceBundle::getBundle('messages', $request->getLocale());

/** Assign the translated messages */
$template->assign('page_title', $bundle->getString('page_title'));
$template->assign('message_first', $bundle->getString('message_first'));
$template->assign('message_congratulations', $bundle->getString('message_congratulations'));

/** Assign the template variable */
$template->assign('molins_url', 'http://www.phpize.com');
	
?>
