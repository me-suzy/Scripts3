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
 * @version $Id: Controller.class.php,v 1.8 2005/10/13 16:27:32 slizardo Exp $
 * @package poop.http
 */

import('poop.io.FileNotFoundException');
 
import('poop.http.HttpResponse');
import('poop.http.HttpSession');

import('poop.mvc.MVCException');
import('poop.mvc.ActionMapping');
import('poop.mvc.ActionForm');

import('poop.mvc.config.ActionConfig');
import('poop.mvc.config.ForwardConfig');
import('poop.mvc.config.FormBeanConfig');

import('poop.mvc.config.ApplicationConfig');

import('poop.presentation.XMLRender');
import('poop.presentation.XSLTRender');
import('poop.presentation.TemplateRender');

import('poop.mvc.Globals');
import('poop.mvc.ActionErrors');
import('poop.mvc.ActionMessages');

Controller::init();

final class Controller {
	
	public static function init() {
		ApplicationConfig::init();
	}
	
	public static function processRequest() {
		$request = $GLOBALS['request'];
		$response = $GLOBALS['response'];
		
		$session = $request->getSession();

		$locale = $session->getAttribute(Globals::LOCALE_KEY);
		if($locale == null) {
			$locale = $request->getLocale();
		}
		if($locale == null) {
			$locale = new Locale('en', 'US');
		} 

		$session->setAttribute(Globals::LOCALE_KEY, $locale);
		
		$mvc = $request->getParameter('mvc');

		switch($mvc) {
			case 'xml':
				XMLRender::render($request, $response);
				break;
				
			case 'xslt':
				XSLTRender::render($request, $response);
				break;

			case 'ctrl':
				self::processCTRL($request, $response);
				break;

			case 'tpl':
				TemplateRender::render($request, $response);
				break;

			case 'php':
			default:
				if(file_exists('default/default.html')) {
					require_once 'default/default.html';
				} else
				if(file_exists('default/default.tpl')) {
					TemplateRender::render($request, $response);
				} else
				if(file_exists('default/default.php')) {
					require_once 'default/default.php';
				} 
				else throw new MVCException(_('fichero de bienvenida no encontrado'));
		}
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */
	private static function processCTRL(HttpRequest $request, HttpResponse $response) {	
		$mapping = new ActionMapping();

		$path = $request->getParameter('path');
		
		$action = ApplicationConfig::getActionByPath($path);

		$actionErrors = new ActionErrors();
		$form = new ActionForm();		
		$name = $action->getName();
		if(!empty($name)) {
			$type = ApplicationConfig::getFormBeanByName($name)->getType();
			$form = ClassUtil::importInstance($type);

			foreach($_REQUEST as $parameter => $value) {
				$setter = 'set'.ucfirst($parameter);
				if(method_exists($form, $setter)) {
					call_user_func(array($form, $setter), $value);
				}
			}

			$validate = $action->getValidate();
			if($validate) {
				$actionErrors = $form->validate($mapping, $request);
			}
		}
	
		if($actionErrors->isEmpty()) {
			$mapping->setForwards($action->findForwardConfigs());
			$mapping->setParameter($action->getParameter());
		
			$action = ClassUtil::importInstance($action->getType());	
			$forward = $action->execute($mapping, $form, $request, $response);
			
			if(!is_null($forward)) {
				$response->sendRedirect(CONTEXT_PATH.$forward->getPath());
			}
		} else {
			$url = $_SERVER['HTTP_REFERER'];
			$forward = new ForwardConfig($url, $url);

			$session = $request->getSession();
			$session->setAttribute(Globals::MESSAGE_KEY, $actionErrors);
			
			// include $forward->getPath();
			$response->sendRedirect($forward->getPath());
		}
	}
}

?>
