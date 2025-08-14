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
 * @version $Id: ListIterator.class.php,v 1.5 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Iterator');
 
interface ListIterator extends Iterator {

	/**
	 * @return boolean
	 */
	public function hasNext();

	/**
	 * @return mixed
	 */
	public function next();
	
	/**
	 * @return boolean
	 */
	public function hasPrevious();

	/**
	 * @return mixed
	 */
	public function previous();
	
	/**
	 * @return int
	 */
	public function nextIndex();
	
	/**
	 * @return int
	 */
	public function previousIndex();
	public function remove();
	
	/**
	 * @param mixed $o
	 */
	public function set($o);

	/**
	 * @param mixed $o
	 */
	public function add($o);
}

?>
