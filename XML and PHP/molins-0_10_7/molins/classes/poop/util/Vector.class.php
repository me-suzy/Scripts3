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
 * @version $Id: Vector.class.php,v 1.8 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.AbstractList');
import('poop.util.IList');
import('poop.lang.Cloneable');

class Vector extends AbstractList implements IList, Cloneable {

	private $elements;

	public function __construct() {
		$this->elements = array();
	}
	
	public function size() {
		return count($this->elements);
	}

	public function isEmpty() {
		return ($this->size() == 0);
	}

	/**
	 * @param mixed $object
	 */
	public function contains($object) {
		foreach($this->elements as $element) {
			if($element->equals($object)) {
				return true;
			}
		}

		return false;
	}

	public function toArray() {
		return $this->elements;
	}

	/**
	 * @param mixed $object
	 */
	public function add($object) {
		array_push($this->elements, $object);
	}

	public function clear() {
		$this->elements = array();
	}

	/**
	 * @param int $index
	 */
	public function get($index) {

	}

	/**
	 * @param int $index
	 * @param mixed $p
	 */
	public function set($i, $p) {
	}

	/**
	 * @param int $index
	 */
	public function remove($index) {
	}

	/**
	 * @param mixed $object
	 */
	public function lastIndexOf($o) {
	}

	public function listIterator() {
	}

	/**
	 * @param int $fromIndex
	 * @param int $toIndex
	 */
	public function subList($fromIndex, $toIndex) {
	}

	public function iterator() {
	}
}

?>
