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
 * @version $Id: Template.class.php,v 1.4 2005/10/12 21:00:59 slizardo Exp $
 * @package poop.presentation
 */

@require_once 'Smarty.class.php';
		
class Template extends Smarty {

	private $template;
	
	public function __construct() {
		$path = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work'));
		
		$this->compile_dir = $path.DIRECTORY_SEPARATOR.'templates_c';
		$this->config_dir = $path.DIRECTORY_SEPARATOR.'configs';
		$this->cache_dir = $path.DIRECTORY_SEPARATOR.'cache'; 

		parent::Smarty();
	}

	/**
	 * @param boolean $debugging
	 */
	public function setDebugging($debugging) {
		$this->debugging = $debugging;
	}
	
	/**
	 * @param boolean $caching
	 */
	public function setCaching($caching) {
		$this->caching = $caching;
	}

	/**
	 * @param string $template_dir
	 */
	public function setTemplateDir($template_dir) {
		$this->template_dir = $template_dir;
	}

	/**
	 * @param string $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * @param string $compile_id
	 */
	public function setCompileId($compile_id) {
		$this->compile_id = $compile_id;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->fetch($this->template);
	}
}
	
?>
