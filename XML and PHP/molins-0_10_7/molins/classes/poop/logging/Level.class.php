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
 * @version $Id: Level.class.php,v 1.8 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.logging
 */

class Level {
	const DEBUG 	= 0;
	const INFO 	= 1;
	const WARN 	= 2;
	const ERROR 	= 3;
	const FATAL 	= 4;

	/**
	 * El metodo convierte una constante que representa<br />
	 * un nivel de log, en su representacion string.
	 *
	 * @param int $level
	 * @return string
	 */
	public static function labelFor($level) {
		switch($level) {
			case self::DEBUG: 
				return 'DEBUG';

			case self::INFO: 
				return 'INFO';

			case self::WARN: 
				return 'WARN';

			case self::ERROR: 
				return 'ERROR';

			case self::FATAL: 
				return 'FATAL';
			
			default:
				throw new Exception('unknow level');
		}
	}
}
	
?>
