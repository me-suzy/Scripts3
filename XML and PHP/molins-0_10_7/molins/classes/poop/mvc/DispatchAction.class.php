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
 * @version $Id: DispatchAction.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.mvc.Action');
 
abstract class DispatchAction extends Action {

	const METHOD_OBJECT = 0;
	const METHOD_NAME = 1;

	/**
	 * @param ActionMapping $mapping
	 * @param ActionForm $form
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @return ActionForward
	 */
	public function execute(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {

		$parameter = $mapping->getParameter();
		if(is_null($parameter)) {
			throw new NullPointerException($parameter);
		}

		$name = $this->getMethodName($mapping, $form, $request, $response, $parameter);

		return $this->dispatchMethod($mapping, $form, $request, $response, $name);
	}

	/**
	 * @param ActionMapping $mapping
	 * @param ActionForm $form
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @throws NoSuchMethodException
	 */
	protected function dispatchMethod(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response, $name) {
		$method = $this->getMethod($name);
		$forward = null;
		
		if(!method_exists($this, $method[self::METHOD_NAME])) {
			throw new NoSuchMethodException($method[self::METHOD_NAME]);
		}
		
		$forward = @call_user_func_array($method, array($mapping, $form, $request, $response));

		return $forward;
	}

	/**
	 * @param string $name
	 */
	protected function getMethod($name) {
		$method = array();
		$method[self::METHOD_OBJECT] = $this;
		$method[self::METHOD_NAME] = $name;

		return $method;
	}
	
	/**
	 * @param ActionMapping $mapping
	 * @param ActionForm $form
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 * @return string
	 */
	protected function getMethodName(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response, $parameter) {
		return $request->getParameter($parameter);
	}
}
	
?>
