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
 * @version $Id: XHTMLLayout.class.php,v 1.4 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */

import('poop.logging.Layout');
import('poop.logging.Level');

class XHTMLLayout extends Layout {

	const DEFAULT_FORMAT = '%s %LEVEL% %MESSAGE%';

	/**	
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function format($level, $message, $exception) {
		$format = self::DEFAULT_FORMAT;

		if($this->propertyExists('format')) {
			$format = $this->getProperty('format');
		}
		
		$format = str_replace(
			array('%LEVEL%', '%MESSAGE%'),
			array(Level::labelFor($level), $message),
			$format
		);
		
		$line = sprintf($format, date('F j, Y, g:i a'));

		return $line;
	}
}
	
?>
