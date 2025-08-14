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
 * @version $Id: PrimitivesTest.class.php,v 1.6 2005/10/13 13:43:22 slizardo Exp $
 * @package poop.lang.tests
 */

import('poop.qa.TestCase');
 
class PrimitivesTest extends TestCase {

	private $int5;
	private $int25;

	private $double4_5;
	private $double4_6;
	
	private $str1;
	private $str2;
	private $str3;	

	public function setUp() {
		$this->int5 = new Integer(5);
		$this->int25 = new Integer(25);

		$this->double4_5 = new Double(4.5);
		$this->double4_6 = new Double(4.6);
		
		$this->str1 = new String('hola');
		$this->str2 = new String('hola');
		$this->str3 = new String('visca, visca, catalunya lliure');
	}

	public function testBoolean() {
		// __construct()
		$true = Boolean::TRUE();
		$false = new Boolean(false);

		// booleanValue()
		self::assert($true->booleanValue() == true);

		// valueOf()
		$true = Boolean::valueOf('true');
		self::assert($true->equals(true) == false);

		// __toString()
		self::assert($true->__toString()->equals(new String('true')));

		// equals()
		self::assert($true->equals($false) == false);
		self::assert($false->equals($false) == true);
	}
	
	public function testIntegers() {
		self::assert(Integer::toHexString(10)->equals(new String('a')));
		self::assert(Integer::toHexString(2748)->equals(new String('abc')));	
		
		self::assert($this->int5->intValue() == 5);
		self::assert($this->int25->intValue() == 25);
		
		self::assert($this->int5->equals($this->int25) == false);
	}

	public function testDoubles() {
		self::assert($this->double4_5->equals($this->double4_6) == false);
	}
	
	public function testStrings() {
		self::assert($this->str1->charAt(2) == 'l');
		self::assert($this->str1->equals($this->str2) == true);
		self::assert($this->str1->equals($this->str3) == false);

		$visca = new String('visca');
		self::assert($this->str3->substring(0, 5)->equals($visca));
		
		self::assert($this->str3->replaceFirst('visca', 'viva')->equals(new String('viva, visca, catalunya lliure')));
	}
}

?>
