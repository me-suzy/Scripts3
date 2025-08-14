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
 * @version $Id: FileUtil.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.io
 */

class FileUtil {

	/**
	 * Lee el contenido de un fichero y lo devuelve.
	 *
	 * @param string $fileName
	 * @return string
	 */
	public static function getContent($fileName) {
		if(file_exists($fileName)) {
			return file_get_contents($fileName);
		} else {
			throw new FileNotFoundException($fileName);
		}
	}
}

?>
