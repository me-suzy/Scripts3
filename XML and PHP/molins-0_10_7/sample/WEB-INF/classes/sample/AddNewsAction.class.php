<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: AddNewsAction.class.php,v 1.7 2005/10/13 13:42:59 slizardo Exp $
 * @package sample
 */

import('poop.mvc.Action');
import('sample.NewsFormBean');
import('sample.News');
 
class AddNewsAction extends Action {

	public function execute(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {

		$title = $form->getTitle();
		$author = $form->getAuthor();
		$details = $form->getDetails();

		$messages = new ActionMessages();
		try {
			$news = new News();
			$news->setCreationDate('SelectClause::NOW');
			$news->setTitle($title);
			$news->setAuthor($author);
			$news->setDetails($details);
			$news->insert();
	
			$message = new ActionMessage('news.added_correctly');
			$messages->add(ActionMessages::GLOBAL_MESSAGE, $message);

			$this->saveMessages($request, $messages);
			
			return $mapping->findForward('success');
			
		} catch (Exception $e) {	
			$message = new ActionMessage('news.add_exception');
			$messages->add(ActionMessages::GLOBAL_MESSAGE, $message);
			
			$this->saveMessages($request, $messages);

			return $mapping->findForward('exception');
		}
	}
}

?>
