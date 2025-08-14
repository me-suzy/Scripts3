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
 * @version $Id: Line.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Drawable');
import('poop.graphics.Point');
 
/**
 * Linea representada por un punto de origen y otro de destino.
 */
class Line implements Drawable {
	private $src;
	private $dst;

	private $color;

	/**
	 * @param Point $src
 	 * @param Point $dst
 	 * @param Color $color
 	 */	 
	public function __construct(Point $src, Point $dst, Color $color = null) {
		$this->src = $src;
		$this->dst = $dst;

		$this->color = $color;
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
	 * @return Point
	 */
	public function getSrc() {
		return $this->src;
	}

	/**
	 * @param Point $src
 	 */	 
	public function setSrc(Point $src) {
		$this->src = $src;
	}

	/**
	 * @return Point
	 */
	public function getDst() {
		return $this->dst;
	}

	/**
 	 * @param Point $dst
 	 */	 
	public function setDst(Point $dst) {
		$this->dst = $dst;
	}

	/**
 	 * @param Image $image
 	 */	 
	public function draw(Image $image) {
		$canvas = $image->getCanvas();

		imageline($canvas, 
			$this->src->getX(), $this->src->getY(), 
			$this->dst->getX(), $this->dst->getY(),
			$this->color->allocate($canvas)
		);
	}

	/**
	 * @param mixed $object
	 * @return boolean
 	 */	 
	public function equals($object) {
		if($object instanceof Line) {
			return (
				$object->getSrc()->equals($this->src) &&
				$object->getDst()->equals($this->dst) &&
				$object->getColor()->equals($this->color)
			);			
		}

		return false;
	}
}
	
?>
