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

ignore_user_abort(1);

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

// Alterting the boards table
  echo "Alterting the boards table to better store the moderators";
  $query = "
    ALTER TABLE {$config['tbprefix']}Boards
    ADD Bo_Moderators $text
  ";
  $dbh -> do_query($query);

  $query = "
    SELECT Mod_Username,Mod_Board
    FROM   {$config['tbprefix']}Moderators
  ";
  $sth = $dbh -> do_query($query);
  while (list($username,$board) = $dbh -> fetch_array($sth)) {
    $array[$board] .="$username,";
  }

  while(list($key,$value) = each($array)) {
    $users = addslashes($value);
    $query = "
       UPDATE {$config['tbprefix']}Boards
       SET Bo_Moderators = ',$users'
       WHERE  Bo_Keyword='$key'
    ";
    $dbh -> do_query($query);
  }
  


  echo "Adding auxillary info to online table...<br>";
  $query = " 
    ALTER TABLE {$config['tbprefix']}Online
    ADD   O_Extra $varchar(200),
    ADD   O_Read  $varchar(255)
  ";
  $dbh -> do_query($query);

  echo "Adding an option for users to have this aux info displayed...<br>";
  $query = "
    ALTER TABLE {$config['tbprefix']}Users
    ADD   U_OnlineFormat $varchar(3)
  ";
  $dbh -> do_query($query);


  echo "Enlarging signature field...<br>";
  $query = " 
    ALTER TABLE {$config['tbprefix']}Posts
    CHANGE   B_Signature B_Signature text
  ";
  $dbh -> do_query($query);

  echo "Adding a new security option to set if users can start new topics or only reply to current topics....<br>";
  $query = " 
    ALTER TABLE {$config['tbprefix']}Boards
    ADD Bo_Reply_Perm $varchar(250) DEFAULT '-3-4-' 
  ";
  $dbh -> do_query($query);


  echo "Setting default reply privileges to the current write privileges...<br>";
  $query = "
     SELECT Bo_Keyword,Bo_Write_Perm
     FROM   {$config['tbprefix']}Boards
  ";
  $sth = $dbh -> do_query($query);
  while (list($Board,$Write) = $dbh -> fetch_array($sth)) {
     $query = "
        UPDATE {$config['tbprefix']}Boards
        SET    Bo_Reply_Perm = '$Write'
        WHERE  Bo_Keyword = '$Board'
     ";
     $dbh -> do_query($query);
  }

// Adding an index to {$config['tbprefix']}Posts for speed improvement
  echo "Adding an index to {$config['tbprefix']}Posts for speed improvment...(NOTE: SOME OF THESE MAY FAIL DEPENDING ON WHEN YOU FIRSTS INSTALLED UBB.threads BUT THAT IS OK<br>";
  $query = "
     ALTER TABLE {$config['tbprefix']}Posts
     DROP INDEX w3t_Postsindex9
  ";
  $dbh -> do_query($query);
  $query = "
     ALTER TABLE {$config['tbprefix']}Posts
     DROP INDEX topic_ndx
  ";
  $dbh -> do_query($query);
  $query = "
     ALTER TABLE {$config['tbprefix']}Posts
     ADD INDEX topic_index (B_Topic,B_Board)
  ";
  $dbh -> do_query($query);

// Disconnect
  echo "Done...<br>"; 

?>

