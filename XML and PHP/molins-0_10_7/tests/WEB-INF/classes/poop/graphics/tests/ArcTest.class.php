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
 * @version $Id: ArcTest.class.php,v 1.1 2005/10/17 15:06:12 slizardo Exp $
 * @package poop.graphics.tests
 */

import('poop.qa.TestCase');
import('poop.graphics.Dimension');
import('poop.graphics.Image');
import('poop.graphics.Color');
import('poop.graphics.Point');
import('poop.graphics.Arc');

class ArcTest extends TestCase {

	private $image;
	
	public function setUp() {
		$dimension = new Dimension(200, 200);
		$this->image = new Image($dimension);
		$this->image->setColor(Color::WHITE());
		$this->image->setAntialias(true);
	}

	public function testArc() {
		$colors = array(Color::RED(), Color::GREEN(), Color::YELLOW(), Color::BLUE());
		
		$point = new Point(100, 100);
		$dimension = new Dimension(160, 160);
		
		for($i = 0; $i < 4; $i++) {
			$degreesBegin = (90*$i)+45;
			$degreesEnd = $degreesBegin+90;
			$color = $colors[$i];
			$arc = new Arc($point, $dimension, $degreesBegin, $degreesEnd, $color);
			$this->image->addDrawable($arc);
		}
		
		$this->image->out();
	}
}

?>
