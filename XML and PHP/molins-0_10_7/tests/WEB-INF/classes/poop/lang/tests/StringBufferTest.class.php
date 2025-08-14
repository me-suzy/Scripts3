<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: StringBufferTest.class.php,v 1.3 2005/09/29 15:11:47 slizardo Exp $
 * @package poop.lang.tests
 */

import('poop.qa.TestCase');
 
class StringBufferTest extends TestCase {

	private $sb;

	public function setUp() {
		$this->sb = new StringBuffer('hola');
	}

	public function testAppend() {
		$this->sb->append(' programador')->append(' de Molins');
		$this->sb->append(new String(' !'));

		self::assert($this->sb->__toString() == 'hola programador de Molins !');
	}
}
 
?>
