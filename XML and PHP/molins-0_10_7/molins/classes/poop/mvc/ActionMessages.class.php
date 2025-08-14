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
 * @version $Id: ActionMessages.class.php,v 1.8 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mvc
 */

import('poop.util.HashMap');
import('poop.mvc.ActionMessage');
import('poop.mvc.ActionMessageItem');
 
class ActionMessages {

	const GLOBAL_MESSAGE = 0;

	private $messages;
	
	public function __construct() {
		$this->messages = new HashMap();
	}
	
	public function clear() {
		$this->messages->clear();
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->messages->isEmpty();
	}

	/**
	 * @param string $key
	 * @return java.util.Iterator
	 */
	public function get($key) {
		return $this->hash->get($key);
	}

	/**
	 * @return int
	 */
	public function size() {
		$total = 0;

		for($i = $this->messages->values->iterator(); $i->hasNext();) {
			$item = $i->next();
			$total += $item->getList()->size();
		}
		
		return $total;
	}

	/**
	 * @param string $key
	 * @param ActionMessage $value
	 */
	public function add($key, ActionMessage $value) {
		$this->messages->put($key, $value);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->messages->__toString();
	}

	/**
	 * @return HashMap
	 */
	public function getMessages() {
		return $this->messages;
	}	
}
	
?>
