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
 * @version $Id: XMLLayout.class.php,v 1.4 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */

import('poop.logging.Layout');
import('poop.logging.Level');

class XMLLayout extends Layout {

	/**
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function format($level, $message, $exception) {
		$dom = new DomDocument();
		$root = $dom->createElement('log');
		$root = $dom->appendChild($root);

		$root->setAttribute('date', date('F j, Y, g:i a'));
		$root->setAttribute('level', Level::labelFor($level));
		
		$child = $dom->createTextNode($message);
		$child = $root->appendChild($child);

		return $dom->saveXML();
	}
}
	
?>
