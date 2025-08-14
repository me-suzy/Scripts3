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
 * @version $Id: PreparedStatement.class.php,v 1.8 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

class PreparedStatement {

	private $pdo;
	private $sql;
	
	private $values;
	
	/**
	 * @param PDO $pdo
	 * @param string $sql
	 */
	public function __construct(PDO $pdo, $sql) {
		$this->pdo = $pdo;
		$this->sql = $sql;

		$this->values = array();
	}
	
	/**
	 * @param int $index
	 * @param int $value
	 */
	public function setInt($index, $value) {
		$this->values[$index-1] = intval($value);
	}

	/**
	 * @param int $index
	 * @param string $value
	 */
	public function setString($index, $value) {
		$this->values[$index-1] = strval($value);
	}

	/**
	 * @return int
	 */
	public function executeUpdate() {
		$statement = $this->pdo->prepare($this->sql);
		$statement->execute($this->values);
		$affected = $statement->rowCount();

		return $affected;
	}

	/**
	 * @return int
	 */
	public function executeQuery() {
		$statement = $this->pdo->prepare($this->sql);
		$statement->execute($this->values);
		$affected = $statement->rowCount();

		return $affected;
	}
}
	
?>
