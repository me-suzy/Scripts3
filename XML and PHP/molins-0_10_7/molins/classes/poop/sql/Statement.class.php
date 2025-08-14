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
 * @version $Id: Statement.class.php,v 1.7 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

import('poop.sql.ResultSet');
import('poop.sql.SQLException');
 
class Statement {

	private $pdo;

	/**
	 * @param PDO $pdo
	 */
	public function __construct(PDO $pdo) {
		$this->pdo = $pdo;
	}

	/**
	 * @param string $sql
	 * @return int
	 */
	public function executeUpdate($sql) {
		$affected = $this->pdo->exec($sql);
		if($affected == false) {
			$error = $this->pdo->errorInfo();
			throw new SQLException($error[0], $sql);
		}

		return $affected;
	}

	/**
	 * @param string $sql
	 * @return ResultSet
	 */
	public function executeQuery($sql) {
		$statement = $this->pdo->query($sql);

		return new ResultSet($statement);
	}

	public function close() {
	}
}

?>
