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
 * @version $Id: DataSource.class.php,v 1.11 2005/10/12 21:01:00 slizardo Exp $
 * @package poop.sql
 */

import('poop.xml.XMLException');
import('poop.io.FileNotFoundException');
import('poop.sql.config.DataSourcesConfig');
import('poop.cache.CacheUtil');
 
DataSource::init();

final class DataSource {

	private static $store;
	
	public static function init() {
		$xmlPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'datasources.xml'));

		/**
		 * If the file WEB-INF/datasources.xml does not exist , we dont do nothing.
		 */
		if(!file_exists($xmlPath)) {
			return;
		}

		$xsdPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_MOLINS, 'schemas', 'datasources.xsd'));
		$serPath = implode(DIRECTORY_SEPARATOR, array(CONF_DIR_APP, 'WEB-INF', 'work', 'datasources.ser'));	
	
		if(filemtime($xmlPath) >= time()-CacheUtil::T_MINUTE && file_exists($serPath)) {
			$serialized = file_get_contents($serPath);
			self::$store = unserialize($serialized);
			return;
		}
		
		self::$store = new DataSourcesConfig();
	
		$dom = new DomDocument();
		if(!@$dom->load($xmlPath)) {
			throw new XMLException(basename($xmlPath));
		}
		if(!@$dom->schemaValidate($xsdPath)) {
			throw new XMLException(_('error validando esquema'), basename($xsdPath));
		}
		
		$xPath = new DomXpath($dom);

		$dataSources = $xPath->query('/datasources/datasource');
		for($i = 0; $i < $dataSources->length; $i++) {
			$dataSource = $dataSources->item($i);

			$dataSourceConfig = new DataSourceConfig();
			if($dataSource->hasAttribute('id')) {
				$dataSourceConfig->setId($dataSource->getAttribute('id'));
			} else {
				$dataSourceConfig->setId('default');
			}

			$url = $xPath->query('./url', $dataSource);
			$dataSourceConfig->setUrl($url->item(0)->textContent);
			
			$username = $xPath->query('./username', $dataSource);
			$dataSourceConfig->setUsername($username->item(0)->textContent);

			$password = $xPath->query('./password', $dataSource);
			$dataSourceConfig->setPassword($password->item(0)->textContent);

			self::$store->addDataSource($dataSourceConfig);
		}

		$serialized = serialize(self::$store);
		file_put_contents($serPath, $serialized);		
	}

	/**
	 * @return DataSource
	 */
	public static function getDefaultDataSource() {
		return self::getDataSource('default');
	}
	
	/**
	 * @param string $id
	 * @return DataSource
	 */
	public static function getDataSource($id) {
		return self::$store->getDataSource($id);
	}
}
	
?>
