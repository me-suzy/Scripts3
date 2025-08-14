<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: Action.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.mvc.ActionForward');
import('poop.mvc.ActionMapping');
import('poop.mvc.ActionForm');
import('poop.mvc.ActionMessages');

import('poop.http.HttpRequest');

import('poop.mvc.Globals');
 
class Action {

	/**
	 * @param ActionMapping $mapping
	 * @param ActionForm $form
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @return ActionForward
	 */
	public function execute(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {
		return null;
	}

	/**
	 * @param HttpRequest $request
	 * @param ActionMessages $messages
	 */
	protected function saveMessages(HttpRequest $request, ActionMessages $messages) {
		$session = $request->getSession();

		if(is_null($messages) || $messages->isEmpty()) {
			// $request->removeAttribute(Globals::MESSAGE_KEY);
			$session->removeAttribute(Globals::MESSAGE_KEY);
			return;
		}

		$session->setAttribute(Globals::MESSAGE_KEY, $messages);
	}
}
	
?>
