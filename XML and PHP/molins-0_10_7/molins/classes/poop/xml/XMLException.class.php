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
 * @version $Id: XMLException.class.php,v 1.4 2005/10/13 13:42:26 slizardo Exp $
 * @package poop.xml
 */
	
class XMLException extends Exception {

	private $fileName;
	
	public function __construct($message, $fileName = null) {
		parent::__construct($message);
		
		$this->fileName = $fileName;
	}

	public function __toString() {
		if($this->fileName == null) {
			return $this->message;
		} else {
			return $this->fileName;
		}
	}
}

?>
