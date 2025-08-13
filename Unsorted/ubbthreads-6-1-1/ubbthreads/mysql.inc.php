<?
/*
# UBB.threads, Version 6
# Official Release Date for UBB.threads Version6: 06/05/2002

# First version of UBB.threads created July 30, 1996 (by Rick Baker).
# This entire program is copyright Infopop Corporation, 2002.
# For more info on the UBB.threads and other Infopop
# Products/Services, visit: http://www.infopop.com

# Program Author: Rick Baker.

# You may not distribute this program in any manner, modified or otherwise,
# without the express, written written consent from Infopop Corporation.

# Note: if you modify ANY code within UBB.threads, we at Infopop Corporation
# cannot offer you support-- thus modify at your own peril :)
# ---------------------------------------------------------------------------
*/

// #########################################################################
// Database class for mysql functions
// #########################################################################
  class sql {

  // #######################################################################
  // Connect to the database
  // #######################################################################
    function connect() {
      
      global $config;  

      if (!isset($this->dbh)) {
        if (!$config['persistent']) {
           $this->dbh = mysql_connect($config['dbserver'],$config['dbuser'],$config['dbpass']);
        }
        else {
           $this->dbh = mysql_pconnect($config['dbserver'],$config['dbuser'],$config['dbpass']);
        }
 
      }
      if (!$this->dbh) {
        $this->not_right("Unable to connect to the database!");
      }
      mysql_select_db($config['dbname'],$this->dbh);
     
    }

  // #######################################################################
  // Grab the error descriptor
  // #######################################################################
    function graberrordesc() {
      $this->error=mysql_error();
      return $this->error;
    }

  // #######################################################################
  // Grab the error number
  // #######################################################################
    function graberrornum() {
      $this->errornum=mysql_errno();
      return $this->errornum;
    }

  // #######################################################################
  // Do the query
  // #######################################################################
    function do_query($query) {
      global $querycount,$debug,$user,$mysqltime;
      $querycount++; 

		// If we are in debug mode then we are going to EXPLAIN each
		// query but only to admins
      if ($user['U_Status'] == "Administrator") { 
      	if ($debug) { 
				$query = str_replace(","," , ",$query);
				$this ->sth =  mysql_query("EXPLAIN $query",$this->dbh);
				if ($this -> sth) {
		   		$numFields = $this -> num_fields($this -> sth);
				}
				echo "<table border=\"1\" width=\"100%\"><tr bgcolor=\"BCBCBC\"><td colspan=\"$numFields\"><b>Query:</b> $query</td></tr>";
				if ($numFields) {
					echo "<tr bgcolor=\"#EcEcEc\">";
            	for ( $i=0; $i<$numFields; $i++) {
          			echo "<td>" . $this -> field_name($this -> sth, $i) ."</td>";
      			}
					echo "</tr><tr>";
					$results = $this -> fetch_array($this -> sth);
					for ($i=0;$i<=sizeof($results);$i++) {
						echo "<td>$results[$i]</td>";
					}
					echo "</tr>";
				}
      	}
		}
      $mytimea = getmicrotime();
      $this->sth = mysql_query($query,$this->dbh);
      $mytimeb = getmicrotime();
      $mytime  = $mytimeb - $mytimea;
      $mytime = round($mytime,3);
      $mysqltime = $mysqltime + $mytime;

		// If in debug mode we will also show the time needed to execute
		// the query.
      if ($user['U_Status'] == "Administrator") { 
			if ($debug) {
				echo "<tr bgcolor=\"BCBCBC\"><td colspan=\"$numFields\"><b>Query took a total of $mytime seconds.</td></tr></table><br>";
			}
		}
      if (!$this->sth) {
        $this->not_right("Unable to do_query: $query");
      }
      return $this->sth;
    }

  // #######################################################################
  // Fetch the next row in an array
  // #######################################################################
    function fetch_array($sth) {
      
      $this->row = mysql_fetch_array($sth);
    
      return $this->row;
    }


  // #######################################################################
  // Get a result row as an enumerated array
  // #######################################################################
		function mysql_fetch_row($sth) {

			$this->row = mysql_fetch_row($sth);
			return $this->row;

		}

  // #######################################################################
  // Finish the statement handler
  // #######################################################################
    function finish_sth($sth) {
      return @mysql_free_result($this->sth);
    }

  // #######################################################################
  // Grab the total rows
  // #######################################################################
    function total_rows($sth) {
      return mysql_num_rows($this->sth);
    }

  // #######################################################################
  // Grab the number of fields
  // #######################################################################
     function num_fields($sth) {
       return mysql_num_fields($this->sth);
     }

  // #######################################################################
  // Grab the field name 
  // #######################################################################
     function field_name($sth,$i) {
       return mysql_field_name($this->sth,$i);
     }

  // #######################################################################
  // Die
  // #######################################################################
    function not_right($error) {
      global $user;
     
      $What = find_environmental ("SCRIPT_NAME"); 
      if (!$What) {
         $What = find_environmental("PHP_SELF");
      }
      if ( ($user['U_Status'] != "Administrator") 
         && ( (!stristr($What,"createtable")) && (!stristr($What,"altertable")) && (!stristr($What,"ubbimport.php")) ) ) {
            $error = "Database error only visible to forum administrators";
      }
      else {
         $this->errordesc = mysql_error();
      }
      echo "<b>SQL ERROR:</b> <i>$error</i><br /> $this->errordesc";
    }

  }

?>
