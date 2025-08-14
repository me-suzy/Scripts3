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
 * @version $Id: ArrayList.class.php,v 1.4 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */

import('poop.util.IList');

class ListIterator {

	private $list;
	private $i;

	/**
	 * @param IList $list
	 */
	public function __construct(IList $list) {
		$this->list = $list;
		$this->i = 0;
	}

	/**
	 * @return boolean
	 */
	public function hasNext() {
		return ($this->i < $this->list->size());
	}

	/**
	 * @return mixed
	 */
	public function next() {
		return $this->list->get($this->i++);
	}
}

class ArrayList implements IList {

	private $array;

	public function __construct() {
		$this->array = array();
	}
	
	/**
	 * @return int
	 */
	public function size() {
		return count($this->array);
	}
	
	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return ($this->size() == 0);
	}
	
	/**
	 * @param mixed $o
	 * @return boolean
	 */
	public function contains($o) {
	}
	
	/**
	 * @return array
	 */
	public function toArray() {
		return $this->array;
	}
	
	/**
	 * @param mixed $o
	 */
	public function add($o) {
		array_push($this->array, $o);
	}
	
	public function clear() {
		$this->array = array();
	}
	
	/**
	 * @param int $index
	 * @return mixed
	 */
	public function get($index) {
		return $this->array[$index];
	}
	
	/**
	 * @param int $index
	 * @param mixed $o
	 */
	public function set($index, $o) {
		$this->array[$index] = $o;
	}

	/**
	 * @param int $index
	 */
	public function remove($index) {
		unset($this->array[$index]);		
	}

	/**
	 * @param mixed $o
	 * @return int
	 */
	public function lastIndexOf($o) {
	}
	
	/**
	 * @return Iterator
	 */
	public function listIterator() {
	}
	
	/**
	 * @param int $fromIndex
	 * @param int $toIndex
	 */
	public function subList($fromIndex, $toIndex) {
	}

	/**
	 * @return Iterator
	 */
	public function iterator() {
		return new ListIterator($this);
	}
}
 
?>
