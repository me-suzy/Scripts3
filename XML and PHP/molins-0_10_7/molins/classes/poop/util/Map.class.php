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
 * @version $Id: Map.class.php,v 1.4 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

interface Map {

	public function clear();

	/**
	 * @param string $key
	 * @return boolean
	 */
	public function containsKey($key);

	/**
	 * @param mixed $value
	 * @return boolean
	 */
	public function containsValue($value);

	/**
	 * @return Set
	 */
	public function entrySet();

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object);

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * @return Set
	 */
	public function keySet();

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function put($key, $value);

	/**
	 * @param Map $map
	 */
	public function putAll(Map $map);

	/**
	 * @param string $key
	 */
	public function remove($key);

	/**
	 * @return int
	 */
	public function size();

	/**
	 * @return array
	 */
	public function values();
}
	
?>
