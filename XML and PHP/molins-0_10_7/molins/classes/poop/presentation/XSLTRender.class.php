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
 * @version $Id: XSLTRender.class.php,v 1.4 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.presentation
 */

import('poop.presentation.Renderizable');
 
import('poop.http.HttpRequest');
import('poop.http.HttpResponse');
 
import('poop.xml.XMLTransformer');

class XSLTRender implements Renderizable {

	/**
	 * @param HttpRequest $request
	 * @param HttpResponse $response
	 */
	public static function render(HttpRequest $request, HttpResponse $response) {
		$xsl = "$mod/$fx.xsl";
		$xml = "http://127.0.0.1:80/".CONTEXT_PATH."/index.php?mvc=xml&mod=$mod&fx=$fx";

		print XMLTransformer::transform($xsl, $xml);
	}
}

?>
