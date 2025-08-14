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
 * @version $Id: StringBuffer.class.php,v 1.7 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.lang
 */

/**
 * Genera un buffer al que se le pueden ir concatenando cadenas.
 */
class StringBuffer {

	private $buffer;
	
	/**
	 * @param string $buffer
	 */
	public function __construct($buffer = '') {
		$this->buffer = $buffer;
	}

	/**
	 * @param mixed $mixed
	 * @return StringBuffer
	 */
	public function append($mixed) {
		if(is_object($mixed)) {
			$this->buffer .= $mixed->__toString();
		} else {
			$this->buffer .= $mixed;
		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function length() {
		return strlen($this->buffer);
	}
	
	/**
	 * @return string
	 */
	public function __toString() {
		return $this->buffer;
	}
}
	
?>
