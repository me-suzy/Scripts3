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
 * @version $Id: JoinClause.class.php,v 1.4 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.clauses
 */

class JoinClause {
	private $table;
	private $columns;
	
	private $left;
	private $right;

	public function __construct() {
		$this->columns = array();
	}
	
	/**
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @param string $table
	 */
	public function setTable($table) {
		$this->table = $table;
	}

	/**
	 * @param string $name
	 * @param string $alias
	 */
	public function addColumn($name, $alias) {
		$this->columns[$name] = $alias;
	}

	/**
	 * @return string
	 */
	public function getColumns() {
		$columns = '';
		
		foreach($this->columns as $column => $alias) {
			$columns .= $this->table.'.'.$column.' '.$alias;
		}

		return $columns;
	}
	
	/**
	 * @return string
	 */
	public function getLeft() {
		return $this->left;
	}

	/**
	 * @param string $left
	 */
	public function setLeft($left) {
		$this->left = $left;
	}

	/**
	 * @return string
	 */
	public function getRight() {
		return $this->right;
	}

	/**
	 * @param string $right
	 */
	public function setRight($right) {
		$this->right = $right;
	}
}

