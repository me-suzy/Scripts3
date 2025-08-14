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
 * @version $Id: FileInputStream.class.php,v 1.6 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.io
 */

import('poop.io.InputStream');
import('poop.io.FileDescriptor');
import('poop.io.EOFException');

class FileInputStream extends InputStream {

	private $fd;

	/**
	 * @param string $fileName
	 */
	public function __construct($fileName) {
		$this->fd = fopen($fileName, 'r');
	}

	public function finalize() {
	}

	/**
	 * @return FileDescriptor
	 */
	public function getFD() {
	}

	/**
	 * @param mixed &$bytes
	 * @param int $off
	 * @param int $len
	 * @return mixed
	 */
	public function read(&$bytes, $off = null, $len = null) {
		if(!is_null($off)) {
			fseek($this->fd, $off, SEEK_CUR);
		}

		if(feof($this->fd)) {
			throw new EOFException();
		}
		$bytes = fread($this->fd, (is_null($len) ? 1024 : $len));

		if($bytes) {
			return strlen($bytes);
		} else {
			return -1;
		}
	}

	/**
	 * @return mixed
	 */
	public function getFileData() {
		while($data = fread($this->fd, 1024)) {
			$result .= $data;
		}

		return $result;
	}

	public function close() {
		fclose($this->fd);
	}
}

?>
