<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	include(realpath("../conf.php"));

	include(realpath("templates/dev_top.php"));
	require_once(realpath("includes/php/security.php"));
	
	// Make sure that this user is logged in
	$i = @IsLoggedIn();
	
	if($i < 2 && $isSetup == "yes" && !in_array("view_config", $publisherAccess))
		header("location: index.php");

	if(@$_GET["post"] == "true")
	{
		// Save the settings back into the config file and also
		// build and fill the MySQL database
		
		$config_output = "<?php\r\n\r\n";
		$config_output .= "\$isSetup = 'yes'; \r\n";
		$config_output .= "\$appName = 'SiteWorks Professional'; \r\n";
		$config_output .= "\$appVersion = '5.0'; \r\n";
		
		// Replace double quotes in $_POST variables with single ones
		foreach($_POST as $k => $v)
			$_POST[$k] = str_replace("\\\"", "'", $v);
		
		if($_POST["siteName"] == "")
			$err .= "<li>You must enter your site's name</li>";
		else
			$config_output .= "\$siteName = \"{$_POST["siteName"]}\"; \r\n";
		
		if($_POST["siteURL"] == "")
			$err .= "<li>You must enter your site's URL</li>";
		else
			$config_output .= "\$siteURL = \"{$_POST["siteURL"]}\"; \r\n";

		if($_POST["siteLogo"] == "")
			$err .= "<li>You must enter your site's logo</li>";
		else
			$config_output .= "\$siteLogo = \"{$_POST["siteLogo"]}\"; \r\n";
			
		$config_output .= "\$template = \"{$_POST['template']}\"; \r\n";
		
		if($_POST["ezinePopup"] == "")
			$config_output .= "\$ezinePopup = false; \r\n";
		else
			$config_output .= "\$ezinePopup = true; \r\n";
		
		if($_POST["showNumUsers"] == "")
			$config_output .= "\$showNumUsers = false; \r\n";
		else
			$config_output .= "\$showNumUsers = true; \r\n";
		
		if($_POST["showFeaturedBook"] == "")
			$config_output .= "\$showFeaturedBook = false; \r\n";
		else
			$config_output .= "\$showFeaturedBook = true; \r\n";

		if($_POST["showAuthorImages"] == "")
			$config_output .= "\$showAuthorImages = false; \r\n";
		else
			$config_output .= "\$showAuthorImages = true; \r\n";

		if($_POST["showXML"] == "")
			$config_output .= "\$showXML = false; \r\n";
		else
			$config_output .= "\$showXML = true; \r\n";

		if($_POST["showHandy"] == "")
			$err .= "<li>You must enter a name for the Handy Tip</li>";
		else
			$config_output .= "\$showHandy = \"" . stripslashes($_POST['showHandy']) . "\"; \r\n";
			
		if($_POST["showMy2c"] == "")
			$err .= "<li>You must enter a name for My 2c Worth</li>";
		else
			$config_output .= "\$showMy2c = \"" . stripslashes($_POST['showMy2c']) . "\"; \r\n";

		if($_POST["siteClosed"] == "")
			$config_output .= "\$siteClosed = false; \r\n";
		else
			$config_output .= "\$siteClosed = true; \r\n";

		$_POST["siteCloseMessage"] = str_replace("\r\n", "\\r\\n", str_replace("\\\"", "'", $_POST["siteCloseMessage"]));
		$config_output .= "\$siteCloseMessage = \"{$_POST['siteCloseMessage']}\"; \r\n";

		if($_POST["siteComments"] == "")
			$config_output .= "\$siteComments = false; \r\n";
		else
			$config_output .= "\$siteComments = true; \r\n";

		if($_POST["siteCommentsApprove"] == "")
			$config_output .= "\$siteCommentsApprove = false; \r\n";
		else
			$config_output .= "\$siteCommentsApprove = true; \r\n";

		if($_POST["siteCommentsShow"] == "")
			$err .= "<li>You must enter how many comments to show</li>";
		else
			$config_output .= "\$siteCommentsShow = \"{$_POST['siteCommentsShow']}\"; \r\n";

		if($_POST["siteKeywords"] == "")
			$err .= "<li>You must enter your site's keywords</li>";
		else
			$config_output .= "\$siteKeywords = \"{$_POST["siteKeywords"]}\"; \r\n";
			
		if($_POST["siteDescription"] == "")
			$err .= "<li>You must enter your site's description</li>";
		else
			$config_output .= "\$siteDescription = \"{$_POST["siteDescription"]}\"; \r\n";

		if($_POST["adminName"] == "")
			$err .= "<li>You must enter the name of your site's admin contact</li>";
		else
			$config_output .= "\$adminName = \"{$_POST["adminName"]}\"; \r\n";

		if($_POST["adminEmail"] == "" || !is_numeric(strpos($_POST["adminEmail"], "@")) || !is_numeric(strpos($_POST["adminEmail"], ".")))
			$err .= "<li>You must enter a valid email for your site's admin contact</li>";
		else
			$config_output .= "\$adminEmail = \"{$_POST["adminEmail"]}\"; \r\n";

		if($_POST["dbServer"] == "")
			$err .= "<li>You must enter the IP address/host name of your MySQL server</li>";
		else
			$config_output .= "\$dbServer = \"{$_POST["dbServer"]}\"; \r\n";

		if($_POST["dbUser"] == "")
			$err .= "<li>You must enter your MySQL username</li>";
		else
			$config_output .= "\$dbUser = \"{$_POST["dbUser"]}\"; \r\n";

		$config_output .= "\$dbPass = \"{$_POST["dbPass"]}\"; \r\n";

		if($_POST["dbDatabase"] == "")
			$err .= "<li>You must enter your MySQL database name</li>";
		else
			$config_output .= "\$dbDatabase = \"{$_POST["dbDatabase"]}\"; \r\n";
		
		if($_POST["ibMaxFileSize"] == "")
			$err .= "<li>You must enter the maximum allowable file size of uploaded images</li>";
		else
			$config_output .= "\$ibMaxFileSize = \"{$_POST["ibMaxFileSize"]}\"; \r\n";
		
		$arrImgTypes = @explode("|", @$_POST["imgTypeH"]);
		
		if(@$_POST["imgTypeH"] != "")
		{
			$config_output .= "\$ibTypes = array(";
			for($i = 0; $i < sizeof($arrImgTypes); $i++)
			{
				if($arrImgTypes[$i] != "")
				{
					if($i < sizeof($arrImgTypes)-2)
						$config_output .= "\"{$arrImgTypes[$i]}\", ";
					else
						$config_output .= "\"{$arrImgTypes[$i]}\"";
				}
			}
			$config_output .= ");\r\n";
		}
		else
		{
			$err .= "<li>You must enter at least one type of image that can be accepted</li>";
		}
		
		if($_POST["recsPerPage"] == "")
			$err .= "<li>You must enter the number of records to show per page in the admin area</li>";
		else
			$config_output .= "\$recsPerPage = {$_POST["recsPerPage"]}; \r\n";
			
		if($_POST["authorsPerPage"] == "")
			$err .= "<li>You must enter the number of authors to show per page on your site</li>";
		else
			$config_output .= "\$authorsPerPage = {$_POST["authorsPerPage"]}; \r\n";

		if($_POST["newsPerPage"] == "")
			$err .= "<li>You must enter the number of news posts to show per page on your site</li>";
		else
			$config_output .= "\$newsPerPage = {$_POST["newsPerPage"]}; \r\n";

		if($_POST["articlesPerPage"] == "")
			$err .= "<li>You must enter the number of articles to show per page on your site</li>";
		else
			$config_output .= "\$articlesPerPage = {$_POST["articlesPerPage"]}; \r\n";
			
		if($_POST["forumTypeList"] == "vbulletin")
			$config_output .= "\$isVBulletinForum = true; \r\n";
		else
			$config_output .= "\$isVBulletinForum = false; \r\n";

		if($_POST["forumTypeList"] == "phpbb")
			$config_output .= "\$isPHPBBForum = true; \r\n";
		else
			$config_output .= "\$isPHPBBForum = false; \r\n";
			
		if($_POST["forumTypeList"] != "")
		{
			if($_POST["forumDBServer"] != "" && $_POST["forumDBServer"] != "[None]")
				$config_output .= "\$forumDBServer = \"{$_POST["forumDBServer"]}\"; \r\n";
			else
				$err .= "<li>You must enter the database server for your forums</li>";
			
			if($_POST["forumDBUser"] != "" && $_POST["forumDBUser"] != "[None]")
				$config_output .= "\$forumDBUser = \"{$_POST["forumDBUser"]}\"; \r\n";
			else
				$err .= "<li>You must enter the username for your forum database</li>";
			
			$config_output .= "\$forumDBPass = \"{$_POST["forumDBPass"]}\"; \r\n";
			
			if($_POST["forumDBName"] != "" && $_POST["forumDBName"] != "[None]")
				$config_output .= "\$forumDBName = \"{$_POST["forumDBName"]}\"; \r\n";
			else
				$err .= "<li>You must enter the name of your forum database</li>";
			
			if($_POST["forumDBPath"] != "" && $_POST["forumDBPath"] != "[None]")
				$config_output .= "\$forumDBPath = \"{$_POST["forumDBPath"]}\"; \r\n";
			else
				$err .= "<li>You must enter the full path to your forums, such as http://www.mysite.com/forum</li>";

			if($_POST["forumDBPrefix"] != "[None]")
				$config_output .= "\$forumDBPrefix = \"{$_POST["forumDBPrefix"]}\"; \r\n";

		}
		else
		{
			$config_output .= "\$forumDBServer = \"[None]\"; \r\n";
			$config_output .= "\$forumDBUser = \"[None]\"; \r\n";
			$config_output .= "\$forumDBPass = \"[None]\"; \r\n";
			$config_output .= "\$forumDBName = \"[None]\"; \r\n";
			$config_output .= "\$forumDBPath = \"[None]\"; \r\n";
			$config_output .= "\$forumDBPrefix = \"[None]\"; \r\n";
		}

		if($_POST["authorEmail"] == "")
		{
			$err .= "<li>You must enter the email message that new authors will receive</li>";
		}
		else
		{
			$_POST["authorEmail"] = str_replace("\r\n", "\\r\\n", str_replace("\\\"", "'", $_POST["authorEmail"]));
			$config_output .= "\$authorEmail = \"{$_POST["authorEmail"]}\"; \r\n";
		}
		
		$config_output .= "\r\n?>";
		
		$oldSetup = $_POST["old_setup"];
		
		if($err != "")
		{
			// Output the errors
			?>
				<p style="margin-left:15; margin-right:10">
				<span class="BodyText">
					<span class="BodyHeading">Invalid/Incomplete Details</span><br><br>
					Some of the details that you have entered were either invalid or incomplete. Please review
					the errors below and then click on the "<< Go Back" link to go back and fix these errors.
					<ul><?php echo $err; ?></ul>
					<p style="margin-left:15; margin-right:10">
					<a href="javascript:history.go(-1)"><< Go Back</a>
				</p></span>
				
			<?php
		}
		else
		{
			// Update the config file
			$fp = @fopen("config.php", "w");
			
			if($fp)
			{
				fputs($fp, $config_output);
				fclose($fp);
				
				if($oldSetup == "no")
				{
					// Let the user build the database
				?>
					<p style="margin-left:15; margin-right:10">
					<span class="BodyText">
						<span class="BodyHeading">Config File Updated!</span><br><br>
						Your SiteWorks Professional configuration files has been updated successfully.
						Please click on the link below to start building your MySQL database using the
						details that you specified on the previous page. After this, you will be able
						to login and start managing your SiteWorks Professional web site.
						<p style="margin-left:15; margin-right:10">
						<a href="setup.php?post=db">Build Database >></a>
					</p></span>
					
				<?php
				}
				else
				{
					// The user is just updating the config file. We don't need to build the database
				?>
					<p style="margin-left:15; margin-right:10">
					<span class="BodyText">
						<span class="BodyHeading">Config File Updated!</span><br><br>
						Your SiteWorks Professional configuration files has been updated successfully.
						Please click on the link below to continue.
						<p style="margin-left:15; margin-right:10">
						<a href="index.php">Continue >></a>
					</p></span>
					
				<?php
				}
			}
			else
			{
			?>
				<p style="margin-left:15; margin-right:10">
				<span class="BodyText">
					<span class="BodyHeading">Couldn't Write To Config File</span><br><br>
					An error occured while trying to write to <?php echo $admindir; ?>/config.php. Please make sure this file
					has the appropriate write permissions set (CHMOD 755).
					<p style="margin-left:15; margin-right:10">
					<a href="javascript:document.location.reload()">Try Again</a>
				</p></span>
				
			<?php
			}
		}
	}
	else if(@$_GET["post"] == "db")
	{
		// Use the details from the config.php file to connect to the MySQL database and
		// create the tables, etc
		
        // Connect to the database
        $svrConn = @mysql_connect($dbServer, $dbUser, $dbPass);
        $dbConn = @mysql_select_db($dbDatabase, $svrConn);
        
        if(!$svrConn || !$dbConn)
        {
        ?>
			<p style="margin-left:15; margin-right:10">
			<span class="BodyText">
				<span class="BodyHeading">Couldn't Connect To Database</span><br><br>
				An error occured whilst trying to connect to your MySQL database.
				Please click on the link below to try again.
				<p style="margin-left:15; margin-right:10">
				<a href="javascript:document.location.reload()">Try Again</a>
			</p></span>
			
        <?php
        }
        else
        {
		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_AdminLogins (
		      pk_alId int(11) NOT NULL auto_increment,
		      alUserName varchar(20) NOT NULL default '',
		      alPass varchar(20) NOT NULL default '',
		      alEmail varchar(250) NOT NULL default '',
		      alFName varchar(30) NOT NULL default '',
		      alLName varchar(30) NOT NULL default '',
		      alBio text,
		      alPic blob,
		      alDateJoined timestamp(14) NOT NULL,
			  alPermissions TEXT NOT NULL,
		      PRIMARY KEY  (pk_alId),
		      UNIQUE KEY ID (pk_alId)
		    ) TYPE=MyISAM;") ;
		    
		    // Open the author image and use it
		    $imgHandle = @fopen("unknownauthor.gif", "r");
		    $imgContent = @fread($imgHandle, 10000);
		    $imgFile = @addslashes($imgContent);

		    @mysql_query("INSERT INTO tbl_AdminLogins(pk_alId, alUserName, alPass, alEmail, alFName, alLName, alBio, alPic, alPermissions)
		      VALUES(0, 'administrator', 'password', 'demo@account.com',
		      'Demo', 'Account', '', '$imgFile', 'sitedetails,users,articles,topics,news,personalContent,books,images,affiliates,newsletter,polls,comments,old_setup,about_us,view_privacy,view_config,contact_us,view_users,add_users,edit_users,delete_users,view_articles,add_articles,edit_articles,delete_articles,activate_articles,view_topics,add_topics,edit_topics,delete_topics,view_news,add_news,edit_news,delete_news,edit_2cents,edit_tips,view_books,add_books,edit_books,delete_books,view_images,add_images,delete_images,view_affiliates,add_affiliates,edit_affiliates,delete_affiliates,view_newsletter,delete_newsletter,export_newsletter,view_polls,add_polls,edit_polls,delete_polls,poll_results,view_comments,approve_comments,delete_comments')") or die("Couldn't create admin account");

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_AdminSessions (
		      pk_asId int(11) NOT NULL auto_increment,
		      asSessId varchar(50) NOT NULL default '',
		      asUName varchar(20) NOT NULL default '',
		      asPass varchar(20) NOT NULL default '',
		      asFName varchar(30) NOT NULL default '',
		      asLName varchar(30) NOT NULL default '',
		      asSec int(11) NOT NULL default '0',
		      PRIMARY KEY  (pk_asId),
		      UNIQUE KEY ID (pk_asId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Affiliates (
		      pk_aId int(11) NOT NULL auto_increment,
		      aName varchar(50) default NULL,
		      aURL varchar(250) default NULL,
		      PRIMARY KEY  (pk_aId),
		      UNIQUE KEY id (pk_aId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_ArticlePages (
		      pk_apId int(11) NOT NULL auto_increment,
		      apArticleId int(11) NOT NULL default '0',
		      apTitle varchar(100) NOT NULL default '',
		      apPage int(11) NOT NULL default '0',
		      apContent text NOT NULL,
		      PRIMARY KEY  (pk_apId),
		      UNIQUE KEY ID (pk_apId)
		    ) TYPE=MyISAM;") ;

			@mysql_query("CREATE TABLE IF NOT EXISTS tbl_Comments (
			  pk_cId INT(14) NOT NULL auto_increment,
			  cId INT(14) NOT NULL,
			  cType VARCHAR(100) NOT NULL,
			  cName VARCHAR(100) NOT NULL,
			  cDateCreate TIMESTAMP NOT NULL,
			  cComment TEXT NOT NULL,
			  cEmail VARCHAR(255) NOT NULL,
			  cVisible INT(4) NOT NULL, 
			  PRIMARY KEY (pk_cId),
			  UNIQUE KEY ID (pk_cId)
			) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Articles (
		      pk_aId int(11) NOT NULL auto_increment,
		      aTitle varchar(100) NOT NULL default '',
		      aDocType smallint(6) NOT NULL default '0',
		      aAuthorId smallint(6) NOT NULL default '0',
		      aSummary text NOT NULL,
		      aTopicIds varchar(50) NOT NULL default '',
		      aArticleIds varchar(50) default '',
		      aBookIds varchar(50) default '',
		      aForumLink varchar(255) default '',
		      aLink1 varchar(255) default '',
		      aLink2 varchar(255) default '',
		      aLink3 varchar(255) default '',
		      aActive tinyint(4) default '1',
		      aStatus tinyint(4) default '0',
		      aRatingTotal int(11) default '0',
		      aNumRatings int(11) default '0',
		      aNumVotes int(11) default '0',
		      aSupportFile int(11) default '0',
		      aDateCreated bigint(20) default NULL,
		      aNumViews int(11) default '0',
		      PRIMARY KEY  (pk_aId),
		      UNIQUE KEY ID (pk_aId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Books (
		      pk_bId int(11) NOT NULL auto_increment,
		      bTitle varchar(100) NOT NULL default '',
		      bURL varchar(250) NOT NULL default '',
		      bTopicIds varchar(100) NOT NULL default '',
		      bPic blob NOT NULL,
		      PRIMARY KEY  (pk_bId),
		      UNIQUE KEY ID (pk_bId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Images (
		      pk_iId int(11) NOT NULL auto_increment,
		      iName varchar(50) NOT NULL default '',
		      iType varchar(20) NOT NULL default '',
		      iBlob longblob NOT NULL,
		      iSize int(11) NOT NULL default '0',
		      PRIMARY KEY  (pk_iId),
		      UNIQUE KEY id (pk_iId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Logs (
		      pk_lId int(11) NOT NULL auto_increment,
		      lData text NOT NULL,
		      PRIMARY KEY  (pk_lId),
		      UNIQUE KEY id (pk_lId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_News (
		      pk_dnId int(11) NOT NULL auto_increment,
		      nAuthorId int(11) default NULL,
		      nTitle varchar(100) default NULL,
		      nContent blob,
		      nDateAdded varchar(20) default NULL,
		      nSource varchar(50) default NULL,
		      nURL varchar(250) default NULL,
		      PRIMARY KEY  (pk_dnId),
		      UNIQUE KEY id (pk_dnId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Personal (
		      pk_nId int(11) NOT NULL auto_increment,
		      nValue text NOT NULL,
		      nType smallint(4) NOT NULL default '0',
		      PRIMARY KEY  (pk_nId),
		      UNIQUE KEY ID (pk_nId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_RatingIps (
		      pk_riId int(11) NOT NULL auto_increment,
		      riIP varchar(255) NOT NULL default '',
		      riArticleId int(11) NOT NULL default '0',
		      PRIMARY KEY  (pk_riId),
		      UNIQUE KEY id (pk_riId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_TempZips (
		      pk_zId int(11) NOT NULL auto_increment,
		      zBlob longblob NOT NULL,
		      PRIMARY KEY  (pk_zId),
		      UNIQUE KEY ID (pk_zId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Topics (
		      pk_tId int(11) NOT NULL auto_increment,
		      tName varchar(50) default NULL,
		      PRIMARY KEY  (pk_tId),
		      UNIQUE KEY id (pk_tId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Users (
		      pk_uId int(11) NOT NULL auto_increment,
		      uEmail varchar(250) NOT NULL default '',
		      uPass varchar(20) NOT NULL default '',
		      uFName varchar(30) default '',
		      uLName varchar(30) default '',
		      uSex smallint(6) default '0',
		      uAge int(11) default '0',
		      uCountry varchar(30) default '',
		      uSalary smallint(6) default '0',
		      uOccupation smallint(6) default '0',
		      uTechRating smallint(6) default '0',
		      uNumPurchLastYr int(11) default '0',
		      uPurchCostLastYr int(11) default '0',
		      uNumPurchNextYr int(11) default '0',
		      uInterests varchar(20) default '',
		      uYrsOnline smallint(6) default '0',
		      uHrsPwOnline int(11) default '0',
		      uDateJoined timestamp(14) NOT NULL,
		      PRIMARY KEY  (pk_uId),
		      UNIQUE KEY ID (pk_uId)
		    ) TYPE=MyISAM;") ;

		    // Added for version 3.0
		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_SiteDetails (
		      pk_sdId int(11) NOT NULL auto_increment,
		      sdAbout text NOT NULL default '',
		      sdContact text NOT NULL default '',
		      sdPrivacy text NOT NULL default '',
		      PRIMARY KEY  (pk_sdId),
		      UNIQUE KEY ID (pk_sdId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("INSERT INTO tbl_SiteDetails
		      VALUES(0, '', '', '')") or die("Couldn't set site details");

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Newsletter (
		      pk_nlId int(11) NOT NULL auto_increment,
		      nlEmail varchar(250),
		      nlDateJoined timestamp,
		      PRIMARY KEY  (pk_nlId),
		      UNIQUE KEY ID (pk_nlId)
		    ) TYPE=MyISAM;") ;

		    @mysql_query("CREATE TABLE IF NOT EXISTS tbl_Polls (
		      pk_pId int(11) NOT NULL auto_increment,
		      pQuestion varchar(250),
		      pAnswer1 varchar(50),
		      pAnswer2 varchar(50),
		      pAnswer3 varchar(50),
		      pAnswer4 varchar(50),
		      pAnswer5 varchar(50),
		      pAnswer6 varchar(50),
		      pAnswer7 varchar(50),
		      pAnswer8 varchar(50),
		      pAnswer9 varchar(50),
		      pAnswer10 varchar(50),
		      pType tinyint,
		      pDateCreated timestamp,
		      pVisible bit,
		      PRIMARY KEY  (pk_pId),
		      UNIQUE KEY ID (pk_pId)
		    ) TYPE=MyISAM;") ;
		    
			@mysql_query("CREATE TABLE IF NOT EXISTS tbl_PollAnswers (
			  pk_paId int(11) NOT NULL auto_increment, 
			  paPollId int not null, 
			  paAnswer int(11) not null, 
			  paVisitorIP varchar(15) not null, 
			  primary key(pk_paId), 
			  unique Id(pk_paId) 
			) TYPE=MyISAM;") ;
			
			@mysql_query("CREATE TABLE IF NOT EXISTS tbl_UsersOnline (
			  id int(11) NOT NULL auto_increment,
			  userIP varchar(20) NOT NULL default '',
			  dateAdded timestamp(14) NOT NULL,
			  PRIMARY KEY  (id),
			  UNIQUE KEY id (id)
			) TYPE=MyISAM;") ;
			
			if(mysql_error() == "")
			{
			?>
			<p style="margin-left:15; margin-right:10">
			<span class="BodyText">
				<span class="BodyHeading">Database Created Successfully!</span><br><br>
				Your MySQL database has been setup successfully. Please click on the link
				below to login.
				<p style="margin-left:15; margin-right:10">
				<a href="index.php">Login >></a>
			</p></span>
			
			<?php
			}
			else
			{
			?>
				<p style="margin-left:15; margin-right:10">
				<span class="BodyText">
					<span class="BodyHeading">A Database Error Occured</span><br><br>
					An error occured whilst trying to create your MySQL database.
					The error was "<i><?php echo mysql_error(); ?></i>". Please click
					on the link below to try again.
					<p style="margin-left:15; margin-right:10">
					<a href="javascript:document.location.reload()">Try Again</a>
				</p></span>
				
			<?php
			}
		}
	}
	else
	{
		// Show the form to modify the config file
		if($isSetup == 'no')
		{
			$buttonText = "Setup SiteWorks >>";
			$title = "First Time Setup";
		}
		else
		{
			$buttonText = "Update Settings >>";
			$title = "SiteWorks Configuration";
		}
		?>
			<script language="JavaScript">
			
				function RemoveImgTypeButton(itemId)
				{
					document.frmSetup.imgTypeList.options[itemId] = null;
				}
				
				function AddImgType(imgType)
				{
					if(imgType != '')
					{
						// Does this image type already exist in the list?
						exists = false;
						for(i = 0; i < document.frmSetup.imgTypeList.length; i++)
						{
							if(document.frmSetup.imgTypeList.options[i].text == imgType)
							{
								exists = true;
								break;
							}
						}
						
						if(exists == true)
						{
							alert('This image type is already in the list');
							document.frmSetup.newImgType.focus();
							document.frmSetup.newImgType.select();
						}
						else
						{
							document.frmSetup.imgTypeList.add(new Option(imgType));
						}
					}
					else
					{
						alert('Please enter an image type');
						document.frmSetup.newImgType.focus();
					}
					
					document.frmSetup.newImgType.select();
					document.frmSetup.newImgType.focus();
				}
				
				function DoIt()
				{
					for(i = 0; i < document.frmSetup.imgTypeList.length; i++)
						document.frmSetup.imgTypeH.value = document.frmSetup.imgTypeH.value + document.frmSetup.imgTypeList.options[i].text + '|';

					return true;
				}
				
				function showNote(helpId)
				{
					help1 = 'SITE LOGO\r\n\r\nIn this field you should enter the path to your sites logo image. If the logo is on another domain then you can specify that domain as well, for example http://www.myothersite.com/logo.gif';
					help2 = 'EZINE POPUP\r\n\r\nIf ticked, then when a user leaves your site a popup form inviting them to join your newsletter will be displayed. This method has been known to increase signup rates by as much as 500%';
					help3 = 'USERS ONLINE\r\n\r\nIf ticked, a column will show up on your SiteWorks site showing the number of users who are currently browsing your web site.';
					help4 = 'SITE KEYWORDS\r\n\r\nKeywords are important to search engines. In this field you should enter 5-15 keywords that describe your site with each seperated by a comma. These keywords will be used to dynamically build your sites keyword meta tag.';
					help5 = 'SITE DESCRIPTION\r\n\r\nIn this field you should enter a 10-20 word description of your site. It will be used to dynamically build your sites description meta tag.';
					help6 = 'MYSQL SERVER\r\n\r\nIn this field you should specify the I.P. address or domain name of the server that contains your MySQL database, e.g. localhost, 127.0.0.1, db.mysite.com, etc.';
					help7 = 'MYSQL USER\r\n\r\nIn this field you should specify the username for your MySQL account. This username will be used to try and validate you against your MySQL server.';
					help8 = 'MYSQL PASSWORD\r\n\r\nIn this field you should specify the password for your MySQL account. This password will be used to try and validate you against your MySQL server.';
					help9 = 'MYSQL DATABASE NAME\r\n\r\nIn this field you should specify the name of your database on the MySQL server, such as mydatabase, db_somesite, etc. Your user account must have full access to this database.';
					help10 = 'MAXIMUM IMAGE SIZE\r\n\r\nWhen you upload images into your content using the image button on the toolbar of the rich text box, each image is checked to make sure it is under a certain size. The default size is 50,000 bytes (approx. 50KB).';
					help11 = 'IMAGE TYPES\r\n\r\nWhen you upload images into your content using the image button on the toolbar of the rich text box, each images file type is checked. This list shows the MIME types for acceptable images. The default should suffice in most cases, however if you also want to upload other types of images, or even zips, applications, etc, then you should add their MIME types to this list.';
					help12 = 'RECORDS TO SHOW PER PAGE IN ADMIN AREA\r\n\r\nThe number of articles, news posts, etc to show per page in the admin area before you have to page to the next set of results.';
					help13 = 'AUTHORS TO SHOW PER PAGE ON SITE\r\n\r\nThe number of authors to show per page on your site before you have to page to the next set of results.';
					help14 = 'NEWS POSTS TO SHOW PER PAGE ON SITE\r\n\r\nThe number of news posts to show per page on your site before you have to page to the next set of results.';
					help15 = 'ARTICLES TO SHOW PER PAGE ON SITE\r\n\r\nThe number of articles to show per page on your site before you have to page to the next set of results.';
					help16 = 'FORUM TYPE\r\n\r\nIf you are running a forum to complement your site, then SiteWorks can grab the most recent posts from that forum and show them as a column on your site. If you have no forum setup, then leave the default value of none selected. Otherwise you may choose the VBulletin or PHPBB forum option.\r\n\r\nNote: If you choose a forum type then you must complete the details for that forums MySQL database below.';
					help17 = 'FULL URL TO FORUM\r\n\r\nIf you\'re running a forum then you should specify the full domain name and path to your forum here, such as http://www.mysite.com/forum, etc.';
					help18 = 'EMAIL BODY\r\n\r\nWhen you add a new administrator or publisher to the users table they are sent an email containing their login details. You can customize the contents of this email by changing the text in this box.';
					help19 = 'PUBLISHER PERMISSIONS\r\n\r\nThere are two types of users for your SiteWorks admin area: Administrators (full access) and Publishers (limited access). This list of items is what publishers can or cannot access through the admin system. To disable access to a certain area, simply remove the tick in that items checkbox.';
					help20 = 'SHOW FEATURED BOOK\r\n\r\nIn the SiteWorks admin area you can add books that will be displayed on the books.php page of your site. If this option is ticked, then a random book will also be grabbed from the database each time your site is loaded and displayed in a column entitled "Featured Book" on your site.';
					help21 = 'SITE TEMPLATE\r\n\r\nSiteWorks includes 3 templates that you can choose from to change the look, feel and layout of your site. Each of these templates represents a completly different look and your design will be updated automatically to reflect the selected template.';
					help22 = 'TABLE PREFIX\r\n\r\nIf you are running PHPBB as your forum package, then specify your table prefix here.';					
					help23 = 'CLOSE SITE\r\n\r\nYou can close your site off from the public by simply clicking this checkbox and then clicking on the "Update Settings" at the bottom of this page.';
					help24 = 'SITE CLOSURE MESSAGE\r\n\r\nThis is the message your visitors will see when you close your web site down for maintenance';
					help25 = 'ARTICLE COMMENTS\r\n\r\nCheck this box if you would like visitors to be able to post comments on your articles';
					help26 = 'MANUALLY APPROVE COMMENTS\r\n\r\nCheck this box if you would like to manually approve all comments before the are live';
					help27 = 'SHOW MOST RECIENT COMMENTS\r\n\r\nThe number of comments shown underneath each article. Comments are ordered from most recent to least recent.';
					help28 = 'DISPLAY AUTHOR IMAGES\r\n\r\nUncheck this option if you do not want to display images for your authors';
					help29 = 'SHOW XML FEED\r\n\r\nUncheck this option if you do not want to display a link to your XML feed on your site';
					help30 = 'TIP COLUMN NAME\r\n\r\nYou can rename the "Tip" column to have a more suitable name by entering it here';
					help31 = '2C COLUMN NAME\r\n\r\nYou can rename the "2c" column to have a more suitable name by entering it here';
					alert(eval('help'+helpId));
				}

				
			</script>
			<form onSubmit="return DoIt()" name="frmSetup" action="setup.php?post=true" method="post">
			<input type="hidden" name="imgTypeH" value="">
			<input type="hidden" name="old_setup" value="<?php echo $isSetup; ?>">
			<p style="margin-left:15; margin-right:10" class="BodyText">
			<span class="BodyHeading"><?php echo $title; ?></span><br><br>
			<?php if($isSetup == 'no') { ?>
				Hi, welcome to SiteWorks Professional. I see that it's your first time here. To setup SiteWorks
				Professional on your web server, simply complete the form below and then click on the "Setup SiteWorks >>"
				button at the bottom of the form. This will save your settings and build your SiteWorks database.
			<?php } else { ?>
				To update your SiteWorks settings, simply change them using the form below and then click on the "Update Settings >>"
				button at the bottom of the page.
			<?php } ?>
			<br><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<b><i>Site Details</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							Site Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteName" value="<?php echo $siteName; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							Site URL:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteURL" value="<?php echo $siteURL; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(1)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Site Logo:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteLogo" value="<?php echo $siteLogo; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(21)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Site Template:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<select name="template">
							<option value="template1" <?php if($template == "template1") { echo " SELECTED "; } ?>>Template #1</option>
							<option value="template2" <?php if($template == "template2") { echo " SELECTED "; } ?>>Template #2</option>
							<option value="template3" <?php if($template == "template3") { echo " SELECTED "; } ?>>Template #3</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(2)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Show Ezine Popup:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="ezinePopup" value="true" <?php if($ezinePopup == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Yes</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(3)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Show Users Online:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="showNumUsers" value="true" <?php if($showNumUsers == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Yes</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(20)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Show Feat. Book:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="showFeaturedBook" value="true" <?php if($showFeaturedBook == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Yes</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(28)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Author Images:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="showAuthorImages" value="true" <?php if($showAuthorImages == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Show</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(29)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							XML Feed:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="showXML" value="true" <?php if($showXML == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Show</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(30)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Tip Column Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="showHandy" value="<?php echo $showHandy ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(31)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							2c Column Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="showMy2c" value="<?php echo $showMy2c; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Site Maintenance</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(23)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Close Site:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="siteClosed" onClick="if(this.checked) { confirm('This option will close down the front end of the site and display a maintenance message.'); } " value="true" <?php if($siteClosed == true) { echo " CHECKED "; } ?>>
						<span class="BodyText">Yes</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(24)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;Site&nbsp;Closure&nbsp;Message:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<textarea name="siteCloseMessage" rows="5" cols="45"><?php echo $siteCloseMessage; ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Article Comments</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(25)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Comments:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="siteComments" value="true" <?php if($siteComments) echo 'checked'; ?> size="40">
						<span class="BodyText">Show</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(26)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Approve:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="checkbox" name="siteCommentsApprove" value="true" <?php if($siteCommentsApprove) echo 'checked'; ?> size="40">
						<span class="BodyText">Manually Approve Comments</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(27)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Show:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteCommentsShow" value="<?php echo $siteCommentsShow; ?>"  size="5">
						<span class="BodyText">Most Recent Comments Per Article</span>
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Meta Tags</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(4)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Site Keywords:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteKeywords" value="<?php echo $siteKeywords; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(5)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Site Description:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="siteDescription" value="<?php echo $siteDescription; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Admin Contact Details</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="adminName" value="<?php echo $adminName; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							Email:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="adminEmail" value="<?php echo $adminEmail; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>MySQL Database Details</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(6)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Server:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="dbServer" value="<?php echo $dbServer; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(7)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							User:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="dbUser" value="<?php echo $dbUser; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(8)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Password:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="dbPass" value="<?php echo $dbPass; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(9)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Database Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="dbDatabase" value="<?php echo $dbDatabase; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>ImageBank Options</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(10)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Max Image Size:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="ibMaxFileSize" value="<?php echo $ibMaxFileSize; ?>" size="5"> <span class="BodyText">bytes (1,000 bytes = approx. 1KB)</span>
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top">
						<span class="BodyText">
							<br><a href="javascript:showNote(11)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Image Types:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<select id="imgTypeList" style="width:265" name="ibTypes[]" multiple size=4>
						<?php
						
							foreach($ibTypes as $i)
								echo "<option value='$i'>$i</option>";
						?>
						</select><br>
						<input id="newImgType" type="text" name="imgType" value="" size="10"><input onClick="AddImgType(newImgType.value)" type="button" value="Add" id=button1 name=button1><input onClick="RemoveImgTypeButton(imgTypeList.selectedIndex)" id="removeImgTypeButton" type="button" value="Remove Selected">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Records To Show Per Page</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(12)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							In Admin Area:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="recsPerPage" value="<?php echo $recsPerPage; ?>" size="5">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(13)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Authors On Site:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="authorsPerPage" value="<?php echo $authorsPerPage; ?>" size="5">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(14)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							News On Site:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="newsPerPage" value="<?php echo $newsPerPage; ?>" size="5">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(15)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Articles On Site:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" name="articlesPerPage" value="<?php echo $articlesPerPage; ?>" size="5">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>Forum Details</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(16)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Forum Type:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<select onClick="ToggleForum(this)" name="forumTypeList" size="3">
							<option value="" <?php if($isVBulletinForum == false && $isPHPBBForum == false) echo " SELECTED "; ?>>None</option>
							<option value="vbulletin" <?php if($isVBulletinForum == true) echo " SELECTED "; ?>>VBulletin</option>
							<option value="phpbb" <?php if($isPHPBBForum == true) echo " SELECTED "; ?>>PHPBB</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(6)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Database Server:&nbsp;&nbsp;</div>
						</span>
					</td>
					<td width="75%">
						<input onClick="if(this.value == '[None]') { this.value = ''; }" type="text" name="forumDBServer" value="<?php echo $forumDBServer; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(7)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Database User:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input onClick="if(this.value == '[None]') { this.value = ''; }" type="text" name="forumDBUser" value="<?php echo $forumDBUser; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(8)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Database Password:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input onClick="if(this.value == '[None]') { this.value = ''; }" type="text" name="forumDBPass" value="<?php echo $forumDBPass; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(9)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Database Name:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input onClick="if(this.value == '[None]') { this.value = ''; }" type="text" name="forumDBName" value="<?php echo $forumDBName; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(17)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Full URL To Forum:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" onClick="if(this.value == '[None]') { this.value = ''; }" name="forumDBPath" value="<?php echo $forumDBPath; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="25%">
						<span class="BodyText">
							<a href="javascript:showNote(22)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Table Prefix:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<input type="text" onClick="if(this.value == '[None]') { this.value = ''; }" name="forumDBPrefix" value="<?php echo $forumDBPrefix; ?>" size="40">
					</td>
				</tr>
				<tr>
					<td width="100%" colspan="2">
						<span class="SubHeading">
							<br><b><i>New Administrator / Publisher Email Text</i></b>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top">
						<span class="BodyText">
							<br><a href="javascript:showNote(18)"><img alt="Click here for help on this item" border="0" src="help.gif"></a>&nbsp;
							Email Body:&nbsp;&nbsp;
						</span>
					</td>
					<td width="75%">
						<br><textarea rows="5" cols="45" name="authorEmail"><?php echo str_replace(">", "&gt;", str_replace("<", "&lt;", $authorEmail)); ?></textarea><br>
						<span class="BodyText">
							<br><b>Note:</b> The values in double triangle brackets will be replaced with the appropriate values when the email is sent. These
							values are:
							<ul>
								<li>&lt;&lt;AuthorName&gt;&gt; The name of the new admin/publisher</li>
								<li>&lt;&lt;SiteName&gt;&gt; The name of your site</li>
								<li>&lt;&lt;SiteURL&gt;&gt; The URL of your site</li>
								<li>&lt;&lt;Username&gt;&gt; The new users login username</li>
								<li>&lt;&lt;Password&gt;&gt; The new users login password</li>
								<li>&lt;&lt;AdminName&gt;&gt; Your sites admin contact name</li>
								<li>&lt;&lt;AdminEmail&gt;&gt; Your sites admin contact email</li>
							</ul>
						</span>
					</td>
				</tr>
				<tr>
					<td width="25%">&nbsp;
						
					</td>
					<td width="75%">
						<br><input type="button" value="Cancel" onClick="document.location.href='index.php'"> <input type="submit" value="<?php echo $buttonText; ?>" id=submit1 name=submit1>
					</td>
				</tr>
			</table>
			</p>
			</form>
		<?php
	}
	
	include(realpath("templates/dev_bottom.php"));
?>