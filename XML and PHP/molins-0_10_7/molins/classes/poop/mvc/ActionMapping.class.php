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
 * @version $Id: ActionMapping.class.php,v 1.12 2005/10/13 13:42:25 slizardo Exp $
 * @package poop.mvc
 */

class ActionMapping {

	private $parameter;
	private $forwards;
	
	/**
	 * Devuelve el valor de la propiedad parameter.
	 *
	 * @return string
	 */
	public function getParameter() {
		return $this->parameter;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad parameter.
	 *
	 * @param string $parameter
	 */
	public function setParameter($parameter) {
		$this->parameter = $parameter;
	}
	
	/**
	 * @param array $forwards
	 */
	public function setForwards($forwards) {
		$this->forwards = $forwards;
	}

	/**
	 * @param string $mapping
	 * @return ActionForward
	 */
	public function findForward($mapping) {
		if(array_key_exists($mapping, $this->forwards)) {
			return $this->forwards[$mapping];
		} else {
			throw new Exception(_('elemento forward no definido'));
		}
	}
}

?>
