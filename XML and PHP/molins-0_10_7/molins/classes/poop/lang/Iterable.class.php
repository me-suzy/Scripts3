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
 * @version $Id: Iterable.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.lang
 */

interface Iterable {

	/**
	 * @return Iterator
	 */
	public function iterator();
}
	
?>
