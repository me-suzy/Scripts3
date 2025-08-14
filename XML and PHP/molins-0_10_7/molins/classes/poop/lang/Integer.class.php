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
 * @version $Id: Integer.class.php,v 1.6 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */
 
import('poop.lang.Comparable');
 
/**
 * The Integer class wraps a value of the primitive type int in an object. An object of type Integer contains a single field whose type is int.
 * 
 * In addition, this class provides several methods for converting an int to a String and a String to an int, as well as other constants and methods useful when dealing with an int. 
 */ 
final class Integer implements Comparable {

	private $value;
	
	/**
	 * Constructs a newly allocated Integer object that represents the specified int value.
	 *
	 * @param mixed $value
	 */	 
	public function __construct($value) {
		$this->value = intval($value);
	}

	/**
	 * Returns the value of this Integer as an int.
	 *
	 * @return int
	 */
	public function intValue() {
		return $this->value;
	}

	/**
	 * Returns the value of this Integer as a double.
	 *
	 * @return double
	 */
	public function doubleValue() {
		return doubleval($this->value);
	}
	
	/**
	 * Returns the value of this Integer as a float.
	 *
	 * @return float
	 */
	public function floatValue() {
		return floatval($this->value);
	}

	/**
	 * Compares this object to the specified object.
	 *
	 * @param mixed $object
	 * @return boolean
	 */	 
	public function equals($object) {
		if($object instanceof Integer) {
			return ($object->intValue() === $this->value);
		}

		return false;
	}

	/**
	 * Parses the string argument as a signed integer in the radix specified by the second argument.
	 *
	 * @param String $value
	 * @param int $radix
	 */
	public static function parseInt(String $value, $radix = 10) {
		return base_convert($value->__toString(), $radix, 10);
	}

	/**
	 * Returns a string representation of the integer argument as an unsigned integer in base 2.
	 *
	 * @param int $i
	 */
	public function toBinaryString($i) {
		return new String(decbin($i));
	}

	/**
	 * Returns a string representation of the integer argument as an unsigned integer in base 16.
	 *
	 * @param int $i
	 */
	public function toHexString($i) {
		return new String(dechex($i));
	}

	/**
	 * Returns a string representation of the integer argument as an unsigned integer in base 8.
	 *
	 * @param int $i
	 */
	public function toOctalString($i) {
		return new String(decoct($i));
	}

	/**
	 * Returns a String object representing this Integer's value.
	 */
	public function __toString() {
		return new String($this->value);
	}

	/**
	 * Returns an Integer object holding the value of the specified String.
	 */
	public function valueOf(String $s) {
		return new Integer($s->__toString());
	}
          
	/**
	 * Compares this Integer object to another object.
	 *
	 * @param mixed $object
	 * @return int
	 */
	public function compareTo($object) {
		if($object instanceof Integer) {
			if($object->intValue() == $this->value) {
				return 0;
			} else
			if($object->intValue() > $this->value) {
				return 1;
			} else {
				return -1;
			}
		}

		return false;
	}

	/**
	 * Returns a hash code for this Integer.
	 */
	public function hashCode() {
		return crc32(serialize($this));
	}
}
	
?>
