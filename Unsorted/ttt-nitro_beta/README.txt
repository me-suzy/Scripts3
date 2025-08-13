//#####################
// Turbo Traffic Trader Nitro v1.0
//#####################
// Copyright (c) 2003 Choker (Chokinchicken.com). All Rights Reserved.
// This script is NOT open source.  You are not allowed to modify this script in any way, shape or form. 
// If you do not like this script, then DO NOT use it.  You do not have the right to make any changes whatsoever.
// If you upload this script then you do so knowing that any changes to this script that you make are in violation
// of International copyright laws.  We aggresively pursue ALL violaters.  Just DO NOT CHANGE THE SCRIPT!

//#####################
// REQUIREMENTS
//#####################

Your server must be running at least PHP 4.1.2
Your server must be running mysql 3.23.*
Your must have a mysql database with a valid login/password.  

//#####################
// INSTRUCTIONS
//#####################

How to install your script:

*NOTE*: To use QuickTGP builder, you must install TTT in your web root directory.
Extract the ZIP file with the path names intact. (If using WinZIP, make sure 'Use Folder Names' is checked)
Upload all of the files to your home directory keeping the path names intact. (Upload with all files in the proper directories)
- Be sure to upload all .php and .css files in ASCII mode.
- Be sure to upload all .gif files in BINARY mode.
 Created a directory named 'categories' in your webroot directory.
Chmod "categories" directory to 777.
Chmod "ttt_toplist" directory to 777.
Chmod "ttt-mysqlvalues.inc.php" to 777.
Chmod "/tttadmin/index_template.tmp" to 777.
Chmod "/tttadmin/page_template.tmp" to 777.
Chmod "index_template.qtgp" to 777.
Chmod "page_template.qtgp" to 777.
Go to ttt-install.php and follow the instructions on the page.

///

Once your script is successfully installed, do the following:

Delete ttt-install.php from your server.
Rename your main page so that it ends in .SHTML.
Add <!--#include file="ttt-in.php" --> to your main page between the <HEAD> </HEAD> tags.
Login to your TTT admin at: http://www.YOURDOMAIN.com/tttadmin/index.php and setup trades.