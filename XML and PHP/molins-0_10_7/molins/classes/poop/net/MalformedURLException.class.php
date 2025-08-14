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
 * @version $Id: MalformedURLException.class.php,v 1.1 2005/10/16 20:01:04 slizardo Exp $
 * @package poop.net
 */

/**
 * Thrown to indicate that a malformed URL has occurred. Either no 
 * legal protocol could be found in a specification string or the 
 * string could not be parsed.  
 */
class MalformedURLException extends IOException {
}

?>
