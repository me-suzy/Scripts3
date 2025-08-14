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
 * @version $Id: TemplateRender.class.php,v 1.9 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.presentation
 */

import('poop.presentation.Renderizable');
 
import('poop.http.HttpRequest');
import('poop.http.HttpResponse');
 
import('poop.util.ResourceBundle');

import('poop.presentation.Template');

class TemplateRender implements Renderizable {

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */
	public static function render(HttpRequest $request, HttpResponse $response) {
		$mod = $request->getParameter('mod');
		if(is_null($mod)) $mod = 'default';
		
		$fx = $request->getParameter('fx');
		if(is_null($fx)) $fx = 'default';

		$phpFile = sprintf('%s/%s.php', $mod, $fx);
		$templateFile = sprintf('%s/%s.tpl', $mod, $fx);

		$locale = $request->getSession()->getAttribute(Globals::LOCALE_KEY);

		if(!file_exists($templateFile)) {
			throw new FileNotFoundException($templateFile);
		} else {
			$template = new Template();
			$template->setDebugging(false);
			$template->setTemplateDir(CONF_DIR_APP.DIRECTORY_SEPARATOR.$mod);
			$template->setCompileId($mod);
			$template->setTemplate($fx.'.tpl');
			$template->assign('CONTEXT_PATH', CONTEXT_PATH);
			
			if(file_exists($phpFile)) {
				$resources = ResourceBundle::getBundle('messages', $locale);
				
				require_once $phpFile;
			}

			print $template;
		}
	}
}

?>
