<?php

import('poop.mvc.Globals');
import('poop.mvc.ActionMessages');

$session = $request->getSession();
$messages = $session->getAttribute(Globals::MESSAGE_KEY);

$errors = array();
//print_r($messages->getMessages()->toArray());die;
if(false) {// is_object($messages)) {
	$errors = $messages->getMessages()->toArray();
} else {
	$errors['title'] = '';
	$errors['author'] = '';
	$errors['details'] = '';
}
	
$template->assign('errors', $errors);
$template->assign('title', 'Sample application');
$template->assign('styleSheets', array('/styleSheets/sample.css'));

$template->assign('txt_title', $resources->getString('title'));
$template->assign('txt_author', $resources->getString('author'));
$template->assign('txt_details', $resources->getString('details'));

?>
