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
// Require the library
   require ("main.inc.php");  

// Postgres handles the INT type differently.  So we need to prepare for this
  $int4  = "INT(4) UNSIGNED";
  $int9  = "INT(9) UNSIGNED";
  $int11 = "INT(11) UNSIGNED";
  $int1  = "INT(1) UNSIGNED";

// Althout Oracle currently isn't supported, this is here for future versions
// Oracle uses VARCHAR2 instead of VARCHAR and does not have TEXT
  $varchar = "varchar";
  $text    = "text";

  if ($config['dbtype'] == "postgres") {
    $int4  = "INT4";
    $int9  = "INT4";
    $int11 = "INT4";
    $int1  = "INT4";
  }
  elseif ($config['dbtype'] == "Oracle") {
    $int4    = "NUMBER(4)";
    $int9    = "NUMBER(9)";
    $int11   = "NUMBER(11)";
    $int1    = "NUMBER(1)";
    $varchar = "VARCHAR2";
    $text    = "VARCHAR(4000)"; // Oracle8 allows 4000, Oracle 7 allows 2000
  }

//
// Figure out if the SQL server supports AUTO INCREMENT
  $autoincs = "";

  if ($config['dbtype'] == 'mysql') {
    $autoincs = "AUTO_INCREMENT";
  }

  echo "Altering Users table...<br>";
  $query = "
    ALTER TABLE {$config['tbprefix']}Users
    ADD   U_StartPage $varchar(2) DEFAULT 'cp',
    ADD   U_Favorites $varchar(250) DEFAULT '-'
  "; 
  $dbh -> do_query($query);


   echo "Creating the table to store favorites and reminders...<br>";
   $indexes = "";
   if ($config['dbtype'] == "mysql") {
      $indexes = ",INDEX FAV_indx1 (F_Owner)";
   }
   $query = "
        CREATE TABLE {$config['tbprefix']}Favorites(
        F_Number $int9 DEFAULT '0' $autoincs PRIMARY KEY,
        F_Thread $int9 DEFAULT '0' NOT NULL,
        F_Owner $varchar(250) NOT NULL,
        F_LastPost $int4,
        F_Type $varchar(1)
        $indexes 
        )
   ";
   $dbh -> do_query($query);
   if ($config['dbtype'] != "mysql") {
      $dbh -> do_query("CREATE INDEX Fav_indx1 ON {$config['tbprefix']}Favorites (F_Owner)");
   }

   if (!$autoincs) {
      $dbh -> do_query("CREATE SEQUENCE {$config['tbprefix']}Favorites_seq");
   }

   echo "Creating the table to store posts that a moderator has been notified on...<br>";
   $indexes = "";
   if ($config['dbtype'] == "mysql") {
      $indexes = ",INDEX modnotif_indx1 (M_Number)";
   }

   $query = "
CREATE TABLE {$config['tbprefix']}ModNotify(
         M_Number $int9 DEFAULT '0' NOT NULL
         $indexes
)";

   $dbh -> do_query($query);
   if ($config['dbtype'] != "mysql") {
      $dbh -> do_query("CREATE INDEX modnotif_indx1 ON {$config['tbprefix']}ModNotify (M_Number)");
   }
   	


// Disconnect
  print "Done...<br>"; 

  if ($auto) {
    echo "<br><br><a href=\"upgrade.php\">Click here</a> to see if there are any more altertables that need to be run.";
   }
?>

