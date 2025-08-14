<?php
error_reporting(7);

require('./global.php');

// vBFTP 2.1 vBulletin Admin CP integration, Download, CHMOD and Search added by Dr. Erwin Loh
// Original stand alone script by Morten Bojsen-Hansen
//

// OS - set this to the OS of the server running the script
//   1 = Windows 95/98/ME/2000/XP
//   2 = Linux/Unix system or variant.

$os		=	2;

// DO NOT CHANGE ANYTHING BELOW UNLESS YOU KNOW WHAT YOU ARE DOING! :)

$home_dir	=	"../";
$use_login	=	true;			// Toggle login system (true/false).
$auto_login	=	true;			// Toggle auto login (true/false).
$username	=	"";	// Set a username
$password	=	"";	// Set a password.
$allow_view	=	true;
$allow_create	=	true;
$allow_edit	=	true;
$allow_chmod	=	true;
$allow_rename	=	true;
$allow_delete	=	true;
$allow_download	=	true;
$allow_upload	=	true;
$current_name	=	".";
$parent_name	=	"..";
$text_files	=	array(			// Editable files
                               "txt",
                               "php",
                               "phtml",
                               "php4",
                               "php3",
                               "html",
                               "htm",
                               "css",
                               "xml",
                               "xsl",
                               "bat",
                               "log",
                               "ini",
                               "inf",
                               "cfg",
                             );
$image_files	=	array(			// Viewable files
                               "jpg",
                               "jpeg",
                               "jpe",
                               "gif",
                               "png",
                               "bmp",
                             );
$archive_files	=	array(			// No affect exept icon change
                               "zip",
                               "tar",
                               "gz",
                               "tgz",
                               "z",
                               "ace",
                               "rar",
                               "arj",
                               "cab",
                               "bz2",
                             );
$sound_files	=	array(			// No affect exept icon change
                               "wav",
                               "mp3",
                               "mp2",
                               "mp1",
                               "mid",
                             );

$binary_files	=	array(			// No affect exept icon change
                               "exe",
                               "bin",
                               "dat",
                               "rpm",
                               "deb",
                             );
$ignore_file_strings		=	array(
                                               ".htaccess",
                                             );
$ignore_file_extensions		=	array(
                                               "foo",
                                               "bar",
                                             );
$ignore_directory_strings	=	array(
                                               "secret dir",
                                             );
$temp_dir	=	"../tmp/";
$use_timeout	=	false;
$timeout	=	30;
$max_drive	=	'H';
$version = "Version 2.1";							// Version
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");				// Headers
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if ($use_timeout) set_time_limit($timeout);					// Maximum execution time
ob_implicit_flush();								// Output on-the-fly
@import_request_variables("gpc");						// Import globals
$PHP_SELF = $_SERVER['PHP_SELF'];						// Import PHP_SELF

// Define variables
if (!isset($cookie_username))	$cookie_username	=	false;
if (!isset($cookie_password))	$cookie_password	=	false;
if (!isset($path))		$path			=	false;
if (!isset($action))		$action			=	false;

if ($cookie_username == $username && md5($cookie_password) == md5($password))	// Create cookie on login
{
 if ($auto_login)
 {
  setcookie("cookie_username", $username, time()+31536000);
  setcookie("cookie_password", md5($cookie_password), time()+31536000);
 }
 else
 {
  setcookie("cookie_username", $username);
  setcookie("cookie_password", md5($cookie_password));
 }
 header("Location: $PHP_SELF");
}

if ($action == "logout")							// Kill cookie on logout
{
 setcookie("cookie_username", "");
 setcookie("cookie_password", "");
 header("Location: $PHP_SELF");
}

function access_check($input_username, $input_password)
{
 global $use_login, $username, $password;

 if ($use_login && $input_username == $username && $input_password == md5($password))
  return 1;
 else if (!$use_login)
  return 1;
 else
  return 0;

}

$path = stripslashes($path);							// Strip slashes

if (stristr($path, "../") || stristr($path, "..\\"))				// Hacker protection
 $path = false;

if ($home_dir) $home_dir = realpath($home_dir)."/";				// Better looking homedir
else if (!$home_dir && $os == 2)
 $home_dir = dirname($SCRIPT_FILENAME)."/";

if ($path == "/" || $path == "./" || $path == "\\" || $path == ".\\")		// Better looking path
 $path = false;

if (is_dir($home_dir.$path))							// Ignored files/directories.
{
 foreach($ignore_directory_strings as $match)
  if (stristr(basename($path), $match))
   $action = "access_denied";
}
else if (is_file($home_dir.$path))
{
 foreach($ignore_file_strings as $match)
  if (stristr(basename($path), $match))
   $action = "access_denied";

 $ext = strtolower(substr(strrchr(basename($path), "."),1));
 foreach($ignore_file_extensions as $extension)
  if ($ext == $extension)
   $action = "access_denied";
}
if (!$action == "download_verify"){
print "<html>";
print "<body link='#0000FF' alink='#0000FF' vlink='#0000FF' bgcolor='#FFFFFF'><center>";
print "<font face='Verdana' size='4'><b>vB FTP $version</b></font>";
if ($use_login && $cookie_username && $cookie_password)
print "<br><font class='logout'><a href='$PHP_SELF?action=logout'>.:Logout:.</a></font>";
print "<div class='line_top'>&nbsp;</div>";
}
// ################################################################################################################
// ############## Access denied ###################################################################################
// ################################################################################################################

if ($action == "access_denied")
{
 print "<a href='$PHP_SELF?path='>.:Back:.</a><br><br>";
 print "<b>ERROR:</b> Access denied.<br><br>";
 print "You can't access ignored files/directories.";
}

// ################################################################################################################
// ############## Rename file/directory ###########################################################################
// ################################################################################################################

else if ($action == "chmod_prompt" && $allow_chmod && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br>";
		    print "<font face=\"arial, helvetica\"><center><h4>Current level: ";
			printf("%o", (fileperms($home_dir.$path.$file)) & 0777);
			print "</h4><form action='$PHP_SELF?action=chmod' method=post>\n";
			function selections($type)  //  type: 0 Owner, 1 Group, 2 Public
				{
				echo "<option value=\"0\""; if (substr($GLOBALS["perm"], $type, 1)=="0") echo "selected"; echo ">0 - No permissions";
				echo "<option value=\"1\""; if (substr($GLOBALS["perm"], $type, 1)=="1") echo "selected"; echo ">1 - Execute";
				echo "<option value=\"2\""; if (substr($GLOBALS["perm"], $type, 1)=="2") echo "selected"; echo ">2 - Write ";
				echo "<option value=\"3\""; if (substr($GLOBALS["perm"], $type, 1)=="3") echo "selected"; echo ">3 - Execute & Write";
				echo "<option value=\"4\""; if (substr($GLOBALS["perm"], $type, 1)=="4") echo "selected"; echo ">4 - Read";
				echo "<option value=\"5\""; if (substr($GLOBALS["perm"], $type, 1)=="5") echo "selected"; echo ">5 - Execute & Read";
				echo "<option value=\"6\""; if (substr($GLOBALS["perm"], $type, 1)=="6") echo "selected"; echo ">6 - Write & Read";
				echo "<option value=\"7\""; if (substr($GLOBALS["perm"], $type, 1)=="7") echo "selected"; echo ">7 - Write, Execute & Read";
				echo "</select>";
				}
			$perm = sprintf ("%o", (fileperms($home_dir.$path.$file)) & 0777);  // Definition of a variable containing the file permissions
			echo "<p><h4>Owner<br>";
			echo "<select name=\"owner\">";
			selections(0);

			echo "<p>Group<br>";
			echo "<select name=\"group\">";
			selections(1);

			echo "<p>Public<br>";
			echo "<select name=\"public\">";
			selections(2);

			echo "</h4>";
			echo "<p>";
			echo "<INPUT TYPE=\"SUBMIT\" NAME=\"confirm\" VALUE=\"Change\">\n";
			echo "<INPUT TYPE=\"SUBMIT\" NAME=\"cancel\" VALUE=\"Cancel\">\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"action\" VALUE=\"chmod\">\n";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"file\" VALUE=\"$file\">";
			echo "<INPUT TYPE=\"HIDDEN\" NAME=\"wdir\" VALUE=\"$path\">";
			echo "</FORM>";
			echo "</center>";
			}
		else if ($action == "chmod" && $allow_chmod && access_check($cookie_username, $cookie_password))
{
			$level = "0";
			$level .= $owner;
			$level .= $group;
			$level .= $public;
			$showlevel = $level;
			$level=octdec($level);
			chmod($home_dir.$path.$file,$level);
			print "Changed permission on $file to $showlevel";
   		    print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."/'>.:Back:.</a><br><br>";
			}


// ################################################################################################################
// ############## Rename file/directory ###########################################################################
// ################################################################################################################

else if ($action == "rename_prompt" && $allow_rename && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br>";
 print "<form action='$PHP_SELF?action=rename' method=post>";
 print "Please choose a new name for the file/directory:<br><br>";
 print "<input type=text size=30 name=newname value=\"".htmlentities(basename($home_dir.$path))."\">&nbsp;";
 print "<input type=submit value='Rename'>";
 print "<input type=hidden name=oldname value='".htmlentities(rawurlencode(basename($path)))."'>";
 print "<input type=hidden name=path value='".htmlentities(rawurlencode(dirname($path)))."'></form>";
}
else if ($action == "rename" && $allow_rename && access_check($cookie_username, $cookie_password))
{

 $path = stripslashes(rawurldecode($path));
 $oldname = stripslashes(rawurldecode($oldname));
 $newname = stripslashes(rawurldecode($newname));

 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."/'>.:Back:.</a><br><br>";

 print "Renaming file/directory...<br>";
 if (file_exists($home_dir.$path."/".$newname))
  print "<font color='#CC0000'>A file/directory with that name already exists.</font>";
 else
 {
  if (stristr($newname, "../") || stristr($newname, "..\\"))
   print "<font color='#CC0000'>Invalid directory- or filename.</font>";
  else if (@rename($home_dir.$path."/".$oldname, $home_dir.$path."/".$newname))
   print "<font color='#009900'>File/directory renamed successfully!</font>";
  else
   print "<font color='#CC0000'>File/directory rename failed.</font>";
 }
}

// ################################################################################################################
// ############## View image and clear temporary folder ###########################################################
// ################################################################################################################

else if ($action == "clear_temp" && $allow_view && access_check($cookie_username, $cookie_password))
{
 $open = opendir("$temp_dir");
 while (($file = readdir($open)) != false)
  if (is_file("$temp_dir/$file")) @unlink("$temp_dir/$file");
 closedir($open);
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
 print "Clearing temporary files...<br>";
 print "<font color='#009900'>Temporary files deleted successfully!</font><br><br>";
 print "Just click on the link to return to the file browser.";
}
else if ($action == "view" && $allow_view && access_check($cookie_username, $cookie_password))
{
 if (!isset($zoom)) $zoom = false;
 if (!isset($zoom_factor)) $zoom_factor	= false;

 $tempname = $temp_dir.basename($path);
 if (!file_exists($tempname)) @copy($home_dir.$path, "$tempname");
 if (!($image = @getimagesize($tempname)))
 {
  print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
  print "<font color='#CC0000'>Error opening image.</font><br><br>";
  print "This might be caused by an invalid image type or because you haven't<br>";
  print "set public read and write access on the temporary directory.<br>";
  print "Check config.php for more information on the temporary directory.";
 }
 else
 {
  if ($zoom == "in") $zoom_factor++;
  if ($zoom == "out") $zoom_factor--;

  if (!$zoom) $zoom = 0;
  if (!$zoom_factor) $zoom_factor = 0;

  $open = opendir(dirname($home_dir.$path));
  for($i=0;($file = readdir($open)) != false;$i++)
  {
   $ext = strtolower(substr(strrchr($file, "."),1));
   $is_image = false;
   foreach($image_files as $extension)
    if ($ext == $extension)
     $is_image = true;
   if (is_file(dirname($home_dir.$path)."/".$file) && $is_image)
    $files[$i] = $file;
  }
  closedir($open);
  @sort($files);

  if (count($files)>1)
  {
   for($i=0;$files[$i]!=basename($path);$i++);

   if ($i==0) $prev = $i+count($files)-1;
   else $prev = $i-1;
   if ($i==(count($files)-1)) $next = $i-count($files)+1;
   else $next = $i+1;
  }

  print "<br>";
  print "<table cellspacing=1 cellpadding=0 class='menu'>";
   if (count($files)>1) print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode(dirname($path)))."/".$files[$prev]."' class='menu'>&lt;&lt; Prev</a></td>";
   else print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path))."' class='menu'>&lt;&lt; Prev</a></td>";
   print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path))."&zoom=in&zoom_factor=$zoom_factor' class='menu'>::Zoom in::</a></td>";
   print "<td width=125><a href='$PHP_SELF?action=clear_temp&path=".htmlentities(rawurlencode($path))."' class='menu'>::Go back::</a></td>";
   print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path))."&zoom=out&zoom_factor=$zoom_factor' class='menu'>::Zoom out::</a></td>";
   if (count($files)>1) print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode(dirname($path)))."/".$files[$next]."' class='menu'>Next &gt;&gt;</a></td>";
   else print "<td width=125><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path))."' class='menu'>Next &gt;&gt;</a></td>";
  print "</tr></table><br>";

  print "<table width=600 cellspacing=0 cellpadding=0>";
   print "<tr class='bold'>";
    print "<td>Filename</td>";
    print "<td align=center width=130>Real width*height</td>";
    print "<td align=center width=130>Virtual width*height</td>";
    print "<td align=center width=50>Scale</td>";
   print "</tr><tr>";
    print "<td>".basename($path)."</td>";
    print "<td align=center width=130>$image[0]*$image[1]</td>";
    print "<td align=center width=130>".$image[0]*pow(2,$zoom_factor)."*".$image[1]*pow(2,$zoom_factor)."</td>";
    if (pow(2,$zoom_factor) >= 1)
     print "<td align=center width=50>".pow(2,$zoom_factor).":1</td>";
    else
     print "<td align=center width=50>1:".pow(2,-$zoom_factor)."</td>";
   print "</tr>";
  print "</table><br>";

  print "<img src='".dirname($tempname)."/".rawurlencode(basename($tempname))."' width='".$image[0]*pow(2,$zoom_factor)."' height='".$image[1]*pow(2,$zoom_factor)."'>";

 }
}

// ################################################################################################################
// ############## Upload file #####################################################################################
// ################################################################################################################

else if ($action == "upload_prompt" && $allow_upload && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br>";
 print "<form action='$PHP_SELF?action=upload' method=post enctype=multipart/form-data>";
 print "The uploaded file(s) will be placed in <font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br>";
 print "Please select the file(s) you want to upload:<br><br>";
 print "<table>";
  print "<tr><td>File 1:</td><td><input type=file name=upload[] size=30></td></tr>";
  print "<tr><td>File 2:</td><td><input type=file name=upload[] size=30></td></tr>";
  print "<tr><td>File 3:</td><td><input type=file name=upload[] size=30></td></tr>";
  print "<tr><td>File 4:</td><td><input type=file name=upload[] size=30></td></tr>";
 print "</table>";
 print "<input type=submit value=Upload><input type=hidden name=path value='".htmlentities(rawurlencode($path))."'>";
 print "</form>";
}
else if ($action == "upload" && $allow_upload && access_check($cookie_username, $cookie_password))
{
 $failed = false;
 $path = stripslashes(rawurldecode($path));

 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br><br>";
 print "Uploading files...<br><br>";

 print "<table cellspacing=0 cellpadding=0>";
 for($i=0;$i<=3;$i++)
 {
  if (@move_uploaded_file($_FILES['upload']['tmp_name'][$i], $home_dir.$path.$_FILES['upload']['name'][$i]))
   print "<tr><td width='250'>Uploading ".$_FILES['upload']['name'][$i]."...</td><td width='50' align='center'>[<font color='#009900'>OK!</font>]</td></tr>";
  else if ($_FILES['upload']['name'][$i])
  {
   print "<tr><td width='250'>Uploading ".$_FILES['upload']['name'][$i]."...</td><td width='50' align='center'>[<font color='#CC0000'>FAILED!</font>]</td></tr>";
  $failed = true;
  }
 }
 print "</table><br>";

 if ($failed)
  print "<font color='#CC0000'>Some uploads have failed.</font>";
 else
  print "<font color='#009900'>All uploads completed successfully!</font>";
}

// ################################################################################################################
// ############## Create directory ################################################################################
// ################################################################################################################

else if ($action == "create_directory_prompt" && $allow_create && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br>";
 print "<form action='$PHP_SELF?action=create_directory' method=post>";
 print "The new directory will be placed in <font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br>";
 print "Please choose a name for the new directory:<br><br>";
 print "<input type=text size=30 name=dirname>&nbsp;";
 print "<input type=submit value='Create directory'>";
 print "<input type=hidden name=path value='".htmlentities(rawurlencode($path))."'></form>";
}
else if ($action == "create_directory" && $allow_create && access_check($cookie_username, $cookie_password))
{
 $path = stripslashes(rawurldecode($path));

 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br><br>";
 print "Creating new directory...<br>";
 if (stristr($dirname, "../") || stristr($dirname, "..\\"))
  print "<font color='#CC0000'>Invalid directoryname.</font>";
 else if (file_exists($home_dir.$path.$dirname))
  print "<font color='#CC0000'>Directory already exists.</font>";
 else if (@mkdir($home_dir.$path.$dirname, 0700))
  print "<font color='#009900'>Directory created successfully!</font>";
 else
  print "<font color='#CC0000'>Directory create failed.</font>";
}

// ################################################################################################################
// ############## Create file #####################################################################################
// ################################################################################################################

else if ($action == "create_file_prompt" && $allow_create && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br>";
 print "<form action='$PHP_SELF?action=create_file' method=post>";
 print "The new file will be placed in <font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br>";
 print "Please choose a name for the new file:<br><br>";
 print "<input type=text size=30 name=filename>&nbsp;";
 print "<input type=submit value='Create file'>";
 print "<input type=hidden name=path value='".htmlentities(rawurlencode($path))."'></form>";
}
else if ($action == "create_file" && $allow_create && access_check($cookie_username, $cookie_password))
{
 $path = stripslashes(rawurldecode($path));

 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>.:Back:.</a><br><br>";
 print "Creating new file...<br>";
 if (file_exists($home_dir.$path.$filename))
  print "<font color='#CC0000'>File already exists.</font>";
 else
 {
  if (stristr($filename, "../") || stristr($filename, "..\\"))
   print "<font color='#CC0000'>Invalid filename.</font>";
  else if (@fopen($home_dir.$path.$filename, "w+"))
  {
   print "<font color='#009900'>File created successfully!</font>";
   $file_created = true;
  }
  else
   print "<font color='#CC0000'>File create failed.</font>";
 }
 if ($file_created == true && $allow_edit) print "<br><br><a href='$PHP_SELF?action=edit&path=".htmlentities(rawurlencode($path.$filename))."'>.:Edit your new file:.</a>";
}

// ################################################################################################################
// ############## Delete directory ################################################################################
// ################################################################################################################

else if ($action == "delete_directory_verify" && $allow_delete && access_check($cookie_username, $cookie_password))
{
 print "Are you sure you want to delete the following directory?<br><br>";
 print "<font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br><br>";
 print "Note that the directory must be empty and that you<br>";
 print "must have write access on the directory to delete it!<br><br>";
 print "<a href='$PHP_SELF?action=delete_directory&path=".htmlentities(rawurlencode($path))."'>Yes</a> or ";
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>Cancel</a>";
}
else if ($action == "delete_directory" && $allow_delete && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
 print "Deleting directory...<br>";
 if (@rmdir($home_dir.$path))
  print "<font color='#009900'>Directory deleted successfully!</font>";
 else
 {
  print "<font color='#CC0000'>Directory delete failed.</font><br><br>";
  print "Are you sure that the directory is empty<br>";
  print "and that you have permission to delete it?";
 }
}

// ################################################################################################################
// ############## Delete file #####################################################################################
// ################################################################################################################

else if ($action == "delete_file_verify" && $allow_delete && access_check($cookie_username, $cookie_password))
{
 print "Are you sure you want to delete the following file?<br><br>";
 print "<font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br><br>";
 print "<a href='$PHP_SELF?action=delete_file&path=".htmlentities(rawurlencode($path))."'>Yes</a> or ";
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>Cancel</a>";
}
else if ($action == "delete_file" && $allow_delete && access_check($cookie_username, $cookie_password))
{
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
 print "Deleting file...<br>";
 if (@unlink($home_dir.$path))
  print "<font color='#009900'>File deleted successfully!</font>";
 else
 {
  print "<font color='#CC0000'>File delete failed.</font><br><br>";
  print "Are you sure you have permission to delete<br>";
  print "this file and are you sure it isn't write protected?";
 }
}

// ################################################################################################################
// ############## Download file ###################################################################################
// ################################################################################################################

else if ($action == "download_verify" && $allow_download && access_check($cookie_username, $cookie_password))
{
 print "Are you sure you want to dowload the following file?<br><br>";
 print "<font color='#FF0000'>".htmlentities($home_dir.$path)."</font><br><br>";
 print "<a href='$PHP_SELF?action=download&path=".htmlentities(rawurlencode($path))."'>DOWNLOAD NOW!</a> or ";
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>GO BACK</a>";
}
else if ($action == "download" && $allow_download && access_check($cookie_username, $cookie_password))
{
$name = basename($path);
$size=filesize("$home_dir/$path");
header("Content-Type: application/force-download; name=\"$name\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $size");
header("Content-Disposition: attachment; filename=\"$name\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
readfile("$home_dir/$path");
print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
  print "<font color='#009900'>Download completed successfully!<br><br></font>";
}

// ################################################################################################################
// ############## Edit file #######################################################################################
// ################################################################################################################

else if ($action == "edit" && $allow_edit && access_check($cookie_username, $cookie_password))
{
 $path = stripslashes(rawurldecode($path));
 print "<form action='$PHP_SELF?action=save' method=post name=myform>";
 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br>";
 print "<textarea cols=100 rows=27 name=text wrap=off>";

 $fp = fopen ($home_dir.$path, "rb");
 $text = fread ($fp, filesize($home_dir.$path));
 fclose ($fp);
 print htmlentities($text);

 print "</textarea>";
 print "<br><br><input type=hidden name=path value='".htmlentities(rawurlencode($path))."'>";
print "<script language=\"JavaScript\"><!--
var pos = 0;
function findit() {
    if (document.myform.mytext.value == '') {
        alert('Nothing to search for');
        return;
    }
    if (document.all) {
        var found = false;
        var text = document.body.createTextRange();
        for (var i=0; i<=pos && (found=text.findText(document.myform.mytext.value)) != false; i++) {
            text.moveStart(\"character\", 1);
            text.moveEnd(\"textedit\");
        }
        if (found) {
            text.moveStart(\"character\", -1);
            text.findText(document.myform.mytext.value);
            text.select();
            text.scrollIntoView();
            pos++;
        }
        else {
            if (pos == '0')
                alert('\"' + document.myform.mytext.value +'\" was not found on this page.');
            else
                alert('No further occurences of \"' + document.myform.mytext.value +'\" were found.');
            pos=0;
        }
    }
    else if (document.layers) {
        find(document.myform.mytext.value,false);
    }
}

if (document.layers || document.all) {
      document.write('<input type=\"text\" name=\"mytext\">');
      document.write('<input type=\"button\" value=\"Find\" onClick=\"findit()\">');
}
//--></script>";
 print "&nbsp;<input type=reset value='Restore original'>&nbsp;<input type=submit value='Save & exit'>";
 print "</form>";
 }
else if ($action == "save" && $allow_edit && access_check($cookie_username, $cookie_password))
{
 $path = stripslashes(rawurldecode($path));

 print "<a href='$PHP_SELF?path=".htmlentities(rawurlencode(dirname($path)))."/'>.:Back:.</a><br><br>";
 print "Saving file...<br>";

 $fp = fopen ($home_dir.$path, "wb");
 $text = stripslashes($text);
 if (fwrite($fp, $text)!=-1)
  print "<font color='#009900'>File saved successfully.</font>";
 else
  print "<font color='#CC0000'>File save failed.</font>";
 fclose($fp);
}

else if (access_check($cookie_username, $cookie_password))
{

// ################################################################################################################
// ############## Drive browser [WINDOWS ONLY] ####################################################################
// ################################################################################################################

 if (!$path && $os == "1" && !$home_dir)
 {
  print "<table class='list'>";
   print "<tr bgcolor='#CCCCCC'>";
    print "<td width=100>Drive</b></td>";
    print "<td width=75 align=right>Free</b></td>";
    print "<td width=20 align=right>&nbsp;</b></td>";
    print "<td width=75 align=right>Total</b></td></td>";
   print "</tr>";

  for ($char='C';$char<=$max_drive;$char++)					// Show avalible drives <start>
  {
   if ($open = @opendir($char.":"))
    {
     $totalspace = number_format(round(disk_total_space($char.":")/1024/1024, 0), 0, ",", ".");
     $freespace = number_format(round(disk_free_space($char.":")/1024/1024, 0), 0, ",", ".");

     print "<tr>";
      print "<td width=20><a href='$PHP_SELF?path=$char:/'><img src='../images/ftp/drive.gif' border=0>&nbsp;$char</td>";
      print "<td width=75 align=right>$freespace MB</td>";
      print "<td width=20 align=right>/</td>";
      print "<td width=75 align=right>$totalspace MB</td>";
     print "</tr>";

     closedir($open);
    }
  }										// Show avalible drives <end>
  print "</table>";
 }

// ################################################################################################################
// ############## File browser ####################################################################################
// ################################################################################################################

 else if (@opendir($home_dir.$path))
 {
  print "<font class='current'>Current directory: ".htmlentities($home_dir.$path)."</font></font><br><br>";

  print "<table cellspacing=1 cellpadding=0 class='menu'>";
   if ($allow_create) print "<td width=175><a href='$PHP_SELF?action=create_directory_prompt&path=".htmlentities(rawurlencode($path))."' class='menu'>::Create new directory::</a></td>";
   if ($allow_create) print "<td width=175><a href='$PHP_SELF?action=create_file_prompt&path=".htmlentities(rawurlencode($path))."' class='menu'>::Create new file::</a></td>";
   if ($allow_upload) print "<td width=175><a href='$PHP_SELF?action=upload_prompt&path=".htmlentities(rawurlencode($path))."' class='menu'>::Upload files::</a></td>";
  print "</tr></table><br>";

  print "<table border=1 bordercolor='#000000' cellspacing=0 cellpadding=5 class='list'>";
   print "<tr>";
    print "<td width=250 valign=top>";

// ############## Show directories ################################################################################

     $open = opendir($home_dir.$path);
     for($i=0;($dir = readdir($open)) != false;$i++)
      if (is_dir($home_dir.$path.$dir) && $dir != "." && $dir != "..")
       $dirs[$i] = $dir;
     closedir($open);

     print "<table width=100% class='list'>";

      print "<tr class='info'>";
       print "<td width=20>&nbsp;</td>";
       print "<td>Dirname</td>";
       if ($allow_chmod) print "<td align='center' width=20>Ch</td>";
       if ($allow_rename) print "<td align='center' width=20>Rn</td>";
       if ($allow_delete) print "<td align='center' width=20>Rm</td>";
      print "</tr>";

      print "<tr>";
       print "<td width=20><a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'><img src='../images/ftp/folder.gif' border=0></a></td>";
       print "<td><a href='$PHP_SELF?path=".htmlentities(rawurlencode($path))."'>$current_name</a></td>";
       print "<td width=20 align=right>&nbsp;</td><td width=20 align=right>&nbsp;</td>";
      print "</tr>";

     $parent = dirname($path)."/";						// Parent directory

      print "<tr>";
       print "<td width=20><a href='$PHP_SELF?path=".htmlentities(rawurlencode($parent))."'><img src='../images/ftp/folder.gif' border=0></a></td>";
       print "<td><a href='$PHP_SELF?path=".htmlentities(rawurlencode($parent))."'>$parent_name</a></td>";
       print "<td width=20 align=right>&nbsp;</td><td width=20 align=right>&nbsp;</td>";
      print "</tr>";

     @sort($dirs);
     if ($dirs) foreach($dirs as $dir)
     {

      $ignore = false;

      foreach($ignore_directory_strings as $match)				// Ignore string
       if (stristr($dir, $match))
        $ignore = true;

      if (!$ignore)
      {
       print "<tr>";
        print "<td width=20><a href='$PHP_SELF?path=".htmlentities(rawurlencode($path.$dir))."/'><img src='../images/ftp/folder.gif' border=0 alt='Open'></a></td>";
        print "<td><a href='$PHP_SELF?path=".htmlentities(rawurlencode($path.$dir))."/'>".htmlentities($dir)."</a></td>";
        if ($allow_chmod) {
        print "<td width=110 align=right><a href='$PHP_SELF?action=chmod_prompt&path=".htmlentities(rawurlencode($path.$dir))."'>";
        printf("%o", (fileperms($home_dir.$path.$dir)) & 0777);
        print "</a></td>";
        }
        if ($allow_rename) print "<td width=20 align=center><a href='$PHP_SELF?action=rename_prompt&path=".htmlentities(rawurlencode($path.$dir))."/'><img src='../images/ftp/rename.gif' border=0 alt='Rename Directory'></a></td>";
        if ($allow_delete) print "<td width=20 align=center><a href='$PHP_SELF?action=delete_directory_verify&path=".htmlentities(rawurlencode($path.$dir))."/''><img src='../images/ftp/delete.gif' border=0 alt='Delete Directory'></a></td>";
       print "</tr>";
      }
     }

     print "</table>";
    print "&nbsp;</td>";

// ############## Show files ######################################################################################

    $icon = false;
    $text = false;

    print "<td width=500 valign=top>";

     print "<table width=100% class='list'>";
      print "<tr class='info'>";
       print "<td width=20>&nbsp;</td>";
       print "<td>Filename</td>";
       print "<td align='right' width=75>Filesize</td>";
       print "<td align='center' width=110>Last modified</td>";
       if ($allow_chmod) print "<td align='center' width=20>Ch</td>";
       if ($allow_rename) print "<td align='center' width=20>Rn</td>";
       if ($allow_download) print "<td align='center' width=20>Dl</td>";
       if ($allow_delete) print "<td align='center' width=20>Rm</td>";
      print "</tr>";

     $open = opendir($home_dir.$path);
     for($i=0;($file = readdir($open)) != false;$i++)
      if (is_file($home_dir.$path.$file))
       $files[$i] = $file;
     closedir($open);
     @sort($files);

     if ($files) foreach($files as $file)
     {
      $ext = strtolower(substr(strrchr($file, "."),1));

      $ignore = false;

      foreach($ignore_file_strings as $match)					// Ignore string
       if (stristr($file, $match))
        $ignore = true;

      foreach($ignore_file_extensions as $extension)				// Ignore extension
       if ($ext == $extension)
        $ignore = true;

      foreach($text_files as $extension)
      {
       if ($ext == $extension)
       {
        $icon = "<td width=20><a href='$PHP_SELF?action=edit&path=".htmlentities(rawurlencode($path.$file))."'><img src='../images/ftp/text.gif' border=0 alt='Edit'></a></td>";
        $text = "<td><a href='$PHP_SELF?action=edit&path=".htmlentities(rawurlencode($path.$file))."'>".htmlentities($file)."</a></td>";
       }
      }
      foreach($image_files as $extension)
      {
       if ($ext == $extension)
       {
        $icon = "<td width=20><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path.$file))."'><img src='../images/ftp/image2.gif' border=0 alt='View'></a></td>";
        $text = "<td><a href='$PHP_SELF?action=view&path=".htmlentities(rawurlencode($path.$file))."'>".htmlentities($file)."</a></td>";
       }
      }
      foreach($archive_files as $extension)
      {
       if ($ext == $extension)
        $icon = "<td width=20><img src='../images/ftp/compressed.gif' alt='Archive'></a></td>";
      }
      foreach($sound_files as $extension)
      {
       if ($ext == $extension)
        $icon = "<td width=20><img src='../images/ftp/sound2.gif' alt='Sound'></a></td>";
      }
      foreach($binary_files as $extension)
      {
       if ($ext == $extension)
        $icon = "<td width=20><img src='../images/ftp/binary.gif' alt='Binary'></a></td>";
      }

      if (!$ignore)
      {
       print "<tr>";

        if ($icon) print $icon;
        else print "<td width=20><img src='../images/ftp/unknown.gif' alt='Unknown'></td>";
        if ($text) print $text;
        else print "<td>".htmlentities($file)."</td>";

        print "<td width=75 align=right>";
         $filesize = filesize($home_dir.$path.$file);
         if ($filesize >= 1073741824) print number_format($filesize/1024/1024/1024, 2, ',', '.')."&nbsp;GB";
         else if ($filesize >= 1048576) print number_format($filesize/1024/1024, 2, ',', '.')."&nbsp;MB";
         else if ($filesize >= 1024) print number_format($filesize/1024, 2, ',', '.')."&nbsp;KB";
         else print number_format($filesize, 0, ',', '.')."&nbsp;B";
        print "</td>";

        $modified = date("H:i d-m-Y",filemtime($home_dir.$path.$file));
        print "<td width=110 align=right>$modified</td>";
        if ($allow_chmod) {
        print "<td width=110 align=right><a href='$PHP_SELF?action=chmod_prompt&path=".htmlentities(rawurlencode($path.$file))."'>";
        printf("%o", (fileperms($home_dir.$path.$file)) & 0777);
        print "</a></td>";
        }
        if ($allow_rename) print "<td width=20 align='center'><a href='$PHP_SELF?action=rename_prompt&path=".htmlentities(rawurlencode($path.$file))."'><img src='../images/ftp/rename.gif' border=0 alt='Rename File'></a></td>";
        if ($allow_download) print "<td width=20 align='center'><a href='$PHP_SELF?action=download_verify&path=".htmlentities(rawurlencode($path.$file))."'><img src='../images/ftp/download.gif' border=0 alt='Download File'></a></td>";
        if ($allow_delete) print "<td width=20 align='center'><a href='$PHP_SELF?action=delete_file_verify&path=".htmlentities(rawurlencode($path.$file))."'><img src='../images/ftp/delete.gif' border=0 alt='Delete File'></a></td>";
       print "</tr>";
      }
     $icon = false;
     $text = false;
     }
   print "</table>";
  print "&nbsp;</td></tr></table>";
 }

// ################################################################################################################
// ############## Error ###########################################################################################
// ################################################################################################################

 else
 {
  print "<a href='$PHP_SELF?path='>.:Back:.</a><br><br>";
  print "<b>ERROR:</b> Unable to open the specified path.<br><br>";
  print "<font color='#CC0000'>".$home_dir.$path."</font>";
 }
}

// ################################################################################################################
// ############## Login ###########################################################################################
// ################################################################################################################

else
{
 print "<font class='bold'>Login system:</font><br><br>";
 print "<table>";
 print "<form action='$PHP_SELF' method=post>";
  print "<tr>";
   print "<td>Username:</td>";
   print "<td><input name='cookie_username' size=20></td>";
  print "</tr>";
  print "<tr>";
   print "<td>Password:</td>";
   print "<td><input type='password' name='cookie_password' size=20></td>";
  print "</tr>";
  print "<tr>";
   print "<td>&nbsp;</td>";
   print "<td><input type=submit value=Enter></td>";
  print "</tr>";
 print "</table></form>";
}
print "<head>";
print "<title>.: vB FTP $version - by Dr. Erwin Loh :.</title>";
print "<link rel='stylesheet' href='../cp.css' type='text/css'>";
print "</head>";
print "<br><br>";
print "<div class='line_bottom'>&nbsp;</div>";
print "<center>";
print "vB FTP $version brought to you by Dr. Erwin Loh";
print "</center><br><br><br>";

?>
