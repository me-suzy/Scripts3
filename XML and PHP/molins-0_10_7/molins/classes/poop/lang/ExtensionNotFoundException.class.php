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
 * @version $Id: ExtensionNotFoundException.class.php,v 1.7 2005/10/18 14:58:13 slizardo Exp $
 * @package poop.lang
 */

class ExtensionNotFoundException extends Exception {

	private $description;
	
	/**
	 * @param string $extension
	 */	 
	public function __construct($extension) {
		parent::__construct("la extension necesaria $extension.".PHP_SHLIB_SUFFIX." no ha sido encontrada");

		$this->description = "agrega <i>extension= $extension.".PHP_SHLIB_SUFFIX."</i> al php.ini";		
	}

	public function __toString() {
		return $this->description;
	}
}
?>
