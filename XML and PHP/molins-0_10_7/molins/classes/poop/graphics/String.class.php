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
 * @version $Id: String.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Drawable');
 
class String implements Drawable {

	private $position;
	private $text;
	private $color;

	/**
	 * @param Point $position
	 * @param string $text
	 * @param Color $color
	 */
	public function __construct(Point $position, $text, Color $color = null) {
		$this->position = $position;
		$this->text = $text;
		$this->color = (is_null($color) ? Color::BLACK() : $color);
	}
	
	/**
	 * @return Point
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @param Point $position
	 */
	public function setPosition(Point $position) {
		$this->position = $position;
	}
	
	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
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

		imagestring($canvas, 5, 
			$this->position->getX(), $this->position->getY(),
			$this->text,
			$this->color->allocate($canvas)
		);
	}	
}
	
?>
