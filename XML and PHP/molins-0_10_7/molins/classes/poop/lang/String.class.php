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
 * @version $Id: String.class.php,v 1.11 2005/10/14 09:52:53 slizardo Exp $
 * @package poop.lang
 */
 
import('poop.lang.Comparable');
import('poop.lang.StringBuffer');
 
class String implements Comparable {

	private $value;

	/**
	 * @param mixed $value
	 */
	public function __construct($value) {
		$this->value = strval($value);
	}
	
	/**
	 * @param int $index
	 * @return char
	 */
	public function charAt($index) {
		return $this->value[$index];
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function compareTo($object) {
		return strcmp($object->__toString(), $this->value);
	}

	/**
	 * @param String $object
	 * @return boolean
	 */
	public function compareToIgnoreCase(String $object) {
		return strcasecmp($object->__toString(), $this->value);
	}

	/**
	 * @param String $str
	 * @return String
	 */
	public function concat(String $str) {
		return new String($this->value.$str->__toString());
	}

	/**
	 * @param StringBuffer $buffer
	 * @return boolean
	 */
	public function contentEquals(StringBuffer $buffer) {
		return ($buffer->__toString() == $this->value);
	}

	/**
	 * @param String $suffix
	 * @return boolean
	 */
	public function endsWith(String $suffix) {
		return (substr($this->value, $suffix->length(), strlen($this->value)) == $suffix->__toString());
	}
	
	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof String) {
			return ($object->compareTo($this) == 0);
		}

		return false;
	}

	/**
	 * @param String $str
	 * @return boolean
	 */
	public function equalsIgnoreCase(String $str) {
	}

	/**
	 * @param String $str
	 * @return int 
	 */
	public function indexOf(String $str) {
		return strpos($this->value, $str);
	}

	/**
	 * @param String $str
	 * @return int
	 */
	public function lastIndexOf(String $str) {
	}
	
	/**
	 * @return int
	 */
	public function length() {
		return strlen($this->value);
	}

	/**
	 * @param string $a
	 * @param string $b
	 * @return String
	 */
	public function replace($a, $b) {
	}

	/**
	 * @param string $a
	 * @param string $b
	 * @return String
	 */
	public function replaceAll($a, $b) {
		$string = preg_replace("/$a/", $b, $this->value);
		return new String($string);
	}

	/**
	 * @param string $a
	 * @param string $b
	 * @return String
	 */
	public function replaceFirst($a, $b) {
		$string = preg_replace("/$a/", $b, $this->value, 1);
		return new String($string);
	}

	/**
	 * @param string $a
	 * @return array
	 */
	public function split($a) {
		$array = split($a, $this->value);
		for($i = 0; $i < count($array); $i++) {
			$array[$i] = new String($array[$i]);
		}

		return $array;
	}

	/**
	 * @param string $a
	 * @return boolean
	 */
	public function startsWith($a) {
		return (substr($this->value, 0, strlen($a)) == $a);
	}

	/**
	 * @param string $begin
	 * @param string $end
	 * @return String
	 */
	public function substring($begin, $end) {
		return new String(substr($this->value, $begin, $end));
	}

	/**
	 * @return string
	 */
	public function toUpperCase() {
		return strtoupper($this->value);
	}

	/**
	 * @return string
	 */
	public function toLowerCase() {
		return strtolower($this->value);
	}

	/**
	 * @return string
	 */
	public function trim() {
		return trim($this->value);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->value;
	}

	/**
	 * @param mixed $var
	 * @return String
	 */
	public function valueOf($var) {
		return new String(strval($var));
	}
}
	
?>
