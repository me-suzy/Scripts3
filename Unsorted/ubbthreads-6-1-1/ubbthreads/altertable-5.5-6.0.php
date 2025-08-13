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

if ($step) {
   ${$step()};
}

if (!$step) {
   echo "Because this is a very large upgrade this altertable has been split into several steps.  Depending on the number of users you have it could take quite awhile for some of these steps to complete.  Make sure you close your forums before running this altertable.  You can do this by editing your config.inc.php file.";
   echo "<br><br>";
   echo "Step 1 will do the following:<br />\n";
   echo "Alter the {$config['tbprefix']}Online table for optimizations..<br />\n";
   echo "New fields for user rating...<br />\n";
   echo "New fields for post ratings...<br />\n";
   echo "New field for attaching post to user...<br />\n";
   echo "Need a placeholder user for anonymous posts and for any posts from users that have been deleted...<br />\n";
   echo "<br /><br />";
   echo "<a href=\"altertable-5.5-6.0.php?step=step1\">Proceed to step1</a>";
}


function step1() {

  global $dbh,$config;

// Alterting the boards table
  $query = "
    ALTER TABLE {$config['tbprefix']}Online
    CHANGE O_Username O_Username varchar(30) NOT NULL ,
    ADD    O_Type varchar(1) NOT NULL,
    ADD    INDEX type_index (O_Type)
  ";
  $dbh -> do_query($query);

// Alter the Users table
  $query = "
     ALTER TABLE {$config['tbprefix']}Users
     ADD U_Rating varchar(5) DEFAULT '0',
     ADD U_Rates int(4) DEFAULT '0',
     ADD U_RealRating INT(1),
     ADD U_PicWidth INT(4),
     ADD U_PicHeight INT(4), 
     ADD U_Palprofile INT(11),
     CHANGE U_Password U_Password VARCHAR(32) NOT NULL,
     CHANGE U_TempPass U_TempPass VARCHAR(32)
  ";
  $dbh -> do_query($query);

  $query = "
    CREATE TABLE {$config['tbprefix']}Ratings (
      R_What varchar(100) NOT NULL,
      R_Rater varchar(100) NOT NULL,
      R_Rating int(1) DEFAULT '0',
      R_Type varchar(1) NOT NULL,
      INDEX r_indx1(R_What,R_Rater,R_Type)
    )
  ";
  $dbh -> do_query($query);

  $query = "
     ALTER TABLE {$config['tbprefix']}Posts
     ADD B_PosterId INT(9) UNSIGNED NOT NULL,
     ADD B_Rating varchar(5) DEFAULT '0',
     ADD B_Rates int(4) DEFAULT '0',
     ADD B_RealRating INT(1),
     ADD INDEX ID_ndx(B_PosterId)
  ";
  $dbh -> do_query($query);

  $query = "
     INSERT INTO {$config['tbprefix']}Users
     (U_Username,U_Groups)
     VALUES
     ('**DONOTDELETE**','-4-')
  ";
  $dbh -> do_query($query); 

  echo "Step 1 has been completed.<br>";
  echo "Step 2 will be incrementing everone's userid by 1.  This needs to be done because userid 1 now is the placeholder user for anonymous posts and posts from users that have been deleted.<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step2\">Proceed to step 2</a>";
}


function step2() {
  global $dbh,$config;

  $query = "
    ALTER TABLE {$config['tbprefix']}Users
    CHANGE U_Number U_Number INT(9) NOT NULL,
    DROP PRIMARY KEY
  ";
  $dbh -> do_query($query);

  $query = "
    UPDATE {$config['tbprefix']}Users
    SET U_Number=U_Number+1
  ";
  $dbh -> do_query($query);

  $query = "
    ALTER TABLE {$config['tbprefix']}Users
    CHANGE U_Number U_Number INT(9) AUTO_INCREMENT PRIMARY KEY
  ";
  $dbh -> do_query($query);

  echo "Step 2 has been completed.<br>";
  echo "The next step will create an index on the B_Username field<br>";
  echo "<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step3\">Proceed to step 3</a>";

}


function step3() {
  global $dbh,$config;
  $query = "
     ALTER TABLE {$config['tbprefix']}Posts
     CHANGE B_Username B_Username VARCHAR(30) NOT NULL,
     ADD INDEX poster_ndx(B_Username)
  ";
  $dbh -> do_query($query);

  echo "<br />Step 3 has been completed.<br>";
  echo "Step 4 is the largest step in this upgrade.  Every post needs to be attached to a userid instead of just a username.  This step will go through that process.  If you have alot of users and posts this step may take an extended period of time.  If this step times out, it may be exceeding the max_execution_time in php.ini in which case you will need to either change this yourself or contact your host about how you can proceed.  At that point you can reload your browser to repeat this step.  When finished scroll to the bottom of your screen (if necessary) to proceed to the next step.";
  echo "<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step4\">Proceed to step 4</a>";
  
}

function step4() {
  global $dbh,$config;

  if (!$uid) {
     $uid = 0;
  }

  $query = "
     SELECT U_Username,U_Number
     FROM   {$config['tbprefix']}Users
     WHERE  U_Number > $uid
     ORDER BY U_Number ASC
  ";
  $sth = $dbh -> do_query($query);
  $i=0;
  $tot=0;
  while (list($username,$number) = $dbh -> fetch_array($sth)) {
     $username_q = addslashes($username);
     $query = "
        UPDATE {$config['tbprefix']}Posts
        SET    B_PosterId = $number
        WHERE  B_Username = '$username_q'
     ";
     $dbh -> do_query($query);
     $i++;
     echo "<b>.</b>";
     flush();
     if ($i==100) {
        $tot = $tot+$i;
	$i=0;
	echo "<br><b>On UID $number</b>";
	flush();
     }
  }
  echo "Step 4 has been completed.<br>";
  echo "Step 5 will now attach all anonymous posts and posts from users that have been deleted to the userid 1";
  echo "<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step5\">Proceed to step 5</a>";
}

function step5() {
  global $dbh,$config;
  $query = "
    DELETE FROM {$config['tbprefix']}Users WHERE U_Number='1'
  ";
  $dbh -> do_query($query);
  $query = "
    UPDATE {$config['tbprefix']}Users
    SET    U_Number='1'
    WHERE  U_Username='**DONOTDELETE**'
  ";
  $dbh -> do_query($query);
  $query = "
     UPDATE {$config['tbprefix']}Posts
     SET    B_PosterId = '1'
     WHERE  B_PosterId = ''
     OR     B_PosterId IS NULL
     OR     B_Reged = 'n'
  ";
  $dbh -> do_query($query);
  echo "Step 5 has been completed.<br>";
  echo "This next step will complete the following:<br>";
  echo "Adding session_id field to users table...<br />\n";
  echo "New column to specify special headers for each forum if desired...<br />\n";
  echo "Adding new column to the boards  table to store stylesheets...<br />\n";
  echo "Adding two fields to the boards table to link to last post from main page...<br />\n";
  echo "Adding a field to the Users table to allow for registration approval...<br />\n";
  echo "<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step6\">Proceed to step 6</a>";
}

function step6() {
  global $dbh,$config;
  $query = "
     ALTER TABLE {$config['tbprefix']}Users
     ADD U_SessionId varchar(64) NOT NULL DEFAULT '0',
     ADD INDEX sess_ndx(U_SessionId)
  ";
  $dbh -> do_query($query);

  $query = "
     ALTER TABLE {$config['tbprefix']}Boards
     ADD Bo_SpecialHeader INT(1)
  ";
  $dbh -> do_query($query);

  $query = "
     ALTER TABLE {$config['tbprefix']}Boards
     ADD   Bo_Stylesheet varchar(50)
  ";
  $dbh -> do_query($query);

   $query = "
      ALTER TABLE {$config['tbprefix']}Boards
      ADD Bo_LastNumber INT(9),
      ADD Bo_lastMain INT(9)
   ";
   $dbh -> do_query($query);

   $query = "
      ALTER TABLE {$config['tbprefix']}Users
      ADD U_Approved VARCHAR(3)
   ";
   $dbh -> do_query($query);
   $query = "
      UPDATE {$config['tbprefix']}Users
      SET    U_Approved = 'yes'
   ";
   $dbh -> do_query($query);

   echo "Step 6 has been completed.<br>";
   echo "The last step will find the last post on each forum so it can be linked to from the main page.<br />\n";
  echo "<br><br>";
  echo "<a href=\"altertable-5.5-6.0.php?step=step7\">Proceed to step 7</a>";
}

function step7() {
   global $dbh,$config;
   $query = "
      SELECT Bo_Keyword
      FROM   {$config['tbprefix']}Boards
   ";
   $sth = $dbh -> do_query($query);
   while(list($keyword) = $dbh -> fetch_array($sth)) {
      $keyword = addslashes($keyword);
      $query = "
         SELECT B_Main,B_Number
         FROM   {$config['tbprefix']}Posts
         WHERE B_Board = '$keyword'
         AND   B_Approved = 'yes'
         ORDER BY B_Number DESC
         LIMIT 1
      ";
      $sti = $dbh -> do_query($query);
      list($main,$number) = $dbh -> fetch_array($sti);
      $query = "
         UPDATE {$config['tbprefix']}Boards
         SET Bo_LastNumber='$number',
             Bo_LastMain = '$main'
         WHERE Bo_Keyword='$keyword'
      ";
      $dbh -> do_query($query);
   }

   echo "This upgrade is now complete.";
}


?>

