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
 * @version $Id: I18NTest.class.php,v 1.4 2005/09/29 15:11:48 slizardo Exp $
 * @package poop.util.tests
 */

import('poop.util.Locale');
import('poop.util.ResourceBundle');
 
class I18NTest extends TestCase {

	private $locale;
	private $resourceBundle;

	public function setUp() {
		$this->locale = new Locale('es', 'ES');
		$this->resourceBundle = ResourceBundle::getBundle('testing', $this->locale); 
	}
	
	public function testGet() {
		$zipCode = $this->resourceBundle->getString('zipcode');
		self::assert($zipCode == 'Codigo postal');		
	}

	public function testResourceBundleLocale() {
		self::assert($this->resourceBundle->getLocale()->equals($this->locale));
		$numberOfKeys = count($this->resourceBundle->getKeys());
		self::assert($numberOfKeys == 2);
	}
}

?>
