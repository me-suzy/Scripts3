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
 * @version $Id: PointTest.class.php,v 1.1 2005/10/17 15:06:12 slizardo Exp $
 * @package poop.graphics.tests
 */

import('poop.qa.TestCase');
import('poop.graphics.Dimension');
import('poop.graphics.Image');
import('poop.graphics.Color');
import('poop.graphics.Line');
import('poop.graphics.Point');
import('poop.graphics.Ellipse');

class PointTest extends TestCase {

	private $image;
	
	public function setUp() {
		$dimension = new Dimension(200, 200);
		$this->image = new Image($dimension);
		$this->image->setColor(Color::WHITE());
	}

	public function testPoint() {
		$colors = array(Color::RED(), Color::BLUE(), Color::GREEN(), Color::BLACK());
		
		for($cx = 20; $cx < 100; $cx++) {
			for($nop = 0; $nop < $cx*2; $nop++) {
			
				$i = rand(0, count($colors)-1);
				
				$x = rand($cx, 200-$cx);
				$y = rand($cx, 200-$cx);
				$point = new Point($x, $y, $colors[$i]);

				$this->image->addDrawable($point);
			}
		}

		$this->image->out();
	}
}

?>
