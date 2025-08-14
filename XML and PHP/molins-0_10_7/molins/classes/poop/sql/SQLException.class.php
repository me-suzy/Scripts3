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
 * @version $Id: SQLException.class.php,v 1.5 2005/10/16 20:01:04 slizardo Exp $
 * @package poop.sql
 */

class SQLException extends Exception {

	private $query;
	
	public function __construct($message, $query = null) {
		parent::__construct($message);

		$this->query = $query;
	}

	public function __toString() {
		return $this->query;
	}
}
	
?>
