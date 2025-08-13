<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
require("cvar.php");
if($stage=="2A"){
	/*
	Action Stage for Stage 2 - write the db.php file
	*/
	if(file_exists($file)){
		//file is there-check status
		if(is_writable($file)){
			//file is writable
				//open file for writing
				$fp=fopen($file,'w') or die("Can not open $file for writing!");
				fwrite($fp,"<?php\n") or die("Problem writing file! 1");
				fwrite($fp,"//THIS FILE IS WRITTEN BY THE INSTALLATION WIZARD\n") or die("Problem writing file! 2");
				fwrite($fp,"//DO NOT EDIT\n") or die("Problem writing file! 3");
				fwrite($fp,"//	database variables\n") or die("Problem writing file! 4");
				fwrite($fp,'$setting["dbuser"]="'.$dbuser.'";') or die("Problem writing file! 5");
				fwrite($fp,"\n") or die("Problem writing file! 6");
				fwrite($fp,'$setting["dbpass"]="'.$dbpass.'";') or die("Problem writing file! 7");
				fwrite($fp,"\n") or die("Problem writing file! 8");
				fwrite($fp,'$setting["dbhost"]="'.$dbhost.'";') or die("Problem writing file! 9");
				fwrite($fp,"\n") or die("Problem writing file! 10");
				fwrite($fp,'$setting["dbselect"]="'.$dbselect.'";') or die("Problem writing file! 11");
				fwrite($fp,"\n") or die("Problem writing file! 12");
				fwrite($fp,'$table_pre="'.$table_pre.'";') or die("Problem writing file! 13");
				fwrite($fp,"\n?>") or die("Problem writing file! 14");
				//close file
				fclose($fp);
				//Database Connection Information has been written to file
				confirmation_screen();
		}//end is writable
		else{
			die("Please CHMOD $file so that it can be written to!");
		}
	}//end file exists
	else{
		die("Please check your installation.  $file is missing!");
	}
}//end stage 3
//--------------------------------------------------------------------
function confirmation_screen(){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Support Services Manager Installation</title>
</head>
<body>
<P align=center><FONT face=Verdana size=4>Support Services 
Manager&nbsp;<?php print $ver; ?></FONT><BR><FONT face=Verdana>Installation &amp; Setup</FONT></P>
<P>
<HR width="90%" color=black SIZE=1>
<P></P>
<P align=left><FONT face=Verdana>Your database connection information has been successfully written to the correct file.&nbsp; 
Please take this time to open and edit <STRONG>config.php</STRONG> in the main 
folder of this SSM installation.&nbsp; Please change <STRONG>line 18</STRONG> to 
reflect the correct system path to the db.php file, which should be 
in the same folder.&nbsp; An example has been shown below: 
</FONT></P>
<pre> require("/system/path/to/db.php");</pre>
<P><FONT face=Verdana>After you have completed this change, please click 
Continue below to have Setup make the neccessary additions to your 
database.</FONT></P>
<HR width="90%" color=black SIZE=1>
<form action="setup_4.php" method="post">
<input type="hidden" name="stage" value="3">
<center><input type="submit" value="Continue"></center>
</form>
</body>
</html>
<?php
}//end function
//--------------------------------------------------------------------
?>