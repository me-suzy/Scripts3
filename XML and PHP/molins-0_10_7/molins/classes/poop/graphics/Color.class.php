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
 * @version $Id: Color.class.php,v 1.1 2005/10/17 09:52:03 slizardo Exp $
 * @package poop.graphics
 */
 
/**
 * Representa un color, y contiene metodos utiles.
 */
class Color {
	
	/**
	 * @return Color
	 */
	public static function RED() { return new self(0xff, 0, 0); }

	/**
	 * @return Color
	 */
	public static function GREEN() { return new self(0, 0xff, 0); }

	/**
	 * @return Color
	 */
	public static function BLUE() { return new self(0, 0, 0xff); }

	/**
	 * @return Color
	 */
	public static function BLACK() { return new self(0, 0, 0); }

	/**
	 * @return Color
	 */
	public static function WHITE() { return new self(0xff, 0xff, 0xff); }

	/**
	 * @return Color
	 */
	public static function YELLOW() { return new self(0xff, 0xff, 0); }

	private $red;
	private $green;
	private $blue;

	/**
	 * @param int $red
	 * @param int $green
	 * @param int $blue
	 */
	public function __construct($red, $green, $blue) {
		$this->red = $red;
		$this->green = $green;
		$this->blue = $blue;
	}

	/**
	 * @return int
	 */
	public function getRed() {
		return $this->red;
	}

	/**
	 * @param int $red
	 */
	public function setRed($red) {
		$this->red = $red;
	}

	/**
	 * @return int
	 */
	public function getGreen() {
		return $this->green;
	}

	/**
	 * @param int $green
	 */
	public function setGreen($green) {
		$this->green = $green;
	}

	/**
	 * @return int
	 */
	public function getBlue() {
		return $this->blue;
	}

	/**
	 * @param int $blue
	 */
	public function setBlue($blue) {
		$this->blue = $blue;
	}

	/**
	 * @param resource &$canvas
	 */
	public function allocate(&$canvas) {
		return imagecolorallocate($canvas, $this->red, $this->green, $this->blue);
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Color) {
			return (
				$object->getRed() == $this->red && 
				$object->getGreen() == $this->green &&
				$object->getBlue() == $this->blue);
		}
		
		return false;
	}
}

?>
