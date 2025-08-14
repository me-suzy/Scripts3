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
 * @version $Id: Arc.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */
 
import('poop.graphics.Drawable');
 
class Arc implements Drawable {
	
	/*
	IMG_ARC_PIE
	IMG_ARC_CHORD
	IMG_ARC_NOFILL
	IMG_ARC_EDGED
	*/	
	
	private $center;
	private $dimension;
	private $degreeBegin;
	private $degreeEnd;
	
	/**
	 * @param Point $center
	 * @param Dimension $dimension
	 * @param int $degreeBegin
	 * @param int $degreeEnd
	 * @param Color $color
	 */
	public function __construct(Point $center, Dimension $dimension, $degreeBegin, $degreeEnd, Color $color = null) {
		$this->center = $center;
		$this->dimension = $dimension;
		$this->degreeBegin = $degreeBegin;
		$this->degreeEnd = $degreeEnd;
		$this->color = $color;
	}
	
	/**
	 * @return Point
	 */
	public function getCenter() {
		return $this->center;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad center.
	 *
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
	 * Asigna el valor pasado como parametro a la propiedad dimension.
	 *
	 * @param Dimension $dimension
	 */
	public function setDimension(Dimension $dimension) {
		$this->dimension = $dimension;
	}

	/**
	 * @return int
	 */
	public function getDegreeBegin() {
		return $this->degreeBegin;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad degreeBegin.
	 *
	 * @param int $degreeBegin
	 */
	public function setDegreeBegin($degreeBegin) {
		$this->degreeBegin = $degreeBegin;
	}

	/**
	 * @return int
	 */
	public function getDegreeEnd() {
		return $this->degreeEnd;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad degreeEnd.
	 *
	 * @param int $degreeEnd
	 */
	public function setDegreeEnd($degreeEnd) {
		$this->degreeEnd = $degreeEnd;
	}

	/**
	 * @return Color
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad color.
	 *
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
		
		imagefilledarc(
			$canvas,
			$this->center->getX(), $this->center->getY(),
			$this->dimension->getWidth(), $this->dimension->getHeight(),
			$this->degreeBegin, $this->degreeEnd,
			$this->color->allocate($canvas),
			IMG_ARC_PIE | IMG_ARC_EDGED
		);
	}

	/**
	 * @param mixed $object
	 * @return boolean
	 */
	public function equals($object) {
		if($object instanceof Arc) {
			return (
				$object->getCenter()->equals($this->center) &&
				$object->getDimension()->equals($this->dimension) &&
				$object->degreeBegin == $this->degreeBegin &&
				$object->degreeEnd == $this->degreeEnd &&
				(
					(is_null($object->color) && is_null($this->color)) ||
					$object->color->equals($this->color)
			       	)
			);
		}

		return false;
	}
}
	
?>
