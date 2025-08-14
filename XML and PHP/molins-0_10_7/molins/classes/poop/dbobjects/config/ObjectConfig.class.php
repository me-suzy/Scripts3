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
 * @version $Id: ObjectConfig.class.php,v 1.3 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.dbobjects.config
 */

class ObjectConfig {

	private $class;
	private $table;
	private $primaryKey;

	/**
	 * @param string $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}
	
	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @param string $table
	 */
	public function setTable($table) {
		$this->table = $table;
	}

	/**
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @param string $primaryKey
	 */
	public function setPrimaryKey($primaryKey) {
		$this->primaryKey = $primaryKey;
	}
	
	/**
	 * @return string
	 */
	public function getPrimaryKey() {
		return $this->primaryKey;
	}
}

?>
