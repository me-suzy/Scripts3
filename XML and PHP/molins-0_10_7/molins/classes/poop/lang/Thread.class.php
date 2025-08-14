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
 * @version $Id: Thread.class.php,v 1.1 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.lang
 */
 
import('poop.lang.Runnable');
 
class Thread implements Runnable {

	/**
	 * Returns this thread's name.
	 *
	 * @return String
	 */
	public function getName() {
	}

	/**
	 * Returns this thread's priority.
	 *
	 * @return int
	 */
	public function getPriority() {
	}

	/**
	 * Interrupts this thread.
	 */
	public function interrupt() {
	}

	/**
	 * Tests if this thread is alive.
	 *
	 * @return boolean
	 */
	public function isAlive() {
		
	}
	
	/**
	 * Causes this thread to begin execution.
	 */
	public function start() {
	}
	
	public function run() {
	}

	/**
	 * Causes this thread to stop execution.
	 */
	public function stop() {
	}
}
	
?>
