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
 * @version $Id: ImageTestResult.class.php,v 1.5 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.TestResult');
import('poop.http.HttpResponse');
 
class ImageTestResult extends TestResult {

	private $response;

	/**
	 * @param HttpResponse $response
	 */
	public function setResponse(HttpResponse $response) {
		$this->response = $response;
	}

	public function printHeader() {
		$this->response->setContentType('image/png');
	}

	/**
	 * @param string $method
	 * @param int $status
	 * @param string $description
	 */
	public function printResult($method, $status, $description = null) {
	}
	
	public function printFooter() {
	}
}

?>
