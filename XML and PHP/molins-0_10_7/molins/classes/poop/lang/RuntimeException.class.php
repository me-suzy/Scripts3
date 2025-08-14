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
 * @version $Id: RuntimeException.class.php,v 1.3 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */

/**
 * RuntimeException is the superclass of those exceptions that can be thrown during the normal operation of the script.
 *
 * A method is not required to declare in its throws clause any subclasses of RuntimeException that might be thrown during the execution of the method but not caught.
 */
class RuntimeException extends Exception {

	private $description;
	
	public function __construct($message, $description = null) {
		parent::__construct($message);
		
		$this->description = $description;
	}

	public function __toString() {
		return $this->description;
	}
}

?>
