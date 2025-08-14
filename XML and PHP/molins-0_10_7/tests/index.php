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
 * @version $Id: index.php,v 1.8 2005/10/13 13:43:22 slizardo Exp $
 * @package default
 */
	
try {
	/**
	 * Includes the basic configuration parameters for this application.
	 */
	require_once 'molins.conf.php';

	/**
	 * We add to the include path of PHP the necessary directories.
	 */
	$include_path = array(
		get_include_path(), 
		CONF_DIR_MOLINS,
		CONF_DIR_MOLINS.DIRECTORY_SEPARATOR.'classes',
		CONF_DIR_MOLINS.DIRECTORY_SEPARATOR.'smarty'
	);
	set_include_path(implode(PATH_SEPARATOR, $include_path));

	/**
	 * Includes the main class of Molins, and it initializes it.
	 */
	require_once 'Molins.php';

} catch (Exception $e) {

	/**
	 * If an error was fired, the function Molins_errorHandler<br />
	 * will display the typical blue screen, and information about the error.
	 */
	Molins_errorHandler($e);
}

?>
