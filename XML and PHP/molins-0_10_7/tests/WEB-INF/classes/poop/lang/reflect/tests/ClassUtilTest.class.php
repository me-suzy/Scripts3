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
 * @version $Id: ClassUtilTest.class.php,v 1.1 2005/10/19 15:56:28 slizardo Exp $
 * @package poop.lang.reflect.tests
 */

import('poop.qa.TestCase');
import('poop.lang.reflect.ClassUtil');
	
class ClassUtilTest extends TestCase {

	public function testGetClassFor() {
		$package = 'mypackage.subpackage.MyClass';
		$className = ClassUtil::getClassFor($package);

		self::assert($className == 'MyClass');

		$package = 'MySecondClass'; // Using default package
		$className = ClassUtil::getClassFor($package);

		self::assert($className == 'MySecondClass');
	}
}

?>
