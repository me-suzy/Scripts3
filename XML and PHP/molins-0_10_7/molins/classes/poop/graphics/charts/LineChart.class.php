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
 * @version $Id: LineChart.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics.charts
 */

import('poop.graphics.Drawable');
import('poop.graphics.charts.Chart');
import('poop.graphics.Rectangle');
import('poop.graphics.Point');
import('poop.graphics.Color');
import('poop.graphics.Border');

class LineChart extends Chart implements Drawable {
	const MARGIN_LEFT_WIDTH = 5;
	const MARGIN_RIGHT_WIDTH = 5;	
	
	const BAR_WIDTH = 20;
	
	private $title;
	private $bars;
	private $gridVisible;

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad title.
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param string $label
	 * @param int $count
	 */
	public function addBar($label, $count) {
		$this->bars[$label] = $count;
	}

	/**
	 * @return boolean
	 */
	public function getGridVisible() {
		return $this->gridVisible;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad gridVisible.
	 *
	 * @param boolean $gridVisible
	 */
	public function setGridVisible($gridVisible) {
		$this->gridVisible = $gridVisible;
	}

	/**
	 * @return boolean
	 */
	public function getLabelsVisible() {
		return $this->labelsVisible;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad labelsVisible.
	 *
	 * @param boolean $labelsVisible
	 */
	public function setLabelsVisible($labelsVisible) {
		$this->labelsVisible = $labelsVisible;
	}

	/**
	 * @param Image $image
	 */
	public function draw(Image $image) {
		$anchoTotalBarra = 5 + self::BAR_WIDTH + 5;
		$alto = 200;
		
		for($i = 0; $i < count($this->bars); $i++) {
			$x = self::MARGIN_LEFT_WIDTH + ($i * $anchoTotalBarra) + self::MARGIN_RIGHT_WIDTH;
			$rect = new Rectangle(
				new Point($x, $alto-1),
				new Point($x + self::BAR_WIDTH, 200 - (($this->bars[$i][1] * 200) / 300)),
				Color::WHITE()
			);
			$rect->setBorder(new Border(Color::RED(), 1));
		
			$image->addDrawable($rect);
		}
	}
}

?>
