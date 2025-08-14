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
 * @version $Id: CollectionsTest.class.php,v 1.3 2005/09/29 15:11:48 slizardo Exp $
 * @package poop.util.tests
 */
 
import('poop.qa.TestCase');
import('poop.util.Vector');

class CollectionsTest extends TestCase {

	private $vector;

	public function setUp() {
		$this->vector = new Vector();
	}

	public function testVector() {
		$this->vector->add(new Integer(4));
		$this->vector->add(new String('hola mundo'));
		$this->vector->add(new Double(31.4));
		$this->vector->add(3);
		self::assert($this->vector->size() == 4);
	}
}
	
?>
