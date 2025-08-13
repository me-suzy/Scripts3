<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998,1999 SH Online Dienst GmbH
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc.php,v 1.23 1999/08/25 11:40:48 kk Exp $
 *
 */ 

/**************************************************\
///////////////// RELATA MATE \\\\\\\\\\\\\\\\\\\\\\
\**************************************************/


// the TripleDES key - 56bit
DEFINE("Key", "hf8329nf310k1lj092fn3901m1f0231mf3109f3j0fm91m3f90414341");

class relata_db extends DB_Sql 
{
  var $Host;
  var $Database;
  var $User;
  var $Password;
}

class relata_ct_sql extends CT_Sql 
{
  var $database_class = "relata_db";      ## Which database to connect...
  var $database_table = "active_sessions"; ## and find our session data in this table.
}

class relata_session extends Session 
{
  var $classname = "session_id";

  var $cookiename     = "";                ## defaults to classname
  var $magic          = "salkjdflka";      ## ID seed (should be hard to guess)
  var $mode           = "cookie";          ## We propagate session IDs with cookies
  var $fallback_mode  = "get";
  var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
  var $that_class     = "relata_ct_sql";   ## name of data storage container
  var $gc_probability = 5;  
}

class relata_challenge_auth extends Auth 
{
  var $classname      = "relata_challenge_auth";

  var $lifetime       =  60;

  var $magic          = "lksfwewisdytaf";  	## Challenge seed (should be hard to guess)
  var $database_class = "relata_db";
  var $database_table = "relata_user";

/*
  display the login form
  it recieves the template object as a paramater and returns the same object
  it uses the login.htm file found in the /templates/login/ directory
*/

function auth_loginform()
{
	    global $sess;
		global $session_id;
	    global $_PHPLIB;
		global $login_error, $submit;
		
		$challenge = md5($this->magic);
	
		// start a new template
		$template = new Template($_PHPLIB["basedir"] . "templates/login");
	
		$template->set_file("loginform","login.htm");
		
		// if page hasn't been submitted don't show an error
		if(!$submit)
		{
			$login_error = "";
		}
		
		$template->set_var(array(
			"SESSION_ID"	=>	$session_id,
			"WWW_DIR"		=>	$_PHPLIB["webdir"],
			"PHP_SELF"		=>	$this->url(), 
			"CHALLENGE"		=>	$challenge,
			"ERROR"			=>	$login_error
			));
	
		$template->parse("login","loginform");
		$template->p("login");
	}

  	// validate the user login
  	// $username = email
  	function auth_validatelogin()
  	{
		global $username, $password;
		global $response;
		global $sess;
	
		if($username == "")
		{
			return false;
		}
		
		$sess->register("uid");
		
		$challenge = md5($this->magic);
		
		// take off the spaces and make it lower case
		$username = strtolower(trim($username));
		$password = strtolower(trim($pass));
		
		// get the username, password & uid from the db
		$query = "SELECT user_id,login,password FROM " . $this->database_table . " WHERE login='$username'";
	    $this->db->query($query);
	    $this->db->next_record();
		
		$uid = $this->db->f("user_id");
	    
		// this should match the input from the user
	    $exspected_response = md5($this->db->f('login') . ":" . $this->db->f('password') . ":" . $challenge);
		
		
		// the MD5(username:password:challenge) string
		if($response)
		{
		    if ($exspected_response != $response) 
			{
		      	return false;
		    } 
			else 
			{
		      	return $uid;
			}
		}
  	}
}

/*
/* uncomment these to enable different levels of permission for users
class relata_perm extends Perm {
  var $classname = "relata_perm";
  
  var $permissions = array(
                            "user"       => 1
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    global $_PHPLIB;
    
    include($_PHPLIB["libdir"] . "perminvalid.ihtml");
  }
}

// relata speed fix
class relata_user extends User
{
  var $classname = "relata_user";

  var $magic          = "djfhlaksjflkdsja";     ## ID seed (should be hard to guess)
  var $that_class     = "relata_ct_sql"; 		## data storage container
}
*/


?>
