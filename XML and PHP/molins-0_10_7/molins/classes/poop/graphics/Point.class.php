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
 * @version $Id: Point.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */
 
import('poop.graphics.Drawable');
import('poop.graphics.Image');
 
class Point implements Drawable {
	
	private $x;
	private $y;
	private $color;

	/**
	 * @param int $x
	 * @param int $y
	 * @param Color $color
	 */
	public function __construct($x = 0, $y = 0, $color = null) {
		$this->x = $x;
		$this->y = $y;
		$this->color = $color;
	}

	/**
	 * @return int
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * @param int $x
	 */
	public function setX($x) {
		$this->x = $x;
	}

	/**
	 * @return int
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * @param int $y
	 */
	public function setY($y) {
		$this->y = $y;
	}

	/**
	 * @return Color
	 */
	public function getColor() {
		return $this->color;
	}
	
	/**
	 * @param Color $color
	 */
	public function setColor(Color $color) {
		$this->color = $color;
	}

	/**
	 * @param Image $image
	 */
	public function draw(Image $image) {
		$canvas = $image->getCanvas();

		imagesetpixel($canvas, $this->x, $this->y, $this->color->allocate($canvas));
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Point) {
			return ($object->getX() == $this->x && $object->getY() == $this->y);
		}

		return false;
	}
}
	
?>
