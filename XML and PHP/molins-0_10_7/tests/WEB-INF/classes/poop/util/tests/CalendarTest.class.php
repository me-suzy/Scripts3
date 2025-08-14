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
 * @version $Id: CalendarTest.class.php,v 1.4 2005/09/29 15:11:48 slizardo Exp $
 * @package poop.util.tests
 */
 
import('poop.qa.TestCase');

import('poop.util.Calendar');
import('poop.util.GregorianCalendar');

class CalendarTest extends TestCase {

	private $calendar;

	public function setUp() {
		$this->calendar = new GregorianCalendar();
		$this->calendar->set(Calendar::MONTH, 10);
	}

	public function testGet() {
		$this->calendar->get(Calendar::YEAR, 2005);
	}
}
	
?>
