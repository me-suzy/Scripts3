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
 * @version $Id: FileTest.class.php,v 1.4 2005/09/29 15:11:47 slizardo Exp $
 * @package poop.io.tests
 */

import('poop.io.File');
 
class FileTest extends TestCase {

	private $file;
	
	public function setUp() {
		$this->file = new File(__FILE__);
	}

	public function testIs() {
		self::assert($this->file->isDirectory() == false);
		self::assert($this->file->canRead() == true);
	}

	public function testName() {
		self::assert($this->file->getName() == 'FileTest.class.php');
	}
}
	
?>
