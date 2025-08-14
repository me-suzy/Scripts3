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
 * @version $Id: Connection.class.php,v 1.10 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

import('poop.sql.Statement');
import('poop.sql.SQLException');
import('poop.sql.PreparedStatement');
 
class Connection {

	private $url;
	private $user;
	private $password;
	
	private $pdo;

	private $autoCommit;
	
	/**
	 * @param string $url
	 * @param string $user
	 * @param string $password
	 */
	public function __construct($url, $user, $password) {
		$this->url = $url;
		$this->user = $user;
		$this->password = $password;

		$this->connect();
	}

	/**
	 * @param boolean $autoCommit
	 */
	public function setAutoCommit($autoCommit) {
		$this->autoCommit = $autoCommit;
	}

	public function commit() {
		if($this->autoCommit == false) {
			$this->pdo->commit();
		}
	}

	public function rollback() {
		if($this->autoCommit == false) {
			$this->pdo->rollback();
		}
	}

	private function connect() {
		try {
			$this->pdo = new PDO($this->url, $this->user, $this->password);
			$this->pdo->setAttribute(PDO_ATTR_PERSISTENT, true);
			$this->pdo->setAttribute(PDO_MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		} catch (PDOException $e) {
			throw new SQLException($e->getMessage(), $this->url);
		}
	}

	/**
	 * @return Statement
	 */
	public function createStatement() {
		return new Statement($this->pdo);
	}

	/**
	 * @param string $sql
	 * @return PreparedStatement
	 */
	public function prepareStatement($sql) {
		return new PreparedStatement($this->pdo, $sql);
	}

	/**
	 * @param string $sql
	 */
	public function prepareCall($sql) {
	}

	/**
	 * @return PDO
	 */
	public function getPDO() {
		return $this->pdo;
	}
	
	public function close() {
		unset($this->pdo);
	}
}
	
?>
