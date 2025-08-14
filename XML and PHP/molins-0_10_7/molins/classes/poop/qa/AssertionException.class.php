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
 * @version $Id: AssertionException.class.php,v 1.6 2005/10/13 13:42:26 slizardo Exp $
 * @package poop.qa
 */
	
class AssertionException extends Exception {

	public function __construct() {
		parent::__construct('assertion failed');
	}
}
	
?>
