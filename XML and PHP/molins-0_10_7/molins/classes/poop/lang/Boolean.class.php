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
 * @version $Id: Boolean.class.php,v 1.10 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */

import('poop.lang.String');

/**
 * The Boolean class wraps a value of the primitive type boolean in an object. An object of type Boolean contains a single field whose type is boolean.
 * 
 * In addition, this class provides many methods for converting a boolean to a String and a String to a boolean, as well as other constants and methods useful when dealing with a boolean. 
 */
final class Boolean {

	/**
	 * The Boolean object corresponding to the primitive value false.
	 */
	public function TRUE() { return new self(true); }

	/**
	 * The Boolean object corresponding to the primitive value true.
	 */
	public function FALSE() { return new self(false); }
	
	private $value;

	/**
	 * Allocates a Boolean object representing the value argument.
	 *
	 * @param mixed $value
	 */	 
	public function __construct($value) {
		$this->value = $value;
	}
	
	/**
	 * Returns the value of this Boolean object as a boolean primitive.
	 *
	 * @return boolean
	 */
	public function booleanValue() {
		return $this->value;
	}

	/**
	 * Returns a Boolean instance representing the specified boolean value.
	 *
	 * @param mixed $value
	 */	 
	public static function valueOf($value) {
		return new Boolean($value);
	}

	/**
	 * Returns a String object representing this Boolean's value.
	 *
	 * @return string
	 */
	public function __toString() {
		return new String($this->value ? 'true' : 'false');
	}
	
	/**
	 * Returns true if and only if the argument is not null and is a Boolean object that represents the same boolean value as this object.
	 *
	 * @param mixed $object
	 * @return boolean
	 */	 
	public function equals($object) {
		if($object instanceof Boolean) {
			return ($object->booleanValue() === $this->value);
		}

		return false;
	}

	/**
	 * Returns a hash code for this Boolean object.
	 *
	 * @return int
	 */
	public function hashCode() {
		return crc32(serialize($this));
	}
}
	
?>
