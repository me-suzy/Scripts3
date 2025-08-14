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
 * @version $Id: Double.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.lang
 */

final class Double {

	private $value;
	
	/**
	 * @param mixed $value
	 */	 
	public function __construct($value) {
		$this->value = doubleval($value);
	}
	
	/**
	 * @return double
	 */
	public function doubleValue() {
		return $this->value;
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */	 
	public function equals($object) {
		if($object instanceof Double) {
			return ($object->doubleValue() == $this->value);
		}

		return false;
	}
}

?>
