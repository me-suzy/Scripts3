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
 * @version $Id: Rectangle.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Drawable');
 
/**
 * Clase que se ocupa de crear y controlar un rectangulo.
 */
class Rectangle implements Drawable {
	private $src;
	private $dst;
	private $color;
	private $border;

	/**
	 * @param Point $src
	 * @param Point $dst
	 * @param Color $color	 
 	 */	 
	public function __construct(Point $src, Point $dst, $color = null) {
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
	 * @return Border
	 */
	public function getBorder() {
		return $this->border;
	}

	/**
	 * @param Border $border
 	 */	 
	public function setBorder(Border $border) {
		$this->border = $border;
	}

	/**
	 * @param Image $image
 	 */	 
	public function draw(Image $image) {
		$canvas = $image->getCanvas();

		imagefilledrectangle($canvas, 
			$this->src->getX(), $this->src->getY(), 
			$this->dst->getX(), $this->dst->getY(),
			$this->color->allocate($canvas)
		);

		if(!is_null($this->border)) {
			$this->border->draw($canvas);

			imagerectangle($canvas, 
				$this->src->getX(), $this->src->getY(), 
				$this->dst->getX(), $this->dst->getY(),
				$this->border->getColor()
			);
		}
	}
}
	
?>
