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
 * @version $Id: LoggingConfig.class.php,v 1.3 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.logging.config
 */
	
import('poop.logging.config.AppenderConfig');
	
class LoggingConfig {

	private $appenders;

	public function __construct() {
		$this->appenders = array();
	}

	/**
	 * @param AppenderConfig $appender
	 */
	public function addAppender(AppenderConfig $appender) {
		array_push($this->appenders, $appender);
	}

	/**
	 * @return array
	 */
	public function getAppenders() {
		return $this->appenders;
	}
}
	
?>
