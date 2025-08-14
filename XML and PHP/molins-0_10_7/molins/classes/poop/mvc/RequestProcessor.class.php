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
 * @version $Id: RequestProcessor.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.http.HttpRequest');
import('poop.http.HttpResponse');

import('poop.mvc.ActionMapping');
import('poop.mvc.ForwardConfig');

class RequestProcessor {

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */	 
	public function process(HttpRequest $request, HttpResponse $response) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param ActionMapping $mapping
	 */	 
	public function processActionCreate(HttpRequest $request, HttpResponse $response, ActionMapping $mapping) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param ActionMapping $mapping
	 */	 
	public function processActionForm(HttpRequest $request, HttpResponse $response, ActionMapping $mapping) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param ForwardConfig $forwardConfig
	 */	 
	public function processForwardConfig(HttpRequest $request, HttpResponse $response, ForwardConfig $forwardConfig) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param Action $action
	 * @param ActionForm $actionForm
	 * @param ActionMapping $actionMapping
	 */	 
	public function processActionPerform(HttpRequest $request, HttpResponse $response, Action $action, ActionForm $actionForm, ActionMapping $actionMapping) {
	}

	public function processCachedMessages() {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */	 
	public function processContent(HttpRequest $request, HttpResponse $response) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param Exception $exception
	 * @param ActionForm $actionForm
	 * @param ActionMapping $actionMapping
	 */	 
	public function processException(HttpRequest $request, HttpResponse $response, Exception $exception, ActionForm $actionForm, ActionMapping $actionMapping) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param ActionMapping $mapping
	 */	 
	public function processForward(HttpRequest $request, HttpResponse $response, ActionMapping $mapping) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */	 
	public function processLocale(HttpRequest $request, HttpResponse $response) {
		
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param string $path
	 */	 
	public function processMapping(HttpRequest $request, HttpResponse $response, $path) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */	 
	public function processNoCache(HttpRequest $request, HttpResponse $response) {
	}

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
 	 * @param ActionForm $actionForm
	 * @param ActionMapping $actionMapping
	 */	 
	public function processValidate(HttpRequest $request, HttpResponse $response, ActionForm $actionForm, ActionMapping $actionMapping) {
	}

	/**
	 * @param string $uri
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */	 
	public function doForward($uri, HttpRequest $request, HttpResponse $response) {
	}
}
	
?>
