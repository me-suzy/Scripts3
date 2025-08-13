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
	if($_POST['update'] == 'yes')
		{	

			if(@include(realpath("config.php")))
				{

					include(realpath("config.php"));

				}
			else
				{

			?>

			<html>
				<head>
					<title>SiteWorks Professional 4.0 to 5.0 update</title>
				</head>
				<style>
					body, td
						{
						
							font-family: verdana;
							font-size: 10pt;
						
						}
				</style>
				<body>
					<span style="color: red; font-weight: bolder;">Error:</span> Please ensure that you have placed this update file in the admin directory. This file needs to access your configuration file.	
				</body>
			</html>

			<?php

				}

		}
	else
		{

			?>

			<html>
				<head>
					<title>SiteWorks Professional 4.0 to 5.0 update</title>
				</head>
				<style>
					body, td
						{
						
							font-family: verdana;
							font-size: 10pt;
						
						}
				</style>
				<body>
					Welcome to the SiteWorks Professional 4.0 to 5.0 automatic update<br><br>
										
					<strong>What this update does:</strong><br>
This update will modify your Administrator login tables. You will now be able 
to control each users permissions seperately. The two security levels (Administrator 
and Publisher) have been removed and replaced with this new system. Note: All 
users permissions will reset, so it is vital that you change the permissions after 
this update.<br>
					<br>
					This update will create a new comments table. This will allow your visitors to 
					post comments to articles.<br>
					<br>
					<span style="color: red; font-weight: bolder; font-size: 15px;">***WARNING***</span><br>
We have tested this update script, however we can not guarantee that it will be 
100% successfull. Before you continue, please make sure that you have backed up 
your database. Also make sure you keep a backup of your config.php file located 
in the admin directory. <br>
					<form name="form1" method="post" action="v5update.php" onSubmit="if(confirm('Please ensure that you have backed up your previous installation of SiteWorks Professional')) { return true; } else { return false; }">
					  <input type="hidden" name="update" value="yes">
					  <input type="submit" name="Submit" value="Continue &raquo;">
					</form>
					</body>
			</html>

			<?php

		}

?>