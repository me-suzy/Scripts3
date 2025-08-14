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
 * @version $Id: TestCase.class.php,v 1.8 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.AssertionException');
 
class TestCase {

	public function setUp() {
	}

	public function teardown() {
	}

	/**
	 * @param boolean $condition
	 */
	public static function assert($condition) {
		if($condition == false) {
			throw new AssertionException();
		}	
	}
}
	
?>
