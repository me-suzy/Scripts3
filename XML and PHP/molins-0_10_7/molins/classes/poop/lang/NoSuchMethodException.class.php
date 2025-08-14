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
 * @version $Id: NoSuchMethodException.class.php,v 1.3 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */

class NoSuchMethodException extends Exception {

	/**
	 * @param string $method
	 */
	public function __construct($method) {
		parent::__construct(_("el metodo $method doesn't exists").' '.$method);
	}
}

?>
