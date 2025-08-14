<?php

/** 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the LGPL License.
 *
 * Copyright(c) 2005 by Santiago Lizardo Oscares. All rights reserved.
 *
 * The latest version of Molins can be obtained from <http://www.phpize.com>.
 *
 * @author vguardiola <vguardiola@sf.net>
 * @version $Id: IpUtilities.class.php,v 1.2 2005/10/12 21:01:04 slizardo Exp $
 * @package poop.util
 */

import('poop.sql.*');
import('poop.util.zip.*');
import('poop.io.FileInputStream');

class IpUtilities {

	/**
	 * User Ip
	 */
	private $remoteIp;

	private $remoteIpLongFormat;

	private $serverDatFile = "http://www.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip";

	private $dataSource;

	private $connection;

	private $statement;

	/**
	 * @param string $ip
	 */
	public function __construct($ip = null) {
		if(is_null($ip)) $ip = $_SERVER['REMOTE_ADDR'];
		$this->setRemoteIp($ip);
		$this->dataSource = DataSource::getDefaultDataSource(); // Cogemos el datasource definido en el xml.
		$this->connection = $this->dataSource->getConnection(); // realizamos una conexion
		$this->statement  = $this->connection->createStatement();
	}

	public function __destruct() {
		$this->connection->close();
		$this->statement->close();
	}
	
	/**
	 * @param string $pRemoteIp
	 */
	private function setRemoteIp($pRemoteIp) {
		$this->remoteIp = $pRemoteIp;
		$this->remoteIpLongFormat = ip2long($pRemoteIp);
	}

	private function getRemoteIp() {
		return $this->remoteIp;
	}

	private function getRemoteIpLongFormat() {
		return $this->remoteIpLongFormat;
	}

	/**
	 * @param string $pServerDatFile
	 */
	public function setServerDatFile($pServerDatFile) {
		$this->serverDatFile = $pServerDatFile;
	}
	
	/**
	 * return the name of the country for the IP of the user
	 *
	 * @return string
	 */
	public function getCountry() {
		$query = "SELECT cn FROM IpUtilities_CountryCodes NATURAL JOIN IpUtilities_IpRanges  WHERE ".$this->remoteIpLongFormat." BETWEEN start AND end";
		$resultSet = $this->statement->executeQuery($query);
		$resultSet->next();
		return  $resultSet->getString('cn');
	}

	public function getFlag() {
		$query = "SELECT cc FROM IpUtilities_CountryCodes NATURAL JOIN IpUtilities_IpRanges WHERE ".$this->remoteIpLongFormat." BETWEEN start AND end";
		$resultSet = $this->statement->executeQuery($query);
		$resultSet->next();
		$cc = strtolower($resultSet->getString('cc'));
		
		return "http://www.crwflags.com/fotw/images/".substr($cc,0,1)."/".$cc.".gif";
	}
	
	/**
	 * load a new data from maxmind
	 * @todo checkall the queries
	 */
	private function getDataBase() {
		$query = "SELLECT cc FROM IpUtilities_CountryCodes LIMIT 1";
		$resultSet = $this->statement->executeQuery($query);
		$resultSet->next();
		if(!$resultSet->getString('cc')) {
			$zipData = new FileInputStream($this->serverDatFile);
			$zipFile = new ZipFile(CONF_DIR_APP.DIRECTORY_SEPARATOR."WEB-INF".DIRECTORY_SEPARATOR."work".DIRECTORY_SEPARATOR."geoip.zip");
			$zipFile->createNewFile($zipData->getFileData());
			$csv = $zipFile->extractFileToString("GeoIPCountryWhois.csv");
			$csvFile = new File((CONF_DIR_APP.DIRECTORY_SEPARATOR."WEB-INF".DIRECTORY_SEPARATOR."work".DIRECTORY_SEPARATOR."GeoIPCountryWhois.csv"));
			$csvFile->createNewFile($csv);
			$query = "DELETE FROM IpUtilities_csv";
			$this->statement->executeQuery($query);
			$query = "DELETE FROM IpUtilities_CountryCodes";
			$this->statement->executeQuery($query);
			$query = "DELETE FROM IpUtilities_IpRanges";
			$this->statement->executeQuery($query);
			$query = 'LOAD DATA LOCAL INFILE "'.CONF_DIR_APP.DIRECTORY_SEPARATOR.'WEB-INF'.DIRECTORY_SEPARATOR.'work'.DIRECTORY_SEPARATOR.'GeoIPCountryWhois.csv" INTO TABLE IpUtilities_csv FIELDS TERMINATED BY "," ENCLOSED BY \'"\' ESCAPED BY "\\" LINES TERMINATED BY "\n"';
			$resultSet = $this->statement->executeSpecialQuery($query);
			$query = "INSERT INTO IpUtilities_CountryCodes SELECT DISTINCT NULL,cc,cn FROM IpUtilities_csv ORDER BY cc";
			$resultSet = $this->statement->executeUpdate($query);
			$query = "INSERT INTO IpUtilities_IpRanges SELECT start,end,ci FROM IpUtilities_csv NATURAL JOIN IpUtilities_CountryCodes";
			$resultSet = $this->statement->executeUpdate($query);
		}
	}
}

?>
