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
 * @version $Id: SQLUtil.class.php,v 1.8 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

import('poop.sql.DataSource');

class SQLUtil {

	/**
	 * @param string $sql
	 * @return PDOStatement
	 */
	public static function query($sql) {
		$dataSource = DataSource::getDefaultDataSource();
		$connection = $dataSource->getConnection();
		$pdo = $connection->getPDO();
		return $pdo->query($sql);
	}
	
	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public static function fieldFormat($value) {
		if(is_int($value)) {
			return intval($value);
		} else
		if(is_float($value)) {
			return floatval($value);
		} else
		if(defined($value)) {
			return strval(constant($value));
		} else
		if(is_string($value)) {
			return '\''.addslashes($value).'\'';
		} else {
			return $value;
		}
	}
}

?>
