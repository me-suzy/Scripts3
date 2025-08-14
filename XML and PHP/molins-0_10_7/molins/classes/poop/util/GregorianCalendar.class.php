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
 * @version $Id: GregorianCalendar.class.php,v 1.4 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */
	
import('poop.util.Calendar');
	
class GregorianCalendar extends Calendar {

	private $actualDate;
	
	public function __construct() {
		$this->actualDate = getdate();
	}
}
	
?>
