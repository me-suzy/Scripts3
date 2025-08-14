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
 * @version $Id: StringTokenizerTest.class.php,v 1.3 2005/09/29 15:11:48 slizardo Exp $
 * @package poop.util.tests
 */

import('poop.qa.TestCase');
import('poop.util.StringTokenizer');

class StringTokenizerTest extends TestCase {

	private $tokenizer;
	
	public function setUp() {
		$this->tokenizer = new StringTokenizer('Molins framework for PHP5', 'for');
	}
	
	public function testTokenizer() {
		self::assert($this->tokenizer->countTokens() == 2);
		
		$this->tokenizer->nextToken(); // 'Molins framework '
		
		self::assert($this->tokenizer->nextToken() == ' PHP5');
		
		self::assert(is_null($this->tokenizer->nextToken()));
	}
}

?>
