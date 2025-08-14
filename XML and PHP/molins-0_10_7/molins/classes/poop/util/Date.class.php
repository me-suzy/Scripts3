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
 * @version $Id: Date.class.php,v 1.4 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */
 
class Date {

	private $date;

	/**
	 * @param int $date
	 */	
	public function __construct($date = null) {
		if(is_null($date)) {
			$this->date = System::currentTimeMillis();
		} else {
			$this->date = $date;
		}
	}

	/**
	 * @param int $time
	 */
	public function setTime($time) {
		$this->date = $time;
	}

	/**
	 * @param Date $when
	 * @return boolean
	 */
	public function after(Date $when) {
		return getMillisOf($this) > getMillisOf($when);
	}
	
	/**
	 * @param Date $when
	 * @return int
	 */
	private function getMillisOf(Date $date) {
	}

	/**
	 * @param Date $date
	 * @return int
	 */
	public function compareTo(Date $date) {
	}
}
	
?>
