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
 * @version $Id: TestRunner.class.php,v 1.13 2005/10/13 13:42:26 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.TestSuite');
import('poop.qa.TestResult');
import('poop.qa.HTMLTestResult');
 
class TestRunner {

	private $testSuites;
	private $testResult;

	public function __construct() {
		$this->testSuites = array();
		$this->testResult = new HTMLTestResult();
	}

	/**
	 * @param TestSuite $testSuite
	 */
	public function add(TestSuite $testSuite) {
		array_push($this->testSuites, $testSuite);
	}

	/**
	 * Devuelve el valor de la propiedad testResult.
	 *
	 * @return TestResult
	 */
	public function getTestResult() {
		return $this->testResult;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad testResult.
	 *
	 * @param TestResult $testResult
	 */
	public function setTestResult(TestResult $testResult) {
		$this->testResult = $testResult;
	}
	
	public function run() {
		foreach($this->testSuites as $testSuite) {
			$this->runSuite($testSuite);
		}
	}

	/**
	 * @param TestSuite $testSuite
	 */
	private function runSuite(TestSuite $testSuite) {
		foreach($testSuite->getTests() as $testCase) {
			if(method_exists($testCase, 'setup')) {
				call_user_func(array($testCase, 'setUp'));
			}
			
			$methods = get_class_methods($testCase);

			$this->testResult->printHeader();
			
			foreach($methods as $method) {
				if(substr($method, 0, 4) == 'test') {
					try {
						@call_user_func(array($testCase, $method));

						$this->testResult->printResult($method, TestResult::SUCESS);
					} catch (AssertionException $ae) {
						$this->testResult->printResult($method, TestResult::FAILURE, $ae->getDescription());
					} catch (Exception $e) {
						$this->testResult->printResult($method, TestResult::FAILURE, $e->__toString());
					}
				}
			}

			if(method_exists($testCase, 'teardown')) {
				call_user_func(array($testCase, 'tearDown'));
			}

			$this->testResult->printFooter();
		}
	}
}

?>
