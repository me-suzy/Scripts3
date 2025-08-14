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
 * @version $Id: Brush.class.php,v 1.1 2005/10/17 09:52:03 slizardo Exp $
 * @package poop.graphics
 */

class Brush {
	private $canvas;

	private $color;
	private $size;
	
	/**
	 * @param int $size
	 * @param Color $color
	 */
	public function __construct($size, $color = null) {
		$this->canvas = imagecreate($size, $size);
		if(!is_null($color)) {
			$color->allocate($this->canvas);
		}
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
	 * @param resource &$canvas
	 */
	public function setTo(&$canvas) {
		imagesetbrush($canvas, $this->canvas);
	}
}
	
?>
