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
 * @version $Id: UpdateClause.class.php,v 1.7 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.clauses
 */

import('poop.sql.clauses.AbstractClause');
import('poop.sql.SQLUtil');

class UpdateClause extends AbstractClause {
	const SQL = 'UPDATE %s SET %s WHERE %s';
	
	private $columnsValues;
	private $conditions;

	public function __construct() {
		$this->columnsValues = array();
		$this->conditions = array();
	}
	
	/**
	 * @param string $column
	 * @param mixed $value
	 */
	public function addColumnValue($column, $value) {
		$this->columnsValues[$column] = $value;
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
	 * @return string
	 */
	public function __toString() {
		$columnValues = array();
	
		foreach($this->columnsValues as $column => $value) {
			array_push($columnValues, sprintf('%s = %s', $column, SQLUtil::fieldFormat($value)));
		}
		
		$columnValues = implode(', ', $columnValues);
		$conditions = implode(' AND ', $this->conditions);
		
		$sql = sprintf(self::SQL, $this->table, $columnValues, $conditions);

		return $sql;
	}
}

?>
