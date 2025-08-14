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
 * @version $Id: AbstractClause.class.php,v 1.3 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.clauses
 */

abstract class AbstractClause {
	const NULL = 'NULL';	
	const NOW = 'NOW()';	
	
	protected $table;

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
}

?>
