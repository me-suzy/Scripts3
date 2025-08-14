<?
/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/
	/* $sessionID : current session ID */
	$sessionID = "";

	/* $sessionVars: session variables in a hash */
	$sessionVars = array();

	/* $sessionVarsPlain: dumped session variables */
	$sessionVarsPlain = "";

	/* $sessionTimeout: timeout of a session */
	$sessionTimeout = 14/*days*/ * 60/*hours*/* 60/*minutes*/* 60/*second*/;

		/*
			getSessionVars()

			retrieves the variables for specified session
			session id is taken from $sessionID variable
		*/

		function getSessionVars ()
		{
			global $sessionID, $sessionVars, $sessionVarsPlain, $dbfix;

			$query = "SELECT * FROM ".$dbfix."_sessions WHERE id='$sessionID'";
			//print $query;
		
			$result = mysql_query ($query);

			if ($result)
			{
				/* SQL query succeeded - retrieve results */

				/* check if such session exists... */
				if (mysql_num_rows($result)>0)
				{
					$row = mysql_fetch_array($result);
					$serialized = $row[vars];

					$sessionVarsPlain = $serialized;

					$sessionVars = unserialize($serialized);
					if (!is_array($sessionVars))
						$sessionVars = array();

					return 1;
				}
				else
				{
					/* session with specified ID does not exist */
					$sessionID = "";
					$sessionVars = array();
					$sessionVarsPlain = "";
					return 0;
				}
			}
			else
			{
//				die ( mysql_error() );
				/* SQL query failed */
				$sessionID = "";
				$sessionVars = array();
				$sessionVarsPlain = "";
				return 0;
			}
		}

		/* 
			putSessionVars()

			updates current session variables on server
		*/
		function putSessionVars ()
		{
			global $sessionID, $sessionVars, $sessionVarsPlain ,$dbfix;

			if (strlen($sessionID)==0)
				return 0;

			if ( !is_array($sessionVars) )
				$sessionVars = array();

			/* pack the variables for storing in DB */
			$sessionVarsPlain = serialize ($sessionVars);

			$query = "UPDATE ".$dbfix."_sessions SET vars='".addslashes($sessionVarsPlain)."' WHERE id='".addslashes($sessionID)."'";
			$result = mysql_query ($query);

			if ($result)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}

		/*
			createSession()

			creates new sessionID and corresponding record on server
		*/
		function createSession ()
		{
			global $sessionID, $sessionVars, $sessionVarsPlain, $dbfix;

			/* generate session ID */
			$sessionID = md5(uniqid(rand()));

			/* init all sessions-related variables */
			$sessionVarsPlain = "";
			$sessionVars = array();

			
			/* create session on server */
			$query = "INSERT INTO ".$dbfix."_sessions (id,vars,created,last_visit) VALUES ('".addslashes($sessionID)."','',NULL,NULL)";
			$result = mysql_query($query);

			if ($result)
			{
				return 1;
			}
			else
			{
//				die ( mysql_error() );
				return 0;
			}
		}

		/* 
			removeSession()

			remove session from server: to be used, for example, when user logout
		*/
		function removeSession()
		{
			global $sessionID, $sessionVars, $sessionVarsPlain, $dbfix;

			if ( strlen($sessionID)>0 )
			{
				$query = "DELETE FROM ".$dbfix."_sessions WHERE id='".AddSlashes($sessionID)."'";
				$result = mysql_query($query);
				if ($result)
				{
					$sessionVars = array();
					$sessionVarsPlain = "";
					$sessionID = "";
					return 1;
				}
				else
				{
					return 0;
				}
			}
			else
			{
				return 0;
			}
		}

		function putSessionTime()
		{
			global $sessionID, $dbfix;
			mysql_query ( "UPDATE ".$dbfix."_sessions SET last_visit=NULL WHERE id='".$sessionID."'" );
		}
  
	/*	main(): get sessionID from external source and if there
		is no session existing - create new session.
	*/
	if ( !empty($SESSION_ID) )
		$sessionID = "".$SESSION_ID;
	else
		$sessionID = "".$YA_SESSION_ID;

	getSessionVars();
	if ( empty($sessionID) )
	{
		createSession();
		setcookie ( "YA_SESSION_ID", $sessionID, time()+$sessionTimeout );
	}

	putSessionTime();
	$sessionIDU = urlencode($sessionID);
?>
