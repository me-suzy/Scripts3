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
 * @version $Id: HttpRequest.class.php,v 1.18 2005/10/18 14:58:13 slizardo Exp $
 * @package poop.http
 */

import('poop.http.Cookie');
import('poop.http.HttpSession');
import('poop.util.Locale');
 
final class HttpRequest {
	
	private $parameters;
	private $headers;

	private $session;
	
	public function __construct() {
		$this->parameters = $_REQUEST;
		
		$this->headers = apache_request_headers();
		$this->headers = array_change_key_case($this->headers);
	}
	
	/**
	 * Returns the portion of the request URI that indicates the context of the request.
	 *
	 * @return string
	 */
	public function getContextPath() {
		return CONTEXT_PATH;
	}

	/**
	 * Returns an array containing all of the Cookie  objects the client sent with this request.
	 *
	 * @return array
	 */
	public function getCookies() {
		$cookies = array();
		foreach($_COOKIE as $cookieName => $cookieValue) {
			array_push($cookies, new Cookie($cookieName, $cookieValue));
		}
		return $cookies;
	}

	/**
	 * Returns the value of the specified request header as a String.
	 *
	 * @return long
	 */
	public function getDateHeader() {
		return null;
	}

	/**
	 * Returns the value of a request parameter as a String, or null if the parameter does not exist.
	 *
	 * @param string $name
	 * @return string
	 */
	public function getParameter($name) {
		if(array_key_exists($name, $this->parameters)) {
			return $this->parameters[$name];
		} else {
			return null;
		}
	}

	/**
	 * Returns the value of the specified request header as a String.
	 *
	 * @param string $name
	 * @return String
	 */
	public function getHeader($name) {
		$name = strtolower($name);
		if(array_key_exists($name, $this->headers)) {
			$header = $this->headers[$name];

			return new String($header);
		} else {
			return null;
		}
	}

	/**
	 * Returns an enumeration of all the header names this request contains.
	 *
	 * @return array
	 */
	public function getHeadersName() {
		return array_keys($this->headers);
	}

	/**
	 * Returns the name of the HTTP method with which this request was made, for example, GET, POST, or PUT.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Returns the query string that is contained in the request URL after the path.
	 *
	 * @return string
	 */
	public function getQueryString() {
		return $_SERVER['QUERY_STRING'];
	}

	/**
	 * Returns the current session associated with this request, or if the request does not have a session, creates one.
	 *
	 * @param boolean $create
	 * @return HttpSession
	 */
	public function getSession($create = false) {
		if($create || $this->session == null) {
			$this->session = new HttpSession();
		}
		
		return $this->session;
	}

	/**
	 * Returns the value of the named attribute as an Object, or null if no attribute of the given name exists.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function getAttribute($name) {
		return $_REQUEST[$name];
	}

	/**
	 * Stores an attribute in this request.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setAttribute($name, $value) {
		$_REQUEST[$name] = $value;
	}

	/**
	 * Returns an Enumeration containing the names of the attributes available to this request.
	 *
	 * @return array
	 */
	public function getAttributeNames() {
		return array_keys($this->parameters);
	}

	/**
	 * Returns the length, in bytes, of the request body and made available by the input stream, or -1 if the length is not known.
	 *
	 * @return int
	 */
	public function getContentLength() {

	}

	/**
	 * Returns the preferred Locale that the client will accept content in, based on the Accept-Language header.
	 *
	 * @return Locale
	 */
	public function getLocale() {
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { 
			$acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE']; // $acceptLanguage = 'ca,es-es;Q=0.9';
			$acceptLanguage = substr($acceptLanguage, 0, strpos($acceptLanguage, ';')); // $acceptLanguage = 'ca,es-es';
			$languages = split(',', $acceptLanguage); // $languages = array('ca', 'es-es');
			$language = array_shift($languages); // $language = 'ca';

			$tokens = split('-', $language);

			if(count($tokens) == 1) {
				return new Locale($tokens[0]);
			} else {
				return new Locale($tokens[0], $tokens[1]);				
			}
		} else {
			return null;
		}
	}
}

?>
