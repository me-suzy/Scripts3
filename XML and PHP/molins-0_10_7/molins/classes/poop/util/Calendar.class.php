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
 * @version $Id: Calendar.class.php,v 1.6 2005/10/12 21:01:03 slizardo Exp $
 * @package poop.util
 */

abstract class Calendar {

	const YEAR = 0;
	const MONTH = 1;
	const DATE = 2;
	const DAY_OF_MONTH = 3;
	const DAY_OF_YEAR = 4;
	const DAY_OF_WEEK = 5;
	const DAY_OF_WEEK_IN_MONTH = 6;
	const HOUR_OF_DAY = 7;
	const HOUR = 8;
	const MINUTE = 9;
	const SECOND = 10;
	const MILLISECOND = 11;
	const WEEK_OF_YEAR = 12;
	const WEEK_OF_MONTH = 13;
	const AM_PM = 14;
	
	/**
	 * @param int $field
	 * @param int $amount
	 */
	public function add($field, $amount) {
	}

	/**
	 * @param int $when
	 * @return boolean
	 */
	public function after($when) {
	}

	/**
	 * @param int $when
	 * @return boolean
	 */
	public function before($when) {
	}

	/**
	 * @param int $field
	 */
	public function clear($field = null) {
	}

	public function complete() {
	}

	/**
	 * @param TimeZone $timeZone
	 * @param Locale $locale
	 * @return Calendar
	 */
	public function getInstance(TimeZone $timeZone = null, Locale $locale = null) {
	}
	
	/**
	 * @param int $field
	 * @return int
	 */
	public function get($field) {		
	}

	/**
	 * @param int $field
	 * @return boolean
	 */
	public function fieldIsSet($field) {
	}

	/**
	 * @param int $field
	 * @return int
	 */
	public function getActualMaximum($field) {
	}

	/**
	 * @param int $field
	 * @return int
	 */
	public function getActualMinimum($field) {
	}

	/**
	 * @return int
	 */
	public function getFirstDayOfWeek() {
	}

	/**
	 * @param int $field
	 * @param int $value
	 */
	public function set($field, $value) {
	}

	/**
	 * @return Date
	 */
	public function getTime() {
	}

	/**
	 * @param Date $date
	 */
	public function setTime(Date $date) {
	}
}

?>
