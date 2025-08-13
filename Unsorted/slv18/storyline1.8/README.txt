########## INSTALLATION ##########
1 - Upload all files, keeping the directory structure. If you are on a unix platform CHMOD the following files to 0777. (rwxrwxrwx)

base/define.inc.php			[~very~ important to chmod or the installation will fail]
base/html/Default/banner.inc.php
base/html/Default/copyright.inc.php
base/html/Default/footer.inc.php
base/html/Default/index.tmpl.php
base/html/Default/menu.inc.php
base/html/Default/stats.inc.php
base/html/Default/style.css

2 - Go to http://www.yoururl.com/storyline/installer.php

Enter your details as they're requested.

3 - Once prompted to delete installer.php, do so.

4 - You're done!


########## GETTING STARTED ##########

Creating categories etc
	1 - Go to the Manage => Category link and enter your details
	2 - Go to the Manage => SubCategory link and create them, assigning them to a category

Design
	All designs can be altered through the Cosmetics links. If you have not CHMOD the files correctly, they will error as unwritable.
	
Avatars
	To add avatars to the selection available, just add .jpg or. gif files to the images/avatars folder. They _must_ only be in .jpg (not .jpeg) or .gif format. Recommended 	size is 60x60px
Ratings
	To alter the default ratings, open base/functions.func.php.
	Around line 38 should be 

		function rating($var) {
			if ( $var == 1 )
				$varb = "U";
			if ( $var == 2 )
				$varb = "PG";
			if ( $var == 3 )
				$varb = "15";
			if ( $var == 4 )
				$varb = "18";
			if ( $var == 5 )
				$varb = "NC-17";
			return $varb;
		}

	Replace the set rating with your own, but it is important to keep the order (most child friendly down to least child friendly)

########## UPGRADING ##########

The Upgrader will only work under the following conditions

1 - The v1.8 been installed as is in the install file - no table name changes
2 - The v1.8 tables have been placed in the same database as the old scripts tables
3 - The v1.5 tables names have not been altered from original installation docs
4 - Nothing has been added to the new installation - cats, subcats, stories, users (save the admin acc) 
5 - You are upgrading from v1.5

After doing the install, and before logging into the admin panel, open  http://www.yoururl.com/storyline/upgrader.php

Put in your current account numer (not the new install number, but your old account number.) If you did not have an account, use 1.

Press upgrade and the installer will work it all through for you.


########## LICENSE ##########

see the license.txt file.