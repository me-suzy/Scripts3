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
 * @version $Id: XMLRender.class.php,v 1.4 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.presentation
 */

import('poop.presentation.Renderizable');
 
import('poop.http.HttpRequest');
import('poop.http.HttpResponse');
 
class XMLRender implements Renderizable {

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */
	public static function render(HttpRequest $request, HttpResponse $response) {
		$file = "$mod/$fx.xml.php";

		if(!file_exists($file)) {
			throw new FileNotFoundException($file);
		} else {
			ob_start();
			require_once $file;
			$xml = ob_get_contents();
			ob_end_clean();

			$response->setContentType('text/xml');

			print $xml;
		}
	}
}
	
?>
