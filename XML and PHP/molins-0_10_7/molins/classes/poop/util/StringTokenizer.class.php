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
 * @version $Id: StringTokenizer.class.php,v 1.5 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Enumeration');
 
class StringTokenizer implements Enumeration {

	private $str;
	private $delim;
	private $returnDelims;

	private $tokens;
	private $current;
	
	/**
	 * @param string $str
	 * @param string $delim
	 * @param boolean $returnDelims
	 */
	public function __construct($str, $delim = "\s|\t|\n|\r|\f", $returnDelims = false) {
		$this->str = $str;
		$this->delim = $delim;
		$this->returnDelims = $returnDelims;

		$this->tokens = split($delim, $str);
	}
	
	/**
	 * @return boolean
	 */
	public function hasMoreTokens() {
		return ($this->current = array_shift($this->tokens));
	}

	/**
	 * @return mixed
	 */
	public function nextToken() {
		return ($this->hasMoreTokens() ? $this->current : null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasMoreElements() {
		return $this->hasMoreTokens();
	}

	/**
	 * @return mixed
	 */
	public function nextElement() {
		return $this->nextToken();
	}

	/**
	 * @return int
	 */
	public function countTokens() {
		return count($this->tokens);
	}
}
	
?>
