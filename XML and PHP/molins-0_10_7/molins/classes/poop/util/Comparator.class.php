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
 * @version $Id: Comparator.class.php,v 1.3 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */

interface Comparator {
	
	/**
	 * @param mixed $o1
	 * @param mixed $o2
	 * @return boolean
	 */
	public function compare($o1, $o2);

	/**
	 * @param mixed $o
	 * @return boolean
	 */
	public function equals($o);
}

?>
