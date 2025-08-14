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
 * @version $Id: WindowState.class.php,v 1.3 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.portlet
 */

class WindowState {

	public static final $NORMAL = new WindowState('normal');

	public static final $MAXIMIZED = new WindowState('maximized');

	public static final $MINIMIZED = new WindowState('minimized');

	private $state;

	/**
	 * @param string $state
 	 */	 
	private function __construct($state) {
		$this->state = $state;
	}

	/**
	 * @param string $state
 	 */	 
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @return boolean
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param mixed $object
	 * @return boolean
 	 */	 
	public function equals($object) {
		if($object instanceof WindowState) {
			return ($object->getState() == $this->state);
		}

		return false;
	}
}
	
?>
