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
 * @version $Id: Ellipse.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Drawable');
 
/**
 * Clase que se ocupa de crear y modificar un ovalo.
 */
class Ellipse implements Drawable {

	/**
	 * Punto en donde se centrara el ovalo.
	 */
	private $center;

	/**
	 * Dimension (ancho y alto) del ovalo.
	 */
	private $dimension;

	/**
	 * Color (de fondo) del ovalo.
	 */
	private $color;

	/**
	 * Borde de la figura, opcional.
	 */
	private $border;
	
	/**
	 * @param Point $center
	 * @param Dimension $dimension
	 * @param Color $color
	 */
	public function __construct(Point $center, Dimension $dimension, Color $color = null) {
		$this->center = $center;
		$this->dimension = $dimension;
		$this->color = $color;
	}

	/**
	 * @return Point
	 */
	public function getCenter() {
		return $this->center;
	}

	/**
	 * @param Point $center
	 */
	public function setCenter(Point $center) {
		$this->center = $center;
	}

	/**
	 * @return Dimension
	 */
	public function getDimension() {
		return $this->dimension;
	}

	/**
	 * @param Dimension $dimension
	 */
	public function setDimension(Dimension $dimension) {
		$this->dimension = $dimension;
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

		// Pinto el ovalo relleno.
		imagefilledellipse($canvas, 
			$this->center->getX(), $this->center->getY(), 
			$this->dimension->getWidth(), $this->dimension->getHeight(),
			$this->color->allocate($canvas)
		);

		if(!is_null($this->border)) {
			$this->border->draw($canvas);
	
			imageellipse($canvas, 
				$this->center->getX(), $this->center->getY(), 
				$this->dimension->getWidth(), $this->dimension->getHeight(),
				$this->border->getColor()
			);
		}
	}
}
	
?>
