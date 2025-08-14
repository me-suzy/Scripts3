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
 * @version $Id: RecipientType.class.php,v 1.4 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mail
 */

class RecipientType {

	/**
	 * @return RecipientType
	 */
	public static function TO() { return new self('to'); }

	/**
	 * @return RecipientType
	 */
	public static function CC() { return new self('cc'); }

	/**
	 * @return RecipientType
	 */
	public static function BCC() { return new self('bcc'); }

	protected $type;
	
	/**
	 * @param string $type
	 */
	private function __construct($type) {
		$this->type = $type;
	}
}
	
?>
