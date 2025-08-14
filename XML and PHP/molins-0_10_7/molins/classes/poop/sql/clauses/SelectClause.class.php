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
 * @version $Id: SelectClause.class.php,v 1.5 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.clauses
 */

import('poop.sql.clauses.AbstractClause');
import('poop.sql.SQLUtil');

class SelectClause extends AbstractClause {
	const SQL 		= 'SELECT %s FROM %s';
	const SQL_WHERE 	= ' WHERE ';
	const SQL_ORDER_BY 	= ' ORDER BY %s %s';
	const SQL_LIMIT_OFFSET 	= ' LIMIT %d, %d';

	private $columns;
	private $conditions;

	private $orderByColumn;
	private $orderByOrientation;

	private $limitMax;
	private $limitOffset;
	
	private $joins;
	
	public function __construct() {
		$this->columns = array();
		$this->conditions = array();
		$this->joins = array();
	}

	public function setColumns() {
		foreach(func_get_args() as $column) {
			array_push($this->columns, $column);
		}
	}
	
	/**
	 * @param string $name
	 */
	public function addColumn($name) {
		array_push($this->columns, $name);
	}

	/**
	 * @param mixed $join
	 */
	public function addJoin($join) {
		array_push($this->joins, $join);
	}	
	
	public function addWhere() {
		$sql = null;

		if(func_num_args() == 1) {
			$sql = func_get_arg(0);
		} else
		if(func_num_args() > 1) {
			$argv = func_get_args();
			$format = array_shift($argv);
			$sql = vsprintf($format, $argv);
		} else {
			throw new WrongNumberOfArgumentsException(__METHOD__);
		}

		array_push($this->conditions, $sql);
	}

	/**
	 * @param string $column
	 * @param string $orientation
	 */
	public function setOrderBy($column, $orientation = 'DESC') {
		$this->orderByColumn = $column;
		$this->orderByOrientation = $orientation;
	}

	/**
	 * @param int $max
	 * @param int $offset
	 */
	public function setLimit($max, $offset = 0) {
		$this->limitMax = $max;
		$this->limitOffset = $offset;
	}

	/**
	 * @return string
	 */
	private function buildColumns() {
		$columns = null;

		if($this->hasJoins()) {
			if(count($this->columns) == 0) {
				$columns = $this->table.'.*';
			} else {
				array_walk($this->columns, array('SelectClause', 'prepareColumn'), $this->table);
				$columns = implode(', ', $this->columns);
			}
			foreach($this->joins as $join) {
				$columns .= ', '.$join->getColumns();
			}
		} else {
			if(count($this->columns) == 0) {
				$columns = '*';
			} else {
				$columns = implode(', ', $this->columns);
			}
		}
		
		return $columns;
	}

	/**
	 * @param string $value
	 * @param string $key
	 * @param string $table
	 */
	public function prepareColumn(&$value, $key, $table) {
		$value = $table.'.'.$value;
	}

	/**
	 * @return string
	 */
	private function buildFrom() {
		$from = $this->table;
	
		if($this->hasJoins()) {
			foreach($this->joins as $join) {
				$from .= ' LEFT JOIN '.$join->getTable();
				$from .= ' ON '.$this->table.'.'.$join->getLeft().' = '.$join->getTable().'.'.$join->getRight();
			}
		}

		return $from;
	}

	/**
	 * @return boolean
	 */
	private function hasJoins() {
		return (count($this->joins) > 0);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$columns = $this->buildColumns();
		$from = $this->buildFrom();
		
		$sql = sprintf(self::SQL, $columns, $from);		
		
		if(count($this->conditions) > 0) {
			$sql .= self::SQL_WHERE.implode(' AND ', $this->conditions);
		}

		if(!is_null($this->orderByColumn)) {
			$sql .= sprintf(self::SQL_ORDER_BY, $this->orderByColumn, $this->orderByOrientation);
		}

		if(!is_null($this->limitMax)) {
			$sql .= sprintf(self::SQL_LIMIT_OFFSET, $this->limitOffset, $this->limitMax);
		}

		return $sql;
	}
}

?>
