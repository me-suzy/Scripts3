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
 * @version $Id: HttpResponse.class.php,v 1.9 2005/10/16 20:00:56 slizardo Exp $
 * @package poop.http
 */

import('poop.http.Cookie');
 
final class HttpResponse {

	private $headers;
	private $contentType;
	private $status;

	public function __construct() {
		$this->headers = array();
	}
	
	/**
	 * Returns the content type used for the MIME body sent in this response.
	 *
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}
	
	/**
	 * Sets the content type of the response being sent to the client, if the response has not been committed yet.
	 *
	 * @param string $contentType
	 */
	public function setContentType($contentType) {
		$this->contentType = $contentType;
		header('Content-type: '.$contentType);
	}
	
	/**
	 * Sends a temporary redirect response to the client using the specified redirect location URL.
	 *
	 * @param string $URL
	 */
	public static function sendRedirect($URL) {
		header('Location: '.$URL);
		exit;
	}

	/**
	 * Redireccion a la pagina de donde fue llamado, o al indice del directorio si no hubiese.
	 */
	public static function sendBack() {
		if(isset($_SERVER['HTTP_REFERER'])) {
			self::sendRedirect($_SERVER['HTTP_REFERER']);
		} else {
			self::sendRedirect('/');
		}
	}

	/**
	 * @param Cookie $cookie
	 */
	public function addCookie(Cookie $cookie) {
		setcookie($cookie->getName(), $cookie->getValue());
	}

	/**
	 * Adds a response header with the given name and value.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function addHeader($name, $value) {
		$this->headers[$name] = $value;
	}

	/**
	 * Returns a boolean indicating whether the named response header has already been set.
	 *
	 * @param string $name
	 * @return boolean
	 */
	public function containsHeader($name) {
		return array_key_exists($name, $this->headers);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setHeader($name, $value) {
		$this->headers[$name] = $value;
	}

	/**
	 * Sets the status code for this response.
	 *
	 * @param int $code
	 */
	public function setStatus($code) {
		$this->status = $code;
	}

	/**
	 * Sends an error response to the client using the specified status code and clearing the buffer.
	 *
	 * @param int $code
	 */
	public function sendError($code) {
	}
}

?>
