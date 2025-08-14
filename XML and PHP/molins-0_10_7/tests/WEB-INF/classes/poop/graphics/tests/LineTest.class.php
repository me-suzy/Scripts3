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
 * @version $Id: LineTest.class.php,v 1.1 2005/10/17 15:06:12 slizardo Exp $
 * @package poop.graphics.tests
 */

import('poop.qa.TestCase');
import('poop.graphics.Dimension');
import('poop.graphics.Image');
import('poop.graphics.Color');
import('poop.graphics.Point');
import('poop.graphics.Line');

class LineTest extends TestCase {

	private $image;
	
	public function setUp() {
		$dimension = new Dimension(200, 200);
		$this->image = new Image($dimension);
		$this->image->setColor(Color::WHITE());
		$this->image->setAntialias(true);
	}

	public function testLine() {
		for($x = 10; $x <= 190; $x += 10) {
			for($y = 10; $y <= 190; $y += 20) {
				$color1 = Color::GREEN();
				$src1 = new Point($x, 10);
				$dst1 = new Point($x, 190);
				$line1 = new Line($src1, $dst1, $color1);

				$this->image->addDrawable($line1);
			}
		}
		for($x = 10; $x <= 190; $x += 10) {
			for($y = 10; $y <= 190; $y += 20) {
				$color2 = Color::BLUE();
				$src2 = new Point(10, $y);
				$dst2 = new Point(190, $y);
				$line2 = new Line($src2, $dst2, $color2);
				
				$this->image->addDrawable($line2);
			}
		}
		
		$this->image->out();
	}
}

?>
