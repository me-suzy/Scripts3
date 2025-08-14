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
 * @version $Id: NewsFormBean.class.php,v 1.6 2005/09/28 16:28:21 slizardo Exp $
 * @package sample
 */

import('poop.http.HttpRequest');
import('poop.mvc.ActionForm');
import('poop.mvc.ActionMapping');
import('poop.mvc.ActionErrors');
import('poop.mvc.ActionMessage');
	
class NewsFormBean extends ActionForm {

	private $title;
	private $author;
	private $details;

	/**
	 * Devuelve el valor de la propiedad title.
	 *
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad title.
	 *
	 * @param mixed
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Devuelve el valor de la propiedad author.
	 *
	 * @return mixed
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad author.
	 *
	 * @param mixed
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * Devuelve el valor de la propiedad details.
	 *
	 * @return mixed
	 */
	public function getDetails() {
		return $this->details;
	}

	/**
	 * Asigna el valor pasado como parametro a la propiedad details.
	 *
	 * @param mixed
	 */
	public function setDetails($details) {
		$this->details = $details;
	}

	public function validate(ActionMapping $mapping, HttpRequest $request) {
		$actionErrors = new ActionErrors();

		if(is_null($this->title) || empty($this->title)) {
			$actionErrors->add('title', new ActionMessage('security.error.title.required'));
		}

		if(is_null($this->author) || empty($this->author)) {
			$actionErrors->add('author', new ActionMessage('security.error.author.required'));
		}

		if(is_null($this->details) || empty($this->details)) {
			$actionErrors->add('details', new ActionMessage('security.error.details.required'));
		}
		
		return $actionErrors;
	}
}
	
?>
