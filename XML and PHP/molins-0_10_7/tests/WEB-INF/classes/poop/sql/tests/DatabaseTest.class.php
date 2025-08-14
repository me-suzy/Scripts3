<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: DatabaseTest.class.php,v 1.6 2005/09/29 15:11:48 slizardo Exp $
 * @package poop.sql.tests
 */

import('poop.qa.TestCase');

import('poop.sql.DriverManager');
import('poop.sql.Connection');
import('poop.sql.Statement');
import('poop.sql.PreparedStatement');

class DatabaseTest extends TestCase {

	private $conn;
	private $statement;
	
	public function testConnection() {
		$this->conn = DriverManager::getConnection('mysql:host=127.0.0.1;dbname=Molins_tests', 'molins', 'tests');

		self::assert($this->conn instanceof Connection);
	}

	public function testStatement() {
		$this->statement = $this->conn->createStatement();
		self::assert($this->statement instanceof Statement);
	}

	public function testPreparedStatement() {
		$resultSet = $this->statement->executeQuery('SELECT name FROM table_one WHERE id = 1');
		self::assert($resultSet->next());
		self::assert($resultSet->getString(1) == 'slizardo');
		self::assert($resultSet->getString('name') == 'slizardo');
		
		$preparedStatement = $this->conn->prepareStatement('UPDATE table_one SET name = ? WHERE id = ?');
		$preparedStatement->setString(1, 'SLizardo');
		$preparedStatement->setInt(2, 1);
		$affectedRows = $preparedStatement->executeUpdate();

		self::assert($affectedRows == 1);
/*
		$affectedRows = $this->conn->createStatement()->executeUpdate("UPDATE table_one SET name = 'slizardo' WHERE id = 1");*/
	}
}

?>
