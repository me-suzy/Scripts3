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
 * @version $Id: DataSourcesConfig.class.php,v 1.3 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql.config
 */

import('poop.sql.config.DataSourceConfig');
 
class DataSourcesConfig {

	private $dataSources;

	public function __construct() {
		$this->dataSources = array();
	}

	/**
	 * @param DataSourceConfig $dataSource
	 */
	public function addDataSource(DataSourceConfig $dataSource) {
		array_push($this->dataSources, $dataSource);
	}

	/**
	 * @param string $id
	 * @return DataSource
	 */
	public function getDataSource($id = 'default') {
		foreach($this->dataSources as $dataSource) {
			if($dataSource->getId() == $id) {
				return $dataSource;
			}
		}

		return null;
	}
	
	/**
	 * @return array
	 */
	public function getDataSources() {
		return $this->dataSources;
	}
}
	
?>
