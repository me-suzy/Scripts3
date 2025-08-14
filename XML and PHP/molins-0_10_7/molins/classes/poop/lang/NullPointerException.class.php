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
 * @version $Id: NullPointerException.class.php,v 1.6 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */

class NullPointerException extends Exception {

	/**
	 * @param string $message
	 */
	public function __construct($message) {
		parent::__construct(_('Excepcion por variable nula').' '.$message);
	}
}

?>
