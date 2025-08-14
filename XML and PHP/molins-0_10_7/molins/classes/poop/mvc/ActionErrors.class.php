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
 * @version $Id: ActionErrors.class.php,v 1.7 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.mvc.ActionMessage');
import('poop.mvc.ActionMessages');
 
class ActionErrors extends ActionMessages {

	const GLOBAL_ERROR = 1;

	/**
	 * @param string $key
	 * @param ActionMessage $value
	 */
	public function add($key, ActionMessage $value) {
		parent::add($key, $value);
	}
}
	
?>
