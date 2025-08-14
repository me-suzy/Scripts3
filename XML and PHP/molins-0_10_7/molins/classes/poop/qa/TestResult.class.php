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
 * @version $Id: TestResult.class.php,v 1.5 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

abstract class TestResult {
	const SUCESS = 1;
	const FAILURE = 2;

	abstract public function printHeader();

	/**
	 * @param string $method
	 * @param int $status
	 * @param string $description
	 */
	abstract public function printResult($method, $status, $description = null);

	abstract public function printFooter();
}

?>
