<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * To contact the author write to {@link mailto:santiago.lizardo@gmail.com slizardo}
 * The latest version of Molins can be obtained from:
 * {@link http://www.phpize.com/}
 *
 * @author slizardo <santiago.lizardo@gmail.com>
 * @version $Id: TestAction.class.php,v 1.6 2005/10/19 15:56:28 slizardo Exp $
 * @package poop.tests
 */

import('poop.mvc.Action');
import('poop.qa.TestRunner');
import('poop.qa.TestSuite');
import('poop.lang.reflect.ClassUtil');
import('poop.qa.XMLTestResult');
import('poop.qa.ImageTestResult');

class TestAction extends Action {

	public function execute(ActionMapping $mapping, ActionForm $form, HttpRequest $request, HttpResponse $response) {

		$class = $request->getParameter('class');
		$test = ClassUtil::importInstance($class);

		$suite = new TestSuite();
		$suite->addTest($test);

		$mode = $request->getParameter('mode');

		$runner = new TestRunner();
		$runner->add($suite);

		switch($mode) {
			case 'xml':				
				$xmlResult = new XMLTestResult();
				$xmlResult->setResponse($response);
				$runner->setTestResult($xmlResult);
				break;
			case 'image':
				$imageResult = new ImageTestResult();
				$imageResult->setResponse($response);
				$runner->setTestResult($imageResult);
				break;
		}

		$runner->run();
	}
}

