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

// Alterting the Users table
  echo "Alterting the Users table to add an index on Approved users, an admin email opt in/opt out selection, and an email format.<br>";
	
	// U_Approved is an INDEX (needs to be NOT NULL)
	$query = "
		ALTER TABLE {$config['tbprefix']}Users
		CHANGE U_Approved U_Approved VARCHAR(3) NOT NULL
	";
	$dbh -> do_query($query);		


  $query = "
    ALTER TABLE {$config['tbprefix']}Users
	 ADD U_LoginName VARCHAR(64) NOT NULL,
	 ADD INDEX Login_ndx (U_LoginName),
    ADD INDEX App_ndx (U_Approved),
	 ADD U_AdminEmails VARCHAR(3) DEFAULT 'On',
	 ADD U_EmailFormat VARCHAR(10) DEFAULT 'plaintext'
  ";
  $dbh -> do_query($query);

	$query = "
		UPDATE w3t_Users
		SET U_LoginName = U_Username
	";
	$dbh -> do_query($query);		

// Alterting the Last table
  echo "Alterting the {$config['tbprefix']}Last table to add an index<br>";
  $query = "
		ALTER TABLE {$config['tbprefix']}Last 
		ADD INDEX userlast_ndx (L_Username)
  ";
  $dbh -> do_query($query);

// Alterting the {$config['tbprefix']}Boards table
  echo "Alterting the {$config['tbprefix']}Boards table to add an index<br>";
  $query = "
		ALTER TABLE {$config['tbprefix']}Boards 
		ADD INDEX Cat_ndx (Bo_Cat)
  ";
  $dbh -> do_query($query);

// Alter the {$config['tbprefix']}Posts table
	echo "Altering the {$config['tbprefix']}Posts table to add a field to track the number of times a download has been downloaded<br>";
	$query = "
		ALTER TABLE {$config['tbprefix']}Posts
		ADD   B_FileCounter INT(9) DEFAULT '0'
	";
	$dbh -> do_query($query);

// Alter tables for username column consistency
	$query = "
		ALTER TABLE {$config['tbprefix']}AddressBook
		CHANGE Add_Owner Add_Owner VARCHAR(64) NOT NULL,
		CHANGE Add_Member Add_Member VARCHAR(64)
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Banned
		CHANGE B_Username B_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Favorites
		CHANGE F_Owner F_Owner VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Last
		CHANGE L_Username L_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Messages
		CHANGE M_Sender M_Sender VARCHAR(64),
		CHANGE M_Username M_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Moderators
		CHANGE Mod_Username Mod_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Online
		CHANGE O_Username O_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	// P_Name is an INDEX (needs to be NOT NULL)
	$query = "
		ALTER TABLE {$config['tbprefix']}PollData
		CHANGE P_IP P_IP VARCHAR(75),
		CHANGE P_Name P_Name VARCHAR(75) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Polls
		CHANGE P_Name P_Name VARCHAR(75) NOT NULL
	";
	$dbh -> do_query($query);

	// B_Icon is an INDEX (needs to be NOT NULL)
	$query = "
		ALTER TABLE {$config['tbprefix']}Posts
		CHANGE B_ParentUser B_ParentUser VARCHAR(64) NOT NULL,
		CHANGE B_Username B_Username VARCHAR(64) NOT NULL,
		CHANGE B_Icon B_Icon VARCHAR(30) NOT NULL,
		ADD INDEX Icon_ndx(B_Icon)
	";
	$dbh -> do_query($query);

	// R_What is an INDEX (needs to be NOT NULL)
	$query = "
		ALTER TABLE {$config['tbprefix']}Ratings
		CHANGE R_Rater R_Rater VARCHAR(64) NOT NULL,
		CHANGE R_What R_What VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Subscribe
		CHANGE S_Username S_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

	$query = "
		ALTER TABLE {$config['tbprefix']}Users
		CHANGE U_Username U_Username VARCHAR(64) NOT NULL
	";
	$dbh -> do_query($query);

// Table for graemlins
	$query = "
		CREATE TABLE {$config['tbprefix']}Graemlins (
		G_Number INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		G_Code VARCHAR(30),
		G_Smiley VARCHAR(10),
		G_Image VARCHAR(30)
		)
	";
	$dbh -> do_query($query);

	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_OOO\']','','ooo.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_MAD\']','','mad.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_COOL\']','','cool.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_SMILE\']',':)','smile.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_FROWN\']',':(','frown.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_BLUSH\']',':o','blush.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_CRAZY\']','','crazy.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_LAUGH\']',':D','laugh.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_SHOCKED\']','','shocked.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_SMIRK\']','','smirk.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_CONFUSED\']','','confused.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_GRIN\']','','grin.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_WINK\']',';)','wink.gif')
	";	
	$dbh -> do_query($query);
	$query = "
		INSERT INTO {$config['tbprefix']}Graemlins
		(G_Code,G_Smiley,G_Image)
	   VALUES ('\$ubbt_lang[\'ICON_TONGUE\']',':p','tongue.gif')
	";	
	$dbh -> do_query($query);

// Altering every user's signature/ converting markup in the database
	echo "Altering every users signature, converting markup in the database to reduce calls to do_markup on display.<br>";
	$query = "
		SELECT U_Number,U_Signature
		FROM   {$config['tbprefix']}Users
		WHERE  U_Signature <> ''
	";
	$sth = $dbh -> do_query($query);
	$html = new html;
	while (list($number,$sig) = $dbh -> fetch_array($sth)) {
		$sig = $html -> do_markup($sig);
		$sig_q = addslashes($sig);
		$query = "
			UPDATE {$config['tbprefix']}Users
			SET U_Signature='$sig_q'
			WHERE U_Number='$number'
		";
		$dbh -> do_query($query);
	};


// Disconnect
  echo "Done...<br>"; 

?>

