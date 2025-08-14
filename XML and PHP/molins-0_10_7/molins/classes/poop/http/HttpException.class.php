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
 * @version $Id: HttpException.class.php,v 1.6 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.http
 */

class HttpException extends Exception {

	/**
	 * @param int $errorCode
 	 */	 
	public function __construct($code) {
		$message = null;

		switch($code) {
			case 400:
				$message = _('Peticion incorrecta');
			case 401:
				$message = _('Autorizacion requerida');
			case 403:
				$message = _('Tu no tienes permiso para acceder a este recurso del servidor');
			case 404:
				$message = _('La URL solicitada no fue encontrada en el servidor');
			case 500:
				$message = _('Error interno del servidor');
			default:
				$message = _('Error HTTP. Sin explicacion');
		}
		
		parent::__construct($message, $code);
	}
}
	
?>
