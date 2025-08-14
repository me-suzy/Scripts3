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
 * @version $Id: Border.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Brush');

class Border {
	private $color;
	private $size;

	private $brush;

	/**
	 * @param Color $color
	 * @param int $size
	 */
	public function __construct(Color $color, $size = 1) {
		$this->color = $color;
		$this->size = $size;

		$this->brush = new Brush($size, $color);
	}
	
	/**
	 * @return Color | IMG_COLOR_BRUSHED
	 */
	public function getColor() {
		return (is_null($this->brush) ? $this->color : IMG_COLOR_BRUSHED);
	}

	/**
	 * @param Color $color
	 */
	public function setColor(Color $color) {
		$this->color = $color;
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param int $size
	 */
	public function setSize($size) {
		$this->size = $size;
	}

	/**
	 * @return Brush
	 */
	public function getBrush() {
		return $this->brush;
	}

	/**
	 * @param Brush $brush
	 */
	public function setBrush(Brush $brush) {
		$this->brush = $brush;
	}

	/**
	 * @param resource &$canvas
	 */
	public function draw(&$canvas) {
		if(!is_null($this->brush)) {
			$this->brush->setTo($canvas);
		} else {
			$this->color->allocate($canvas);
		}
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Border) {
			return (
				$object->getColor()->equals($this->color) &&
				$object->getSize()->equals($this->size) &&
				$object->getBrush()->equals($this->brush)
			);
		}

		return false;
	}
}
	
?>
