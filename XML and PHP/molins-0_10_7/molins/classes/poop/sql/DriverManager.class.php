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
 * @version $Id: DriverManager.class.php,v 1.5 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

import('poop.sql.Connection');
 
class DriverManager {

	/**
	 * @param string $url
	 * @param string $user
	 * @param string $password
	 * @return Connection
	 */
	public static function getConnection($url, $user, $password) {
		$conn = new Connection($url, $user, $password);

		return $conn;
	}
}
	
?>
