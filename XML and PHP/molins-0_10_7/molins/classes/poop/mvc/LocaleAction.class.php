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
 * @version $Id: LocaleAction.class.php,v 1.2 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.mvc.ActionForward');
import('poop.mvc.ActionMapping');
import('poop.mvc.ActionForm');
import('poop.mvc.ActionMessages');

import('poop.http.HttpRequest');

import('poop.mvc.Globals');
import('poop.mvc.Action');
 
class LocaleAction extends Action {

	/**
	 * @param ActionMapping $mapping
	 * @param ActionForm $form
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @return ActionForward
	 */
	public function execute(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {
		
		$language = $request->getParameter('language');

		list($language, $country) = split('_', $language);
		
		$locale = new Locale($language, $country);

		$session = $request->getSession();

		$session->setAttribute(Globals::LOCALE_KEY, $locale);

		return $mapping->findForward('success');
	}
}
	
?>
