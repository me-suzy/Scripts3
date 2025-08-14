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
 * @version $Id: InputStream.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.io
 */

abstract class InputStream {

	/**
	 * @return int
	 */
	public function available() {
	}

	public function close() {
	}

	/**
	 * @param int $readLimit
	 */
	public function mark($readlimit) {
	}

	public function markSupported() {
	}

	/**
	 * @param mixed &$bytes
	 * @param int $off
	 * @param int $len
	 * @return mixed
	 */
	abstract public function read(&$bytes, $off = null, $len = null);

	public function reset() {
	}

	/**
	 * @param int $n
	 */
	public function skip($n) {
	}
}

?>
