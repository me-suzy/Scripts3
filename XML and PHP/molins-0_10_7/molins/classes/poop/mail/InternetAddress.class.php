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
 * @version $Id: InternetAddress.class.php,v 1.8 2005/10/12 21:00:42 slizardo Exp $
 * @package poop.mail
 */

import('poop.lang.Cloneable');
import('poop.mail.AddressException');
 
class InternetAddress implements Cloneable {

	private $address;
	private $personal;
	
	/**
	 * @param string $address
	 * @param string $personal
	 */
	public function __construct($address, $personal = null) {
		$this->address = $address;
		$this->personal = $personal;
	}

	/**
	 * Devuelve el valor de la propiedad address.
	 *
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad address.
	 *
	 * @param string $address
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Devuelve el valor de la propiedad personal.
	 *
	 * @return string
	 */
	public function getPersonal() {
		return $this->personal;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad personal.
	 *
	 * @param string $personal
	 */
	public function setPersonal($personal) {
		$this->personal = $personal;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$sb = new StringBuffer();
		$sb->append($this->address);
		if(!is_null($this->personal) && empty($this->personal)) {
			$sb->append(' <')->append($this->personal)->append('>');
		}
		return $sb->__toString();
	}
	
	/**
	 * @throws AddressException
	 * @return boolean
	 */
	public function validate() {
		
	}
}
	
?>
