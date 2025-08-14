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
 * @version $Id: AbstractCollection.class.php,v 1.3 2005/10/12 21:01:02 slizardo Exp $
 * @package poop.util
 */

abstract class AbstractCollection implements Collection {

	public abstract function iterator();
	public abstract function size();
	
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
		$iterator = $this->iterator();
		if($o == null) {
			while($iterator->hasNext()) {
				if($iterator->next() === null) {
					return true;
				}
			}
		} else {
			while($iterator->hasNext()) {
				if($o->equals($iterator->next())) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$array = array();

		$iterator = $this->iterator();
		while($iterator->hasNext()) {
			array_push($array, $iterator->next());
		}
		
		return $array;
	}

	/**
	 * @param mixed $o
	 */
	public function add($o) {
	}

	/**
	 * @param mixed $o
	 */
	public function remove($o) {
	}

	/**
	 * @param mixed $o
	 * @return boolean
	 */
	public function containsAll($o) {
	}

	/**
	 * @param mixed $o
	 */
	public function addAll($o) {
	}

	/**
	 * @param mixed $o
	 */
	public function removeAll($o) {
	}

	/**
	 * @param mixed $o
	 */
	public function retainAll($o) {
	}

	public function clear() {
		$iterator = $this->iterator();
		while($iterator->hasNext()) {
			$iterator->next();
			$iterator->remove();
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$stringBuffer = new StringBuffer();

		$stringBuffer->append('[');
		$iterator = $this->iterator();
		$hasNext = $iterator->hasNext();
		while($hasNext) {
			$o = $iterator->next();
			$stringBuffer->append($o == $this ? '(this Collection)' : String::valueOf($o));
			
			$hasNext = $iterator->hasNext();
			if($hasNext) {
				$stringBuffer->append(', ');
			}
		}
		$stringBuffer->append(']');
		
		return $stringBuffer->__toString();
	}
}

?>
