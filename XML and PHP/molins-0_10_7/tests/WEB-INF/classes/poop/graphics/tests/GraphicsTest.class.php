<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@see <a href="mailto:santiago.lizardo@gmail.com">slizardo</a>}
 * The latest version of Molins can be obtained from:
 * {@see <a href="http://www.phpize.com/">http://www.phpize.com/</a>}
 *
 * @author <a href="santiago.lizardo@gmail.com">slizardo</a>
 * @version $Id: GraphicsTest.class.php,v 1.1 2005/10/17 15:06:12 slizardo Exp $
 * @package poop.graphics.tests
 */

import('poop.graphics.Point');
import('poop.graphics.Dimension');
import('poop.graphics.Color');
 
class GraphicsTest extends TestCase {

	private $point1;
	private $point2;

	private $dimension1;
	private $dimension2;

	private $colorBlue;
	private $colorRed;
	private $color;
	
	public function setUp() {
		$this->point1 = new Point(4, 9);
		$this->point2 = new Point(9, 4);
		
		$this->dimension1 = new Dimension(320, 200);
		$this->dimension2 = new Dimension(150, 340);

		$this->colorBlue = Color::BLUE();
		$this->colorRed = new Color(0xff, 0x00, 0x00);
		$this->color = new Color(0, 0, 255);
	}

	public function testPoints() {
		self::assert($this->point1->getX() == 4);
		self::assert($this->point1->getY() == 9);
		
		self::assert($this->point1->equals($this->point2) == false);
	}

	public function testDimensions() {
		self::assert($this->dimension2->getWidth() == 150);
		self::assert($this->dimension2->getHeight() == 340);
		
		$this->dimension2->setWidth(320);
		$this->dimension2->setHeight(200);

		self::assert($this->dimension1->equals($this->dimension2));
	}

	public function testColors() {
		self::assert($this->colorRed->getRed() == 255);
		self::assert($this->colorRed->getGreen() == 0);
		self::assert($this->colorRed->getBlue() == 0);

		self::assert($this->color->equals($this->colorBlue));
	}
}
	
?>
