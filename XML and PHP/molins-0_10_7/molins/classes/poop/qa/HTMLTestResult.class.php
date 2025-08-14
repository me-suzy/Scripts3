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
 * @version $Id: HTMLTestResult.class.php,v 1.6 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.qa
 */

import('poop.qa.TestResult');
 
class HTMLTestResult extends TestResult {

	public function printHeader() {
		print '<html>'.NL;
		print '<head><title>'._('Control de calidad').'</title></head>'.NL;	
		print '<body>'.NL;
		print '<table style="border: 2px dashed black; width: 300px;">'.NL;
		print '<tr>'.NL;
		print '	<th>'._('Metodo').'</th>'.NL;
		print '	<th>'._('Opciones').'</th>'.NL;
		print '</tr>'.NL;
	}

	/**
	 * @param string $method
	 * @param int $status
	 * @param string $description
	 */
	public function printResult($method, $status, $description = null) {
		print '<tr>'.NL;
		printf('<td bgcolor="white">%s</td>%s', $method, NL);
		switch($status) {
			case self::SUCESS:
				printf('<td bgcolor="green" align="center">%s</td>%s', _('Satisfactorio'), NL);
				break;

			case self::FAILURE:
				printf('<td bgcolor="red" align="center">%s</td>%s', _('Fallido'), NL);
				break;
		}
		print '</tr>'.NL;
	}
	
	public function printFooter() {
		print '</table>'.NL;
		print '</body>'.NL;
		print '</html>'.NL;
	}
}

?>
