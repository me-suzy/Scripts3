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
 * @version $Id: HashMap.class.php,v 1.4 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Map'); 

class HashMap implements Map {

	private $hash;

	public function __construct() {
		$this->hash = array();
	}
	
	public function clear() {
		$this->hash = array();
	}

	/**
	 * @param string $key
	 * @return boolean
	 */
	public function containsKey($key) {
		return array_key_exists($key, $this->hash);
	}

	/**
	 * @param mixed $value
	 * @return boolean
	 */
	public function containsValue($value) {
		return in_array($value, $this->hash);
	}

	/**
	 * @return array
	 */
	public function entrySet() {
		return array_values($this->hash);
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		return $this->hash[$key];
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return (count($this->hash) == 0);
	}

	/**
	 * @return array
	 */
	public function keySet() {
		return array_keys($this->hash);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function put($key, $value) {
		$this->hash[$key] = $value;
	}

	/**
	 * @param Map $map
	 */
	public function putAll(Map $map) {
	}

	/**
	 * @param string $key
	 */
	public function remove($key) {
		unset($this->hash[$key]);
	}

	/**
	 * @return int
	 */
	public function size() {
		return count($this->hash);
	}

	/**
	 * @return array
	 */
	public function values() {
		return array_values($this->hash);
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return $this->hash;
	}
}
	
?>
