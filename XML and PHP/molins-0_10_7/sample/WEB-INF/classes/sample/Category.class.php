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
 * @version $Id: Category.class.php,v 1.1 2005/09/28 16:28:21 slizardo Exp $
 * @package sample
 */

import('poop.dbobjects.DbObject');
	
class Category extends DbObject {

	/**
	 * Devuelve el valor de la propiedad name.
	 *
	 * @return mixed
	 */
	public function getName() {
		return $this->getString('name');
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad name.
	 *
	 * @param mixed
	 */
	public function setName($name) {
		$this->setString('name', $name);
	}


	/**
	 * Devuelve el valor de la propiedad description.
	 *
	 * @return mixed
	 */
	public function getDescription() {
		return $this->getString('description');
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad description.
	 *
	 * @param mixed
	 */
	public function setDescription($description) {
		$this->setString('description', $description);
	}
}
	
?>
