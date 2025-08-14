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
 * @version $Id: DBAppender.class.php,v 1.8 2005/10/11 15:57:31 slizardo Exp $
 * @package poop.logging
 */
	
import('poop.logging.Appender');
import('poop.logging.Level');
import('poop.sql.DataSource');
	
class DBAppender extends Appender {

	/**
	 * Este appender graba los mensaje de log en una tabla<br />
	 * de la base de datos.
	 *
	 * @param int $level
	 * @param string $message
	 * @param Exception $exception
	 */
	public function log($level, $message, $exception = null) {
		$SQL = sprintf("INSERT INTO Molins_log (level, message) VALUES ('%s', '%s')",
	       		Level::labelFor($level),
			$message
		);

		$dataSource = DataSource::getDefaultDataSource();
		$connection = $dataSource->getConnection();
		$statement = $connection->createStatement();
		$statement->executeUpdate($SQL);
		$statement->close();
		$connection->close();
	}
}

?>
