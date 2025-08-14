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
 * @version $Id: ClassUtil.class.php,v 1.1 2005/10/19 15:55:18 slizardo Exp $
 * @package poop.lang.reflect
 */

class ClassUtil {

	/**
	 * @param string $package
	 * @return string
	 */
	public static function getClassFor($package) {
		$parts = explode('.', $package);

		return array_pop($parts);
	}

	/**
	 * @see import($classPath)
	 * @param string $classPath
	 * @return object
	 */
	public static function importInstance($classPath) {
		import($classPath);

		$className = ClassUtil::getClassFor($classPath);
				
		$instance = new $className();

		return $instance;
	}	
}
	
?>
