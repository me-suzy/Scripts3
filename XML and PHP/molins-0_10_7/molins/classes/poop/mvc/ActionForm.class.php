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
 * @version $Id: ActionForm.class.php,v 1.7 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.mvc.ActionMapping');
import('poop.http.HttpRequest');
import('poop.mvc.ActionErrors'); 
import('poop.mvc.ActionMessage'); 

// abstract 
class ActionForm {

	/**
	 * @param ActionMapping $mapping
	 * @param HttpRequest $request
	 */
	public function reset(ActionMapping $mapping, HttpRequest $request) {
	}
	
	/**
	 * @param ActionMapping $mapping
	 * @param HttpRequest $request
	 * @return ActionErrors
	 */
	public function validate(ActionMapping $mapping, HttpRequest $request) {
	}
}

?>
