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
 * @version $Id: Renderizable.class.php,v 1.4 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.presentation
 */
	
import('poop.http.HttpRequest');
import('poop.http.HttpResponse');
 
interface Renderizable {

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */
	public static function render(HttpRequest $request, HttpResponse $response);
}

?>
