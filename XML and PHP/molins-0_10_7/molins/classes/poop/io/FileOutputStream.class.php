<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: FileOutputStream.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.io
 */
	
import('poop.io.OutputStream');
	
class FileOutputStream extends OutputStream {

	public function close() {
	}

	public function finalize() {
	}

	/**
	 * @return FileDescriptor
	 */
	public function getFD() {
	}

	/**
	 * @param mixed &$b
	 * @param int $off
	 * @param int $len
	 * @return mixed
	 */
	public function write($b, $off = null, $len = null) {
	}
}
	
?>
