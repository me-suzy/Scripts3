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
 * @version $Id: Observer.class.php,v 1.1 2005/10/16 20:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.util.Observable');
 
/**
 * A class can implement the <code>Observer</code> interface when it
 * wants to be informed of changes in observable objects. 
 */
interface Observer {

	/**
	 * This method is called whenever the observed object is changed. An
	 * application calls an <tt>Observable</tt> object's
	 * <code>notifyObservers</code> method to have all the object's
	 * observers notified of the change. 
	 */
	public function update(Observable $observable, $arg);
}
	
?>
