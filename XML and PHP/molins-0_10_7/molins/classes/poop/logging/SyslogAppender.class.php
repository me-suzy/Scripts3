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
 * @version $Id: SyslogAppender.class.php,v 1.5 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */
	
import('poop.logging.Appender');
	
class SyslogAppender extends Appender {

	public function init() {
		define_syslog_variables();

		openlog('molins', LOG_ODELAY | LOG_PID, LOG_USER);		
	}
	
	/**
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function log($level, $message, $exception = null) {
		syslog(LOG_ERR, $message);
	}

	public function __destruct() {
		closelog();
	}
}

?>
