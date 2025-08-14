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
 * @version $Id: FileAppender.class.php,v 1.6 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */
	
import('poop.logging.Appender');
import('poop.io.IOException');
	
class FileAppender extends Appender {

	/**
	 * Recurso del tipo descriptor de fichero.
	 */
	private $fd;
	
	/**
	 * Inicializa el appender cogiendo la propiedad file,<br />
	 * y abriendo el fichero.
	 *
	 * @throws IOException si el fichero no se puede abrir para escritura
	 */
	public function init() {
		$file = $this->getProperty('file');
		
		if(is_writable($file)) {
			$this->fd = @fopen($file, 'a');
		} else {
			throw new IOException($file);
		}
	}
	
	/**
	 * La funcion log de esta clase escribe el mensaje<br />
	 * de log a un fichero.
	 *
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function log($level, $message, $exception = null) {	
		if(is_resource($this->fd)) {
			$line = $this->getLayout()->format($level, $message, $exception);
		
			fwrite($this->fd, $line.NL);
		}
	}

	/**
	 * Al destruir el objeto appender,<br />
	 * liberamos recursos cerrando el descriptor.
	 */
	public function __destruct() {
		if(is_resource($this->fd)) {
			@fclose($this->fd);
		}
	}

}

?>
