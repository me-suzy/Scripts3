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

   $config['dbtype'] = "mysql";

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

// Let's see if this has already been run
   $query = "
     SHOW TABLES 
   ";
   $sth = $dbh -> do_query($query);
   while (list ($check) = $dbh -> fetch_array($sth)) {
     if ($check == "{$config['tbprefix']}Posts") {
       $norun = 1;
     }
   }
   if ($norun) {
      echo "Createtable.php has already been run.  Your tables already exist.";
      exit;
   }

// The Board Table ##############################
echo "Creating the tables...<br>";

// Some versions of mysql don't like INDEXes to be created outside of
// the main table creation.
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ",UNIQUE indx1 (Bo_Keyword), INDEX indx2 (Bo_Number), INDEX Cat_ndx (Bo_Cat)";
  }

// Create the Board Table
  $query = " 
CREATE TABLE {$config['tbprefix']}Boards(
	Bo_Title $text,
	Bo_Description $text,
	Bo_Keyword $varchar(100) NOT NULL,
	Bo_Total $int9,
	Bo_Last $int11,
	Bo_HTML $varchar(3) DEFAULT 'On' NOT NULL,
	Bo_Markup $varchar(3) DEFAULT 'On' NOT NULL,
	Bo_Number $int9 DEFAULT '0' $autoincs PRIMARY KEY,
        Bo_Created $int11,
        Bo_Expire $int4,
        Bo_Approve $varchar(3) DEFAULT 'Off' NOT NULL,
        Bo_Moderated $varchar(3) DEFAULT 'no',
        Bo_Cat $int4 DEFAULT '1' NOT NULL,
	Bo_Read_Perm $varchar(250) DEFAULT '-3-4-',
	Bo_Write_Perm $varchar(250) DEFAULT '-3-4-',
        Bo_Threads $int9 unsigned DEFAULT '0',
        Bo_Sorter $int4,
        Bo_CatName $varchar(255),
        Bo_ThreadAge $int4,
        Bo_Poster $varchar(64),
        Bo_Reply_Perm $varchar(25) DEFAULT '-3-4-',
        Bo_Moderators $text,
        Bo_SpecialHeader INT(1),
        Bo_Stylesheet varchar(50),
        Bo_LastNumber INT(9),
        Bo_lastMain INT(9)
	$indexes
)";

  $dbh -> do_query($query);
  echo "Board table created...<br>";


// The Posts Table    ###################

  $index1 = "{$config['tbprefix']}Posts"."index1";
  $index2 = "{$config['tbprefix']}Posts"."index2";
  $index3 = "{$config['tbprefix']}Posts"."index3";
  $index4 = "{$config['tbprefix']}Posts"."index4";
  $index5 = "{$config['tbprefix']}Posts"."index5";
  $index6 = "{$config['tbprefix']}Posts"."index6";
  $index7 = "{$config['tbprefix']}Posts"."index7";
  $index8 = "{$config['tbprefix']}Posts"."index8";
  $index9 = "{$config['tbprefix']}Posts"."index9";
  $index10= "{$config['tbprefix']}Posts"."index10";
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX $index1 (B_Parent,B_Board), INDEX $index2 (B_Number, B_Board), INDEX $index3 (B_Main, B_Board), INDEX $index6 (B_Board), INDEX $index7 (B_Approved,B_Board), INDEX $index8 (B_Posted, B_Board), INDEX $index10 (B_Topic,B_Board), INDEX ID_ndx(B_PosterId), INDEX poster_ndx(B_Username)
";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}Posts (
      B_Board $varchar(100) NOT NULL,
      B_Number $int11 $autoincs PRIMARY KEY,
      B_Parent $int11 NOT NULL,
      B_Main $int11 NOT NULL,
      B_Posted $int11 NOT NULL,
      B_Last_Post $int11 DEFAULT '0' NOT NULL,
      B_Username $varchar(64) NOT NULL,
      B_IP $varchar(60),
      B_Subject $text,
      B_Body $text,
      B_Mail $int1,
      B_File $varchar(100),
      B_Kept $varchar(1) NOT NULL,
      B_Status $varchar(1) NOT NULL,
      B_Approved $varchar(3) DEFAULT 'yes' NOT NULL,
      B_Picture $varchar(100),
      B_Icon $varchar(30),
      B_Color $varchar(10),
      B_Reged $varchar(1) DEFAULT 'y',
      B_UTitle $varchar(50),
      B_Counter $int9 DEFAULT '0',
      B_Sticky $int11,
      B_Replies $int4 DEFAULT '0',
      B_Poll $varchar(200),
      B_ParentUser $varchar(64),
      B_UStatus $varchar(1),
      B_Topic $int1 DEFAULT '0' NOT NULL,
      B_Convert $varchar(10) default 'markup',
      B_Signature $text,
      B_LastEdit $int9,
      B_LastEditBy $varchar(64),
      B_PosterId INT(9) UNSIGNED NOT NULL,
      B_Rating varchar(5) DEFAULT '0',
      B_Rates int(4) DEFAULT '0',
      B_RealRating INT(1),
		B_FileCounter INT(9) DEFAULT '0'
      $indexes
  )
  ";   
    $dbh -> do_query($query);

  echo "Posts table created...<br>";

// The polls table ################################
  echo "Adding a master poll table...<br>";
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX pollndx (P_Name)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}Polls (
      P_Name $varchar(75) NOT NULL,
      P_Title $varchar(200),
      P_Option $varchar(200),
      P_Number $int4
      $indexes
    )
  ";
    $dbh -> do_query($query);

  echo "Adding the poll results table...<br>";
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX pollndx2(P_Name)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}PollData (
      P_Name $varchar(75) NOT NULL,
      P_Number $int4,
      P_IP $varchar(75)
      $indexes
    )
  ";
    $dbh -> do_query($query);

// Last viewed table ##############################
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX Last_indx (L_Board,L_Username), INDEX userlast_ndx (L_Username)";
  }

  $query = "
    CREATE TABLE {$config['tbprefix']}Last(
      L_Board $varchar(100) NOT NULL,
      L_Username $varchar(64) NOT NULL,
      L_Last $int11
      $indexes
    )
  ";
    $dbh -> do_query($query);

  echo "Last viewed table created...<br>"; 

// The Category Table ###################

  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ",INDEX indx1 (Cat_Title), INDEX indx2 (Cat_Number)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}Category(
      Cat_Title $varchar(120) DEFAULT 'General Discussion' NOT NULL,
      Cat_Number $int9 DEFAULT '0' NOT NULL,
      Cat_Description $text
      $indexes
    )
  ";
    $dbh -> do_query($query);
  echo "Category table created...<br>";

  $query = "
    INSERT INTO {$config['tbprefix']}Category VALUES ('General Discussion',1,'Forums for general discussion')
  ";
  $dbh -> do_query ($query);


// The User Table ########################
$indexes = "";
if ($config['dbtype'] == "mysql") {
  $indexes = ",INDEX indx1 (U_Username, U_Password), INDEX indx2 (U_Status), INDEX indx3 (U_Number), INDEX sess_ndx(U_SessionId), INDEX App_ndx(U_Approved), INDEX Login_ndx (U_LoginName)";
}

// Create the User Table
  $query = "
CREATE TABLE {$config['tbprefix']}Users(
	U_LoginName $varchar(64) NOT NULL,
	U_Username $varchar(64) NOT NULL,
	U_Password $varchar(32) NOT NULL,
	U_Email $varchar(50),
	U_Fakeemail $varchar(50),
        U_Name $varchar(100),
	U_Totalposts $int9,
	U_Laston $int11,
	U_Signature $text,
	U_Homepage $varchar(150),
	U_Occupation $varchar(150),
	U_Hobbies $varchar(200),
	U_Location $varchar(200),
	U_Bio $text,
	U_Status $varchar(15) DEFAULT 'User' NOT NULL,
        U_Sort $int4,
        U_Display $varchar(10) NOT NULL,
        U_View $varchar(10) NOT NULL,
        U_PostsPer $int11,
        U_Number $int9 $autoincs PRIMARY KEY,
        U_EReplies $varchar(3) NOT NULL,
        U_Notify $varchar(3) NOT NULL,
        U_TextCols $varchar(3),
        U_TextRows $varchar(3),
        U_Extra1 $varchar(200),
        U_Extra2 $varchar(200),
        U_Extra3 $varchar(200),
        U_Extra4 $varchar(200),
        U_Extra5 $varchar(200),
        U_Post_Format $varchar(5) NOT NULL,
        U_Registered $int11,
        U_Preview $varchar(5),
        U_Picture $varchar(150),
        U_PictureView $varchar(3),
	U_Visible $varchar(3) DEFAULT 'yes',
        U_PicturePosts $varchar(3),
	U_AcceptPriv $varchar(3) DEFAULT 'yes',
    	U_RegEmail char(50),
    	U_RegIP char(15),
	U_Groups $varchar(250) DEFAULT '-1-',
        U_Language $varchar(20),
        U_Title $varchar(100),
        U_FlatPosts $varchar(2),
        U_TempPass $varchar(32),
        U_Color $varchar(15),
        U_TempRead $text,
        U_StyleSheet $varchar(50),
        U_TimeOffset $varchar(10),
        U_Privates $int4 DEFAULT '0',
        U_FrontPage $varchar(20),
        U_ActiveThread $int4,
        U_StartPage $varchar(2) DEFAULT 'cp',
        U_Favorites $varchar(250) DEFAULT '-',
        U_ShowSigs $varchar(3),
        U_OnlineFormat $varchar(3),
        U_Rating $varchar(5) DEFAULT '0',
        U_Rates $int4 DEFAULT '0',
        U_RealRating $int1,
        U_PicWidth $int4,
        U_PicHeight $int4,
        U_SessionId varchar(64) NOT NULL DEFAULT '0',
        U_Approved VARCHAR(3) NOT NULL,
	U_Palprofile $int11,
	U_AdminEmails VARCHAR(3) DEFAULT 'On',
	U_EmailFormat VARCHAR(10) DEFAULT 'plaintext'
	$indexes
)";
  $dbh -> do_query ($query);
  echo "Users table created...<br>";

// BoModerators table #############################
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ",INDEX Modindx1 (Mod_Username,Mod_Board)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}Moderators (
	Mod_Username $varchar(64) NOT NULL,
	Mod_Board $varchar(100) NOT NULL
	$indexes
    )
  ";
  $dbh -> do_query($query);
  echo "Moderator table created...<br>";

// Private Message Table ##########################

// Create the Private Messages Table
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ",INDEX Messindx1 (M_Username, M_Status), INDEX sender_ndx(M_Sender)";
  }
  $query = "
CREATE TABLE {$config['tbprefix']}Messages(
	M_Username $varchar(64) NOT NULL,
	M_Status $varchar(1) DEFAULT 'N' NOT NULL,
	M_Subject $varchar(60),
	M_Sender $varchar(64) NOT NULL,
	M_Message $text,
	M_Sent $int11,
	M_Number $int9 $autoincs PRIMARY KEY
	$indexes
)";

  $dbh -> do_query ($query);
  echo "Messages table created...<br>";


// The Banned Table ###########################

  $query = "
CREATE TABLE {$config['tbprefix']}Banned(
	B_Username $varchar(64) NOT NULL,
	B_Hostname $varchar(60) NOT NULL,
	B_Reason $text
)";

  $dbh -> do_query ($query);
  echo "Banned table created...<br>";


// The Groups Table ##################
  $query = "
    CREATE TABLE {$config['tbprefix']}Groups (
      G_Name $varchar(250),
      G_Id $int4 $autoincs PRIMARY KEY
    )
  ";
  $dbh -> do_query ($query);
  echo "Groups table created";

  $extra;
  $cols = "(G_Name)";
  if ($config['dbtype'] == 'Oracle') {
    $extra = ",Group_seq.nextval";
    $cols = "(G_Name,G_Id)";
  }
  elseif ($config['dbtype'] == 'postgres') {
    $extra = ",nextval('Group_seq')";
    $cols = "(G_Name,G_Id)";
  }
  $query = "
    INSERT INTO {$config['tbprefix']}Groups $cols VALUES ('Administrators'$extra)
  ";
  $dbh -> do_query ($query);
  $query = "
    INSERT INTO {$config['tbprefix']}Groups $cols VALUES ('Moderators'$extra)
  ";
  $dbh -> do_query ($query);
  $query = "
    INSERT INTO {$config['tbprefix']}Groups $cols VALUES ('Users'$extra)
  ";
  $dbh -> do_query ($query);
  $query = "
    INSERT INTO {$config['tbprefix']}Groups $cols VALUES ('Guests'$extra)
  ";
  $dbh -> do_query ($query);
  echo "Basic groups created...<br>"; 

// The Online Table ##################
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ",UNIQUE Oindx1 (O_Username), INDEX Oindx2 (O_Last), INDEX type_index(O_Type)";
  }

  $query = "
  CREATE TABLE {$config['tbprefix']}Online(
    O_Username $varchar(64) NOT NULL,
    O_Last $int9 DEFAULT '0' NOT NULL,
    O_What $varchar(64),
    O_Extra $varchar(200),
    O_Read $varchar(255),
    O_Type $varchar(1) NOT NULL
    $indexes
  )
  ";
  $dbh -> do_query ($query);
  echo "Online table created...<br>";


// The Subscribe Table ##################
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX Subindx1 (S_Board,S_Username)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}Subscribe(
	S_Username $varchar(64) NOT NULL,
	S_Board $varchar(100) NOT NULL,
        S_Last $int9
        $indexes
  )";

  $dbh -> do_query ($query);
  echo "Subscribe table created...<br>";

// -----------------------
// Create the address book
  echo "Creating table for the address book...<br>";
  $indexes = "";
  if ($config['dbtype'] == "mysql") {
    $indexes = ", INDEX address_ndx1 (Add_Owner)";
  }
  $query = "
    CREATE TABLE {$config['tbprefix']}AddressBook (
      Add_Owner $varchar(64) NOT NULL,
      Add_Member $varchar(64)
      $indexes
    )
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
        F_Owner $varchar(64) NOT NULL,
        F_LastPost $int4,
        F_Type $varchar(1)
        $indexes 
        )
   ";
   $dbh -> do_query($query);

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

  $query = "
    CREATE TABLE {$config['tbprefix']}Ratings (
      R_What varchar(64) NOT NULL,
      R_Rater varchar(64) NOT NULL,
      R_Rating int(1) DEFAULT '0',
      R_Type varchar(1) NOT NULL,
      INDEX r_indx1(R_What,R_Rater,R_Type)
    )
  ";
  $dbh -> do_query($query);

  echo "Inserting a placeholder user for anonymous posts...<br />\n";
  $query = "
    INSERT INTO {$config['tbprefix']}Users
    (U_Username,U_Groups)
    VALUES
    ('**DONOTDELETE**','-4-')
  ";
  $dbh -> do_query($query);
  $query = "
     UPDATE {$config['tbprefix']}Users
     SET U_Number='1'
     WHERE U_Username='**DONOTDELETE**'
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
   	

echo " 
<br>
All tables created.
<br>
<br>
You will want to remove all altertables, createtable.php and install.php from your webserver. 
<br>
<br>

Visit <a href=\"newuser.php\">the newuser script</a> to create your main admin user.  Be sure to choose this name and email carefully as this will be your permanent main administrator.  After you login go into the admin section and edit the rest of your configuration settings and theme settings to suit your needs.
<br>
<br>
";

?>
