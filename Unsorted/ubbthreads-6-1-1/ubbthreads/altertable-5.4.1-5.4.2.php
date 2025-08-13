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

  echo "Adding a field to store the default aged threads to display on a per board basis...\n";
  $query = " 
    ALTER TABLE {$config['tbprefix']}Boards
    ADD   Bo_ThreadAge $int4
  ";
  $dbh -> do_query($query);

  echo "Alterting the {$config['tbprefix']}Posts table to store if each post uses html/markup/none...\n";
  $query = " 
    ALTER TABLE {$config['tbprefix']}Posts
    ADD   B_Convert $varchar(10) default 'markup'
  ";
  $dbh -> do_query($query);

  $query = "
    UPDATE {$config['tbprefix']}Posts
    SET    B_Convert = 'markup'
  ";
  $dbh -> do_query($query);


// Disconnect
  echo "Done...<br>"; 

  if ($auto) {
    echo "<br><br><a href=\"upgrade.php\">Click here</a> to see if there are any more altertables that need to be run.";
   }

?>

