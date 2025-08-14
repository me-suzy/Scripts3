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
 * @version $Id: Image.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics
 */

import('poop.graphics.Dimension');
import('poop.graphics.Color');
import('poop.graphics.Drawable');
 
 /**
 * Clase basica de una imagen.
 *
 * @author SLizardo
 */
class Image {
	private $dimension;

	private $canvas;
	private $type;

	private $color;

	/**
	 * Array con todos los objetos que se dibujaran en esta imagen.
	 */
	private $drawables;

	private $antialias;

	/**
	 * @param Dimension $dimension
 	 */	 
	public function __construct(Dimension $dimension) {
		$this->dimension = $dimension;	
		$this->canvas = imagecreate($this->dimension->getWidth(), $this->dimension->getHeight());
		$this->drawables = array();
		$this->antialias = false;
	}

	/**
	 * @param string $path
	 */
	public static function createFromPNG($path) {
		return $path;
	}

	/**
	 * @param string $path
	 */
	public static function createFromJPEG($path) {
		return $path;
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
	 * @return resource
	 */
	public function &getCanvas() {
		return $this->canvas;
	}

	/**
	 * @param resource $canvas
	 */
	public function setCanvas($canvas) {
		$this->canvas = $canvas;
	}
	
	/**
	 * Devuelve el valor de la propiedad antialias de esta imagen.
	 * 
	 * @return boolean
	 */
	public function getAntialias() {
		return $this->antialias;
	}

	/**
	 * Define si los dibujos realizados a esta imagen 
	 * seran con efecto antialias o no.
	 *
	 * @param boolean $antialias
	 */
	public function setAntialias($antialias) {
		$this->antialias = $antialias;
	}
	
	/**
	 * @param Drawable $drawable
	 */
	public function addDrawable(Drawable $drawable) {
		array_push($this->drawables, $drawable);
	}

	/**
	 * Devuelve un objeto con las dimension de la imagen.
	 *
	 * @return Dimension
	 */
	public function getDimension() {
		return $this->dimension;
	}

	/**
	 * Asigna las dimensiones de esta imagen
	 *
	 * @param Dimension $dimension
	 */
	public function setDimension(Dimension $dimension) {
		$this->dimension = $dimension;
	}

	/**
	 * Genera la imagen y la envia como respuesta al navegador.	 
	 * (cambia el content type de la respuesta)
	 */
	public function out() {
		imageantialias($this->canvas, $this->antialias);
		
		if(is_null($this->color)) {
			$this->color = Color::WHITE();
		}

		$this->color->allocate($this->canvas);
		
		foreach($this->drawables as $drawable) {
			$drawable->draw($this);
		}
		
		$GLOBALS['response']->setContentType('image/png');

		imagepng($this->canvas);
	}

	public function __destruct() {
		imagedestroy($this->canvas);
	}	
}
	
?>
