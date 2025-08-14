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
 * @version $Id: DbGroup.class.php,v 1.14 2005/10/12 21:00:24 slizardo Exp $
 * @package poop.dbobjects
 */

import('poop.sql.clauses.JoinClause');
import('poop.dbobjects.DbObject');
import('poop.dbobjects.Persistence');

abstract class DbGroup {
	const DESC = 'DESC';
	const ASC = 'ASC';
	
	private $pdo;
	
	private $dbObject;	
	
	private $stmt;
	private $resource;

	private $row;
	private $numRows;
	
	private $executed;
	private $clause;
	
	private $fields;

	private $table;
	private $primaryKey;
	
	public function __construct() {
		$classname = get_class($this);
	
		$group = Persistence::getGroup($classname);
		
		$this->DB_OBJECT = $group->getObject();

		$class = ClassUtil::getClassFor($this->DB_OBJECT);
	
		import($this->DB_OBJECT);
		
		$this->dbObject = new $class();

		$this->table = $this->dbObject->getTable();
		$this->primaryKey = $this->dbObject->getPrimaryKey();		

		$this->executed = false;
		$this->clause = new SelectClause();
		$this->clause->setTable($this->table);

		foreach(Persistence::getJoinsFor($classname) as $xJoin) {
			$object = $xJoin['object'];
			
			$join = new JoinClause();
			$join->setTable(Persistence::getTableFor($object));
			$join->setLeft($xJoin['column']);
			$join->setRight(Persistence::getPrimaryKeyFor($object));

			foreach($xJoin['columns'] as $name => $alias) {
				$join->addColumn($name, $alias);
			}

			$this->addJoin($join);
		}

		$dataSource = DataSource::getDefaultDataSource();
		$connection = $dataSource->getConnection();
		$this->pdo = $connection->getPDO();		
	}

	public function __destruct() {
		unset($this->stmt);
	}
	
	public function execute() {
		$query = $this->clause->__toString();
		$this->stmt = $this->pdo->query($query);
		if($this->stmt) {
			$this->numRows = $this->stmt->rowCount();
		} else {
			$errorInfo = $this->pdo->errorInfo();
			throw new SQLException($errorInfo[2]);
		}
		$this->row = 0;
		$this->executed = true;
	}
	
	/**
	 * @return boolean
	 */
	public function hasNext() {
		if($this->executed == false)
			$this->execute();
		$this->fields = $this->stmt->fetch(PDO_FETCH_ASSOC);

		return $this->fields;
	}
	
	/**
	 * @return DbObject
	 */
	public function &next() {
		$this->dbObject->setArray($this->fields);
		$this->row++;

		return $this->dbObject;
	}

	public function setColumns() {
		foreach(func_get_args() as $column) {
			$this->clause->addColumn($column);
		}
	}

	/**
	 * @param mixed $join
 	 */	 
	public function addJoin($join) {
		$this->clause->addJoin($join);
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
		}

		$this->clause->addWhere($sql);
	}
	
	/**
	 * @param string $column
	 * @param string $orientation
	 */
	public function setOrderBy($column, $orientation = 'DESC') {
		$this->clause->setOrderBy($column, $orientation);
	}

	/**
	 * @param int $max
	 * @param int $offset
	 */
	public function setLimit($max, $offset = 0) {
		$this->clause->setLimit($max, $offset);
	}

	/**
	 * @return array
	 */
	public function toArray() {
		$array = array();

		while($this->hasNext()) {
			$object = $this->next();

			array_push($array, $object->toArray());
		}
		
		return $array;
	}
	
	/**
	 * @return string
	 */
	public function toXML() {
		$dom = new DomDocument();

		$rootName = $this->table.'-group';
		
		$root = $dom->createElement($rootName);
		$root = $dom->appendChild($root);

		while($this->hasNext()) {
			$object = $this->next();

			$object->addNodeTo($dom, $root);
		}

		print $dom->saveXML();
	}
}

?>
