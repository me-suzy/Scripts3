<?
/*****************************************************************************
 *                                                                           *
 * Shop-Script 2.0                                                           *
 * Copyright (c) 2002-2003 Articus consulting group. All rights reserved.    *
 *                                                                           *
 ****************************************************************************/

	//authorized access check

	if (isset($log)) //look for user in the database
	{
		$q = db_query("SELECT cust_password FROM ".CUSTOMERS_TABLE." WHERE Login='$log'") or die (db_error());

		$row = db_fetch_row($q);
		$row[0] = trim($row[0]);
		if (!$row || !isset($pass) || $row[0] != $pass) //unauthorized access
		{
			session_unregister("log");
			session_unregister("pass");
			unset($log);
			unset($pass);

		}
	}

?>