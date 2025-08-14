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
 * @version $Id: Dimension.class.php,v 1.1 2005/10/17 09:52:03 slizardo Exp $
 * @package poop.graphics
 */

class Dimension {
	private $width;
	private $height;
	
	/**
	 * @param int $width
	 * @param int $height
	 */
	public function __construct($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @param int $width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param int $height
	 */
	public function setHeight($height) {
		$this->height = $height;
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Dimension) {
			return (
				$object->getWidth() == $this->width && 
				$object->getHeight() == $this->height
			);
		}

		return false;
	}
}
	
?>
