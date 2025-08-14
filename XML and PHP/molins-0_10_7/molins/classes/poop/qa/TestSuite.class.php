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
 * @version $Id: TestSuite.class.php,v 1.6 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.TestCase');
 
class TestSuite {
	
	private $testCases;

	public function __construct() {
		$this->testCases = array();
	}

	/**
	 * @param TestCase $testCase
	 */
	public function addTest(TestCase $testCase) {
		array_push($this->testCases, $testCase);
	}

	/**
	 * @return array
	 */	
	public function getTests() {
		return $this->testCases;
	}
}
	
?>
