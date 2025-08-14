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
 * @version $Id: Criteria.class.php,v 1.2 2005/10/11 15:57:30 slizardo Exp $
 * @package poop.dbobjects.util
 */

import('poop.dbobjects.util.SqlEnum');
 
class Criteria {

	private $conditions;
	
	public function __construct() {
		$this->conditions = array();
	}

	/**
	 * @param string $leftcol
	 * @param string $rightcol
	 * @param string $comp
  	 */	 
	public function add($leftCol, $rightCol, $comp) {
		$condition = sprintf("%s %s %s", $leftCol, $comp, $rightCol);

		array_push($this->conditions, $condition);
	}
}

?>
