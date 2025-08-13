<?php
/************************************************************************/
/*  Program Name         : QuizShock                                    */
/*  Program Version      : 1.5.4                                        */
/*  Program Author       : Pineapple Technologies                       */
/*  Supplied by          : CyKuH [WTN]                                  */
/*  Nullified by         : CyKuH [WTN]                                  */
/*  Distribution         : via WebForum and Forums File Dumps           */
/*                  (c) WTN Team `2004                                  */
/*   Copyright (c)2002 Pineapple Technologies. All Rights Reserved.     */
/************************************************************************/
error_reporting(7);

$date = gmdate("D, d M Y H:i:s") . " GMT";
header("Last-Modified: $date");
header("Expires: $date");
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0");

?>
<html>
<head>
<title>QuizShock Installation</title>

<style type="text/css">

a {
	FONT-WEIGHT: bold;
	COLOR: #184A9C;
}

a:hover {

	FONT-WEIGHT: bold;
	COLOR: #4883E4;
}

</style>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<center><img src="images/quizshock.gif">
<font size=2 face="verdana,tahoma,arial,helvetica">
<br><br>
<?php

require("script_ext.inc");

if( !isset($script_ext) )
{
	echo "File <b>script_ext.inc</b> was not found or is corrupted. Please ensure that you have uploaded this file to the same directory as this installation program.";
	exit;
}
	
$config_file = "inc/config.inc" . $script_ext;
if( !@file_exists($config_file) )
{
	echo "Couldn't find the config file: <b>$config_file</b>. Please ensure that it exists and that it was properly uploaded to the server!";
	exit;
}

require($config_file);
require("inc/lib.inc" . $script_ext);

if(!$step = $HTTP_GET_VARS['step'])
{
	$step = $HTTP_POST_VARS['step'];
}

$rd = $HTTP_GET_VARS['rd'];


?>
<table cellpadding=0 cellspacing=0 border=0 width=700>
<tr><td bgcolor="#6E8EBE" width="100%">

<table cellpadding=5 cellspacing=1 border=0 width="100%">
<tr><td bgcolor="#415E8D" align=center width="100%">
<b><font size=4 face="verdana,arial,tahoma,helvetica" color="#FFFFFF">

QuizShock <?php echo TS_VERSION;?> Nullified Installation
<tr><td bgcolor="#eeeeee" align=center width="100%">
<font size=2 face="arial,tahoma,helvetica">
	Thanks CyKuH in WTN Team. 
</font>
</td></tr>

<tr><td bgcolor="#F0F0F0">

<font size=2 face="verdana,tahoma,arial,helvetica">

<blockquote><blockquote>
<?php
require("inc/" . $TS_SCRIPTS['DB_CLASS']);
$db = new db_sql($DB_INFO['HOSTNAME'], $DB_INFO['USERNAME'], $DB_INFO['PASSWORD'], $DB_INFO['DATABASE']);

if( !$step )
{
	?>
	<blockquote>
	Welcome. This program will install QuizShock <?php echo TS_VERSION;?> on your web site.
	The installation procedure is divided into several steps. At each step, simply click the "Proceed to step..." link at the bottom of the page.
	If an error occurs during installation, the procedure will stop and you will be given a chance to correct the error and repeat the step if possible.
	To start the installation, click "Begin Installation" below.
	<?php
	
	step_link("Begin Installation", 1);
}

elseif( $step == 1 )
{
	echo "Checking your PHP version...<br><br>";
	
	$php_version = phpversion();
	
	if( floor($php_version) < 4 )
	{
		echo "TriviaShock requires <b>PHP 4.0</b> or greater to run. You are currently running "
			."<b>PHP $php_version</b>. You will need to upgrade before you can use TriviaShock. "
			."You can visit <a href=\"http://www.php.net\">http://www.php.net</a> to obtain the "
			."latest version of the software. Please contact your system administrator for more "
			."information on upgrading your PHP version.";
		
		exit;
	}
	
	echo "Looks good.";
	step_link("Proceed to Step 2 >>", 2);
	
} 

elseif( $step == 2 )
{
	echo "Checking to see if your software package is complete...";
	$check_dirs = array("admin", "inc", "swf", "images", "swf/skins");
	$check_files = array(

		'index'					. $script_ext,
		'global'				. $script_ext,

		'quiz'					. $script_ext,
		'take_quiz'				. $script_ext,
		'quiz_info'				. $script_ext,
		'user'					. $script_ext,

		'auth'					. $script_ext,
		'forgot_info'				. $script_ext,


		'admin/admin_global'			. $script_ext,
		'admin/admin_category'			. $script_ext,
		'admin/admin_category_br'		. $script_ext,
		'admin/admin_category_import'		. $script_ext,
		'admin/admin_category_export'		. $script_ext,
		'admin/admin_game'			. $script_ext,
		'admin/admin_game_br'			. $script_ext,
		'admin/admin_game_section'		. $script_ext,
		
		'admin/admin_game_skin'			. $script_ext,
		'admin/admin_game_skin_br'		. $script_ext,
		
		'admin/admin_options'			. $script_ext,
		'admin/admin_question'			. $script_ext,
		'admin/admin_question_br'		. $script_ext,
		'admin/admin_template'			. $script_ext,
		'admin/admin_template_br'		. $script_ext,
		'admin/admin_template_vars'		. $script_ext,
		'admin/admin_game_skin_tester.swf',
		'admin/admin_manual'			. $script_ext,

		'admin/admin_main'			. $script_ext,
		'admin/index'				. $script_ext,
		
		'inc/admin_lib.inc'			. $script_ext,
		'inc/lib.inc'				. $script_ext,
		'inc/db_class.inc'			. $script_ext,
		'inc/config.inc'			. $script_ext,
		'inc/quizshock.sql',

		'swf/quizshock.swf',
		'swf/skins/default.swf'	

		); // end array()

        ////////////////////////////////////////////////
        // Check for missing directories
        ////////////////////////////////////////////////
	@reset($check_dirs);
	while( @list($key,$value) = @each($check_dirs) )
	{
		if( !@file_exists($value) )
		{
			$missing_dirs[] = $value;
		}
	}

	if(count($missing_dirs))
	{
		echo "Uh oh! It appears that the software package is not complete. This means that one or more directories (folders) were not found:";
		echo "<blockquote><ul type=square>";

		for($i=0;$i<count($missing_dirs);$i++)
			echo "<li>$missing_dirs[$i]</li>";

		echo "</ul></blockquote><br>";

		echo "Please check and make sure you created these directories (folders) when you uploaded the program to your server. They should be within the main directory (folder) you uploaded the software too.";
	}
	@reset($check_files);
	while(@list($key,$value) = @each($check_files))
	{
		if(!@file_exists($value))
		{
			$missing_scripts[] = $value;
		}
	}
	if(count($missing_scripts))
	{
		echo "Uh oh! It appears that this software package is not complete. This means you are missing one or more files that are supposed to come with this program. We couldn't find the following file(s): ";
		echo "<blockquote><ul type=square>";
		for($i=0;$i<count($missing_scripts);$i++)
		{
			echo "<li>$missing_scripts[$i]</li>\n";
		}

		echo "</ul></blockquote><br>";

		echo "Please check to ensure that you uploaded every file that came with the software to it's proper location.";

	} // end if
	if( count($missing_dirs) || count($missing_scripts) )
	{
		step_link("Re-try this step", 2);
		step_link("<< Start Over", 0);
		
	}
	
	else
	{
		echo "Looks good.";
		step_link("Proceed to Step 3 >>", 3);
	}
	
	
} // end if $step==2

elseif( $step == 3 )
{

	echo "Attempting to connect to MySQL database server...<br><br>";

        if( !$db->connect() )
        {
                echo "Unable to connect to your MySQL database server! Here's the information we tried:<br>";
                echo "<blockquote>";
                echo "<b>Host:</b> $DB_INFO[HOSTNAME]<br>";
                echo "<b>Username:</b> $DB_INFO[USERNAME]<br>";
                echo "<b>Password:</b> $DB_INFO[PASSWORD]<br>";
                echo "</blockquote><br><br>";

                echo "Please ensure that this information is correct. If it is not, edit <b>$config_file</b> and enter the correct information.";

		$db_error = 1;
        }
	if( !$db->select_db() && !$db_error )
	{
		echo "<br>Database <b>$DB_INFO[DATABASE]</b> not found... attempting to create it...<br><br>";
                
		if($db->query("CREATE DATABASE $DB_INFO[DATABASE]"))
		{
                        echo "Database successfully created.";
		}
                else
                {
                        echo "Unable to create the database <b>$DB_INFO[DATABASE]</b>, you may need to set it up manually in MySQL.";
                        $db_error = 1;
                }
        }

	if( !$mysql_version = $db->query_one_result("SELECT VERSION()") )
	{
		$mysql_version = "(unknown version)";
	}
	if( (float)$mysql_version < 3.23 )
	{
		echo "TriviaShock requires <b>MySQL 3.23</b> or greater, and will not run on any earlier versions."
			."<br>You are currently running <b>MySQL $mysql_version</b>, and you will need to upgrade before "
			."you can install TriviaShock. You can find the latest MySQL version at "
			."<a href=\"http://www.mysql.com/\">http://www.mysql.com</a>. Please contact your system administrator "
			."for more information on upgrading MySQL on your system.";
			
		$db_error = 1;
	}
	if($db_error)
	{
		step_link("Re-try this step", 3);
		step_link("<< Start Over", 0);
	}
	else
	{
		echo " Successfully connected and selected your database";
		
		step_link("Proceed to Step 4 >>", 4);
	}
	
} // end if $step==3

elseif( $step == 4 )
{
	$db->connect();
	$db->select_db();
	$db->debug_off();
	$db->die_on_error = 1;

	echo "Creating database tables and setting up default configuration...<br><br>";
	
	echo "<blockquote>";

	$queries = @file("inc/quizshock.sql");
	
	$num_queries = count($queries);
	for($i=0;$i<$num_queries-1;$i++)
	{
		if( !ereg("^[\r\n]", $queries[$i]) && !ereg("^#", $queries[$i]) )
		{
			$db->query( substr($queries[$i], 0, strlen($queries[$i]) -2) );
		}

	}

	echo "<br><br>Completed.";
		
	step_link("Proceed to step 5 >>", 5);
	
} // end if $step == 4

elseif( $step == 5 )
{
	echo "Checking for proper directory permissions...<br><br>";
	if( !@fclose(@fopen($TS_DIRS[GAME_SKINS] . '/test', 'a')) )
	{
		echo "It appears that the game skin directory (<b>$TS_DIRS[GAME_SKINS]</b>) is not set to writable. "
			."You will not be able to upload or delete game skins from the control panel unless this directory is set "
			."to world writeable, or writable by the user your web server is running as."
			."<br><br>If you are on a UNIX system, you can set the directory to world writeable with this command:<br><br>"
			."<b>chmod 777 $TS_DIRS[GAME_SKINS]</b><br>";

		step_link("Re-try this step", 5);			

	} // end if
	
	else
	{
		echo "Looks good, game skin directory (<b>$TS_DIRS[GAME_SKINS]</b>) is writable.<br>";
	}
		
	@unlink($TS_DIRS[GAME_SKINS] . '/test');

	step_link("Proceed to step 6 >>", 6);

} // end if $step == 5

elseif( $step == 6 )
{
	$db->connect();
	$db->select_db();
	if( !isset($HTTP_POST_VARS['admin_password']) )
	{
		?>
		Please set the administrative password by entering it into the box below.<br><br>
		This password will be used to log into the admin control panel which is used
		to set up everything in the program.

		<br><br>
		Set the password to something difficult to guess, and don't give it out to
		untrusted users! Anyone with this password will have full access to the software
		and will be able to change anything!
		
		<br><br>
		
		<blockquote>
		<?php
		
		echo "<form action=\"$TS_SCRIPTS[INSTALL]\" method=POST>"
			."<input type=hidden name=step value=$step>"
			."<b>Set Password to:</b> "
			."<input size=15 name=\"admin_password\">"
			." <input type=submit value=\"Submit\"></form>";
	}
	else
	{
		if(empty($HTTP_POST_VARS['admin_password']))
		{
			$errors = "<li>Please enter a password</li>";
		}
 
		if(strlen($HTTP_POST_VARS['admin_password']) > 15)
		{
			$errors .= "<li>The admin password cannot be longer than 15 characters!</li>";
		}
		if($errors)
		{
		
			echo "<b>There were errors with your submission:<br><ul type=square>" . $errors . "</ul><br>";

			echo "<blockquote>";
			echo "<form action=\"$TS_SCRIPTS[INSTALL]\" method=POST>";
			echo "<input type=hidden name=step value=$step>";
			echo "<b>Set Password to:</b> ";
			echo "<input size=15 name=\"admin_password\" value=\"" . htmlspecialchars(stripslashes($HTTP_POST_VARS[admin_password])) . "\">";
			echo " <input type=submit value=\"Submit\">";
			echo "</blockquote>";

			exit;

		} // end if error
		else
		{
			$db->query("UPDATE ts_users SET password='" . md5($HTTP_POST_VARS['admin_password']) . "', date_registered=" . time() . " WHERE id=1 AND username='admin'");

			echo "Successfully set your admin password.";
		}

		step_link("Finish Installation >>", 7);
		
	} // end else

} // end if step==6

elseif( $step == 7 )
{
	$db->connect();
	$db->select_db();
	$trivia_site_url = 'http://' . dirname(getenv('HTTP_HOST') . getenv('SCRIPT_NAME'));
	
	$db->query("UPDATE ts_options SET the_value='$trivia_site_url' WHERE the_key='TRIVIA_SITE_URL'");
	
	?>
	Success! Installation is complete! You may now use the software.
	<Br><br>
	
	Please delete this program (<b><?php echo $TS_SCRIPTS['INSTALL'];?></b>) before
	using the software so that it cannot be run by anyone browsing your web site! Running
	installation again will install a fresh copy with a fresh database.
	
	<br><br>

	Next, <a href="admin/<?php echo $TS_SCRIPTS['AINDEX']; ?>">proceed to the control panel</a>.
	You will be prompted for your admin user name and password, which are as follows:
	
	<blockquote>
	<b>Username:</b> Admin<br><br>
	
	<b>Password:</b> <i>(what you set it to in step 5)</i>
	</blockquote>

	<br><br>
	
	Once you log in you will be given links to the online users manual to help you get started using the software.
	
	<br><br>
	
	Thank you for choosing TriviaShock!
	
	<?php
			

} // end if $step==7

else
{
	echo "Error - Invalid step specified";
	
	echo "</td></tr></table></td></tr></table>";
}

?>
</td></tr></table></td></tr></table>
<br><br>
<font size=1 face="verdana,arial,helvetica">
Copyright (c)2002 Pineapple Technologies. All Rights Reserved.
</font>
<?php

function step_link($text, $step)
{

	global $rd, $TS_SCRIPTS;
	mt_srand((double)microtime()*1000000);
	$nrd = mt_rand(0,1000000);
	if($nrd == $rd)
	{
		$nrd++;
	}
		
	echo "<br><br><div align=right><a href=\"$TS_SCRIPTS[INSTALL]?step=$step&rd=$nrd\" class=header2link>$text</a></div>";

} // end function step_link

?>
