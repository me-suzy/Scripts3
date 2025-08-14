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
 * @version $Id: IList.class.php,v 1.5 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Collection');
 
interface IList extends Collection {

	/**
	 * @return int
	 */
	public function size();
	
	/**
	 * @return boolean
	 */
	public function isEmpty();
	
	/**
	 * @return boolean
	 */
	public function contains($o);

	/**
	 * @return array
	 */
	public function toArray();

	/**
	 * @param mixed $object
	 */
	public function add($o);
	public function clear();
	
	/**
	 * @param int $index
	 */
	public function get($index);
	
	/**
	 * @param int $field
	 * @param mixed $object
	 */
	public function set($index, $o);
	
	/**
	 * @param int $index
	 */
	public function remove($index);
	
	/**
	 * @param mixed $o
	 * @return int
	 */
	public function lastIndexOf($o);

	/**
	 * @return Iterator
	 */
	public function listIterator();
	
	/**
	 * @param int $fromIndex
	 * @param int $toIndex
	 * @return IList
	 */
	public function subList($fromIndex, $toIndex);	
}
 
?>
