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
 * @version $Id: InsertClause.class.php,v 1.6 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.clauses
 */

import('poop.sql.clauses.AbstractClause');
import('poop.sql.SQLUtil');

class InsertClause extends AbstractClause {
	const SQL = 'INSERT INTO %s (%s) VALUES (%s)';

	private $columnsValues;

	public function __construct() {
		$this->columnsValues = array();
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 */
	public function addColumnValue($column, $value) {
		$this->columnsValues[$column] = $value;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$columns = implode(', ', array_keys($this->columnsValues));

		$values = array();
		
		foreach($this->columnsValues as $value) {
			array_push($values, SQLUtil::fieldFormat($value));
		}
		
		$values = implode(', ', $values);
		
		$sql = sprintf(self::SQL, $this->table, $columns, $values);

		return $sql;
	}
}

?>
