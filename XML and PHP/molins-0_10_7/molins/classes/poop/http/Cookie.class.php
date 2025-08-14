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
 * @version $Id: Cookie.class.php,v 1.5 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.http
 */
 
class Cookie {

	private $comment;
	private $domain;
	private $maxAge;
	private $name;
	private $secure;
	private $value;

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name = null, $value = null) {
		$this->name = $name;
		$this->value = $value;
	}
	
	/**
	 * Devuelve el valor de la propiedad comment.
	 *
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad comment.
	 *
	 * @param string $comment
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}

	/**
	 * Devuelve el valor de la propiedad domain.
	 *
	 * @return mixed
	 */
	public function getDomain() {
		return $this->domain;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad domain.
	 *
	 * @param string $domain
	 */
	public function setDomain($domain) {
		$this->domain = $domain;
	}

	/**
	 * Devuelve el valor de la propiedad maxAge.
	 *
	 * @return mixed
	 */
	public function getMaxAge() {
		return $this->maxAge;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad maxAge.
	 *
	 * @param int $maxAge
	 */
	public function setMaxAge($maxAge) {
		$this->maxAge = $maxAge;
	}

	/**
	 * Devuelve el valor de la propiedad name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad name.
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Devuelve el valor de la propiedad secure.
	 *
	 * @return string
	 */
	public function getSecure() {
		return $this->secure;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad secure.
	 *
	 * @param string $secure
	 */
	public function setSecure($secure) {
		$this->secure = $secure;
	}

	/**
	 * Devuelve el valor de la propiedad value.
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad value.
	 *
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}		

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf('Cookie { name = "%s", value = "%s" }', $this->name, $this->value);
	}
}
	
?>
