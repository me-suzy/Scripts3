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
 * @version $Id: RingChart.class.php,v 1.2 2005/10/17 15:04:20 slizardo Exp $
 * @package poop.graphics.charts
 */

import('poop.graphics.Image');
import('poop.graphics.Dimension');
import('poop.graphics.Color');
import('poop.graphics.Point');
import('poop.graphics.Arc');
import('poop.graphics.charts.Chart');

class RingChart extends Chart {
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

	public function out() {
		$anchoTotalBarra = 5 + self::BAR_WIDTH + 5;
		$ancho = 5 + ($anchoTotalBarra * 7) + 5;
		$alto = 200;	
			
		$image = new Image(new Dimension($ancho, $alto));
		$image->setColor(Color::WHITE());
		$image->setAntialias(true);
		

		$total = 0;
		foreach($this->bars as $v) {
			$total += $v[1];
		}
		$color = null;
		$degreeBegin = 90;
		for($i = 0; $i < count($this->bars); $i++) {
			switch($i) {
				case 0: $color = Color::RED(); break;
				case 1: $color = Color::GREEN(); break;
				case 2: $color = Color::BLACK(); break;
				case 3: $color = Color::BLUE(); break;
				case 4: $color = Color::WHITE(); break;
				case 5: $color = Color::YELLOW(); break;
				case 6: $color = Color::BLACK(); break;
				case 7: $color = Color::BLACK(); break;			
			}
			$degreeEnd = $this->bars[$i][1] * 360 / $total;
			$rect = new Arc(
				new Point($ancho/2, $alto/2),
				new Dimension($ancho-5, $alto-5),
				$degreeBegin, $degreeBegin + $degreeEnd,
				$color
			);
			$image->addDrawable($rect);

			$degreeBegin += $degreeEnd;
		}

		$image->out();
	}
}

?>
