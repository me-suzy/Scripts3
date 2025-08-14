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
 * @version $Id: ResultSet.class.php,v 1.8 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

class ResultSet {

	private $statement;
	private $current;
	
	/**
	 * @param PDOStatement $statement
	 */
	public function __construct($statement) {
		$this->statement = $statement;
	}
	
	/**
	 * @return boolean
	 */
	public function next() {
		$this->current = $this->statement->fetch();

		return (is_array($this->current));
	}

	/**
	 * @param mixed $ik
	 * @return mixed
	 */
	public function getString($ik) {
		switch(gettype($ik)) {
			case 'integer':
				$ik--;
				if(isset($this->current[$ik])) {
					return $this->current[$ik];
				}
				break;

			case 'string':
				if(array_key_exists($ik, $this->current)) {
					return $this->current[$ik];
				}
				break;
		}
	}

	/**
	 * @param mixed $ik
	 * @return mixed
	 */
	public function getFloat($ik) {
		switch(gettype($ik)) {
			case 'integer':
				$ik--;
				if(isset($this->current[$ik])) {
					return $this->current[$ik];
				}
				break;

			case 'string':
				if(array_key_exists($ik, $this->current)) {
					return $this->current[$ik];
				}
				break;
		}
	}

	/**
	 * @param mixed $ik
	 * @return mixed
	 */
	public function getInt($ik) {
		switch(gettype($ik)) {
			case 'integer':
				$ik--;
				if(isset($this->current[$ik])) {
					return $this->current[$ik];
				}
				break;

			case 'string':
				if(array_key_exists($ik, $this->current)) {
					return $this->current[$ik];
				}
				break;
		}
	}	

	public function close() {
		$this->statement = null;
	}
}
	
?>
