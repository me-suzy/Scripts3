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
 * @version $Id: DbObject.class.php,v 1.16 2005/10/12 21:00:24 slizardo Exp $
 * @package poop.dbobjects
 */

import('poop.sql.DataSource');
import('poop.sql.clauses.*');
import('poop.dbobjects.Persistence');

abstract class DbObject {
	
	private $table;
	private $primaryKey;

	private $fields;

	private $connection;	
	private $pdo;
	
	/**
	 * @param int $id
	 */
	public function __construct($id = null) {
		$this->fields = array();

		$className = get_class($this);
	
		$dataSource = DataSource::getDefaultDataSource();
		$this->connection = $dataSource->getConnection();
		$this->pdo = $this->connection->getPDO();		
	
		$object = Persistence::getObject($className);	
		
		$this->table = $object->getTable();
		$this->primaryKey = $object->getPrimaryKey();
		
		if(!is_null($id)) {
			$this->fields[$this->primaryKey] = $id;
			$this->select();
		}
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
	 * @return string
	 */
	public function getPrimaryKey() {
		return $this->primaryKey;
	}

	/**
	 * @param string $primaryKey
	 */
	public function setPrimaryKey($primaryKey) {
		$this->primaryKey = $primaryKey;
	}

	/**
	 * Esta funcion inserta los datos actuales en la bbdd.
	 * @author SLizardo
	 * @deprecated
	 * @see DbObject::save()
	 */
	public function insert() {
		$this->save();
	}

	public function save() {
		$clause = new InsertClause();
		$clause->setTable($this->table);
		foreach($this->fields as $column => $value) {
			$clause->addColumnValue($column, $value);
		}
		$query = $clause->__toString();
		$statement = $this->connection->createStatement();
		$statement->executeUpdate($query);
		$statement->close();
	}

	public function select() {
		$clause = new SelectClause();
		$clause->setTable($this->table);
		$clause->addWhere('%s = %d', $this->primaryKey, $this->fields[$this->primaryKey]);
		$query = $clause->__toString();
		$this->stmt = $this->pdo->query($query);	
		$this->fields = $this->stmt->fetch(PDO_FETCH_ASSOC);
	}
	
	public function update() {
		$clause = new UpdateClause();
		$clause->setTable($this->table);
		$clause->addWhere('%s = %d', $this->primaryKey, $this->fields[$this->primaryKey]);
		foreach($this->fields as $column => $value) {
			if($column != $this->primaryKey) {
				$clause->addColumnValue($column, $value);
			}
		}
		$query = $clause->__toString();

		$this->pdo->query($query);
	}
	
	public function delete() {
		$clause = new DeleteClause();
		$clause->setTable($this->table);
		$clause->addWhere('%s = %s', $this->primaryKey, $this->fields[$this->primaryKey]);		
		$query = $clause->__toString();
		
		$this->pdo->query($query);
	}

	/**
	 * @param array $array
	 */
	public function setArray($array) {
		$this->fields = $array;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return $this->fields;
	}
	
	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->fields[$this->primaryKey] = $id;
	}
	
	/**
	 * @return int
	 */
	public function getId() {
		return $this->fields[$this->primaryKey];
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 */
	private function setColumn($column, $value) {
		$this->fields[$column] = $value;
	}

	/**
	 * @param string $column
	 * @return mixed
	 */
	private function getColumn($column) {
		if(isset($this->fields[$column])) {
			return $this->fields[$column];
		} else {
			return null;
		}
	}
	
	/**
	 * @param string $column
	 * @return string
	 */
	public function getString($column) {
		return strval($this->getColumn($column));
	}

	/**
	 * @param string $column
	 * @param string $value
	 */
	public function setString($column, $value) {
		$this->setColumn($column, strval($value));
	}

	/**
	 * @param string $column
	 * @return int
	 */
	public function getInt($column) {
		return intval($this->getColumn($column));
	}

	/**
	 * @param string $column
	 * @param int $value
	 */
	public function setInt($column, $value) {
		$this->setColumn($column, intval($value));
	}

	/**
	 * @param string $column
	 * @return double
	 */
	public function getDouble($column) {
		return doubleval($this->getColumn($column));
	}

	/**
	 * @param string $column
	 * @param double $value
	 */
	public function setDouble($column, $value) {
		$this->setColumn($column, doubleval($value));
	}

	/**
	 * @param string $column
	 * @return string
	 */
	public function getDate($column) {
		return $this->getColumn($column);
	}

	/**
	 * @param string $column
	 * @param string $value
	 */
	public function setDate($column, $value) {
		$this->setColumn($column, $value);
	}

	/**
	 * @param string $column
	 * @return mixed
	 */
	public function getBinary($name) {
		return $this->getColumn($name);
	}

	/**
	 * @param string $column
	 * @param mixed $value
	 */
	public function setBinary($column, $value) {
		$this->setColumn($column, $value);
	}

	/**
	 * @param mixed $dom
	 * @param mixed &$parent
	 */
	public function addNodeTo($dom, &$parent) {
		$root = $dom->createElement($this->table);
		$root = $parent->appendChild($root);

		$root->setAttribute('id', $this->getId());

		$fields = $this->fields;
		unset($fields[$this->primaryKey]);

		foreach($fields as $key => $value) {
			$key = str_replace('_', '-', $key); // Se reemplaza el _ por - que es mas xml like.
			
			$child = $dom->createElement($key);
			$child = $root->appendChild($child);

			$text = $dom->createTextNode($value);
			$text = $child->appendChild($text);
		}
	}

	public function __destruct() {
		$this->connection->close();
	}
}

?>
