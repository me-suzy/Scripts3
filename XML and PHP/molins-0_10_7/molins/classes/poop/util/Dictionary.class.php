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
 * @version $Id: Dictionary.class.php,v 1.3 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */

abstract class Dictionary {

	public abstract function size();
	public abstract function isEmpty();
	public abstract function keys();
	public abstract function elements();
	
	/**
	 * @param string $key
	 * @return int
	 */
	public abstract function get($key);

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public abstract function put($key, $value);

	/**
	 * @param string $key
	 */	
	public abstract function remove($key);
}
	
?>
