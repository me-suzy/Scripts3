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
 * @version $Id: XMLTestResult.class.php,v 1.7 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.TestResult');
import('poop.http.HttpResponse');
 
class XMLTestResult extends TestResult {

	private $dom;
	private $root;
	private $response;

	/**
	 * @param HttpResponse $response
	 */
	public function setResponse(HttpResponse $response) {
		$this->response = $response;
	}
	
	public function printHeader() {
		$this->response->setContentType('text/xml');
		
		$this->dom = new DomDocument();
		$this->root = $this->dom->createElement('molins-qa');
		$this->root = $this->dom->appendChild($this->root);
	}

	/**
	 * @param string $method
	 * @param int $status
	 * @param string $description
	 */
	public function printResult($method, $status, $description = null) {
		$child = $this->dom->createElement('test');
		$child->setAttribute('method', $method);
		switch($status) {
			case self::SUCESS:
				$status = 'sucess';
				break;

			case self::FAILURE:
				$status = 'failure';
				break;
		}		
		$child->setAttribute('status', $status);
		if(!is_null($description)) {
			$child->setAttribute('description', $description);
		}
		$this->root->appendChild($child);
	}
	
	public function printFooter() {
		print $this->dom->saveXML();
	}
}

?>
