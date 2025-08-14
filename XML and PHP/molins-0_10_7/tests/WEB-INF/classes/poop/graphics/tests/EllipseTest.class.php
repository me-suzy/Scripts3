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
 * @version $Id: EllipseTest.class.php,v 1.1 2005/10/17 15:06:12 slizardo Exp $
 * @package poop.graphics.tests
 */

import('poop.qa.TestCase');
import('poop.graphics.Dimension');
import('poop.graphics.Image');
import('poop.graphics.Color');
import('poop.graphics.Point');
import('poop.graphics.Ellipse');

class EllipseTest extends TestCase {

	private $image;
	
	public function setUp() {
		$dimension = new Dimension(200, 100);
		$this->image = new Image($dimension);
		$this->image->setColor(Color::WHITE());
		$this->image->setAntialias(true);
	}

	public function testEllipse() {
		$colors = array(Color::RED(), Color::GREEN(), Color::YELLOW(), Color::BLUE());
		
		$point = new Point(100, 50);
		
		for($i = 0; $i < 4; $i++) {
			$width = 180-(20*$i);
			$height = 90-(20*$i);
			$dimension = new Dimension($width, $height);
			$color = $colors[$i];
			$ellipse = new Ellipse($point, $dimension, $color);
			$this->image->addDrawable($ellipse);
		}
		
		$this->image->out();
	}
}

?>
