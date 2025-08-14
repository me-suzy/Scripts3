<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: install.php,v 1.38 2002/06/05 12:57:23 zorg Exp $
#

$templates_repository="skin1_original";
$templates_directory="skin1";

error_reporting (E_ALL ^ E_NOTICE);
 set_time_limit(120);
set_magic_quotes_runtime(0);
umask(0);

 function FatalError ($mywarn)
 {
  echo "<font color=red>$mywarn</font>";
  echo "\n</body></html>";
  exit ();
 }

 function UpdatePGP () {
	if (!is_dir (".pgp")) {
		if (!mkdir (".pgp", 0755))
			FatalError ("Cannot create .pgp directory");
		#`cp -R .pgp.def/*.* .pgp/`;
		#`chmod 0600 .pgp/*`;
	}
 }

#
# This function copies directory tree from skin1_original to skin1
#

function CopyTemplates ($dir, $parent_dir) {

        global $templates_repository, $templates_directory;

        if(!$handle = opendir($dir)) return;

        while (false !== ($file = readdir($handle)))
            if (is_file($dir."/".$file)) {
                if (!@copy ("$templates_repository$parent_dir/$file","$templates_directory$parent_dir/$file"))
				echo "Copying $templates_repository$parent_dir/$file to $templates_directory$parent_dir/$file <b><font color=red>FAILED!</font></b>";
				flush();
            } elseif (is_dir($dir."/".$file) && $file != "." && $file != "..") {
				echo "Creating directory $templates_directory$parent_dir/$file ";
				if(!file_exists("$templates_directory$parent_dir/$file")) {
                	if(!@mkdir ("$templates_directory$parent_dir/$file",0777)) 
						echo "- <b><font color=red>FAILED!</font></b>";
				} else {
						echo "- <b>Already exists</b>";
				}
				echo "<br>\n";
				flush();
                CopyTemplates ($dir."/".$file,$parent_dir."/".$file);
            }
;
        closedir($handle);

}

 
 function ChangeConfig ()
 {
  global $mysqlhost, $mysqluser, $mysqlpass, $mysqlbase, $xcart_http_host, $xcart_https_host, $xcart_web_dir;
global $HTTP_SERVER_VARS;

  $current_directory = str_replace("\\", "/", realpath("."));
  $allfile = "";

  // Write data to config.php
  if (!($fp=fopen ("config.php", "r+")))
   FatalError ("Can't open file \"config.php\" for reading\\writing");
  while (!feof($fp))
  {
   $buffer=fgets ($fp, 4096);

   ereg('^\$sql_host *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$mysqlhost\";",$buffer);
   ereg('^\$sql_user *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$mysqluser\";",$buffer);
   ereg('^\$sql_db *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$mysqlbase\";",$buffer);
   ereg('^\$sql_password *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$mysqlpass\";",$buffer);
   ereg('^\$xcart_http_host *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$xcart_http_host\";",$buffer);
   ereg('^\$xcart_https_host *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$xcart_https_host\";",$buffer);
   ereg('^\$xcart_web_dir *=', $buffer) and $buffer=ereg_replace('=.*;',"=\"$xcart_web_dir\";",$buffer);
   $allfile .= $buffer;
  }
  ftruncate ($fp, 0);
  rewind ($fp);
  fwrite ($fp, $allfile);
  fclose ($fp);
 } 
 function QueryUpload ($mysql_host, $mysql_user, $mysql_db, $mysql_password, $filename)
 {
  mysql_connect($mysql_host, $mysql_user, $mysql_password) || FatalError("Could not connect to SQL db");
  mysql_select_db($mysql_db) || FatalError("mysql_select_db :: failure");

  $fp = fopen($filename, "rb");
  $command = "";
  echo "Please wait...<br>\n";
  while (!feof($fp)) {
   $c = fgets($fp, 1500000);
   $c = chop($c);
   $c = ereg_replace("^[ \t]*#.*", "", $c);
   $command.=$c;
   if (ereg(";$",$command)) {
    $command=ereg_replace(";$","",$command);
        if (ereg("CREATE TABLE ", $command)) {
                $table_name = ereg_replace(" .*$", "", eregi_replace("^.*CREATE TABLE ", "", $command));
                echo "Creating table: [$table_name] ...<br>\n";
        flush();
        }
    mysql_query($command);
    $myerr = mysql_error ();
        if (!empty($myerr))
        {
     echo $myerr;
         break;
        } 
    $command="";
    flush();
   }
  }
  mysql_close ();
  fclose($fp);
  if (!empty($myerr))
     return false;
  return true;   
 }
?>
<html>
<head>
 <title>X-Cart Installation Wizard</title>
 <script language="JavaScript">
	loaded=false;
  function CheckFields1 ()
  {
   if (document.form1.mysqlhost.value == "")
   {
    alert ("You must enter MySQL host name");
	return false;
   }
   if (document.form1.mysqluser.value == "")
   {
	alert ("You must enter MySQL user name");
	return false;
   }
   if (document.form1.mysqlbase.value == "")
   {
    alert ("You must enter MySQL database name");
    return false;
   }
  }
 </script>
</head>
<?
 $clr1 = "bgcolor=\"#dddddd\"";
 $clr2 = "bgcolor=\"#eeeeee\"";
?>
<body bgcolor="white" style="margin-left: 50px; margin-right: 50px" onLoad="loaded=true">
<center>
<h2><FONT color="darkblue">X-Cart Installation Wizard</FONT></h2>
</center>
<?
if ($action=='agreed') {
	$action="enter";
	$mysqlhost="localhost";
	$mysqluser="";
	$mysqlpass="";
	$mysqlbase="xcart";
}
?>
<? if (empty ($action)) { ?>
<FORM method=POST action='install.php'>
<INPUT type=hidden name=action value='agreed'>
<CENTER>
<TEXTAREA cols=80 rows=22>
<? require "./COPYRIGHT" ?>
</TEXTAREA>
<P>
<B><FONT color="darkgreen">You must agree with our License Agreement in order to proceed.</FONT></B>
<P>
<INPUT type=submit value='I agree'>&nbsp;&nbsp;<INPUT type=button value='I do not agree' onClick='javascript:alert("Dear customer.\nIt is neccessary to agree with the License Agreement in order to use this software.\nOtherwise this software will be considered as illegal copy.")'>
</FORM>
</CENTER>
<? } ?>
<? if ($action=="enter") : ?>
<P><B><FONT color="darkgreen">Thank you for choosing X-Cart. This wizard will provide you with the installation instructions and will handle most of the installation tasks for you.</FONT></B></P>
Before the installation starts, please ensure that you have properly configured file access permissions (UNIX only):<p>
<FONT color="darkblue">
&gt; chmod 777 .<BR>
&gt; chmod 777 admin/newsletter<br>
&gt; chmod 666 config.php<br>
</FONT>
<p>The Installation Wizard needs to know your web server details and MySQL database information:<p>
<? if ("4.0.6" > phpversion()) echo "<br><font color=red>WARNING!!! There is PHP version ".phpversion()." installed on your server. X-Cart requires PHP 4.0.6 or later.<br>Please setup the latest PHP version from <a href=\"http://www.php.net\">http://www.php.net</a>."; ?>
</p>
<form name="form1" method=post action="install.php" onsubmit="return CheckFields1 ()">
<input type=hidden name=action value="check">
<table width="100%" border=0>
<tr <? echo $clr1 ?>><td><b>Server host name</b><br>
Host name of your server (i.e. www.mywebstore.com)
</td><td><input type="text" name=xcart_http_host size=30 value="<? echo $HTTP_HOST; ?>"></td></tr>
<tr <? echo $clr1 ?>><td><b>Secure server host name</b><br>
Host name of your secure (HTTPS) server (i.e. secure.mywebstore.com)
</td><td><input type="text" name=xcart_https_host size=30 value="<? echo $HTTP_HOST; ?>"></td></tr>
<tr <? echo $clr1 ?>><td><b>X-Cart web directory</b><br>
Web directory where X-Cart files is located (i.e. /xcart)
</td><td><input type="text" name=xcart_web_dir size=30 value="<? echo str_replace("/install.php","",$SCRIPT_NAME); ?>"></td></tr>
<tr <? echo $clr1 ?>><td><b>MySQL host name</b><br>
Host name of MySQL server. It can be host name or IP address.
</td><td><input type="text" name=mysqlhost size=30 value="<? echo $mysqlhost; ?>"></td></tr>
<tr <? echo $clr2 ?>><td><b>MySQL user name</b><br>
The name of the MySQL user.
</td><td><input name=mysqluser size=30 type=text value="<? echo $mysqluser; ?>"></td></tr>
<tr <? echo $clr1 ?>><td><b>MySQL database name</b><br>
The name of the database you connect to.
</td><td><input name=mysqlbase size=30 type=text value="<? echo $mysqlbase; ?>"></td></tr>
<tr <? echo $clr2 ?>><td><b>MySQL password</b><br>
Which password to use for MySQL.
</td><td><input name=mysqlpass size=30 type=text value="<? echo $mysqlpass; ?>"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=" Continue... "></td></tr>
</table>
</form>
<? elseif ($action=="check") : ?>

<?
// Now try to check if there is already database named $mysqlbase
$mylink = @mysql_connect ($mysqlhost, $mysqluser, $mysqlpass) or FatalError ("Can't connect to the MySQL server. Press 'BACK' browser button and check again");
if (!@mysql_select_db ($mysqlbase))
 FatalError ("Installer couldn't find database \"$mysqlbase\". You should ask your system administrator to create one or choose another name");
if (!is_writable("config.php"))
 FatalError ("Cannot open file \"config.php\" for writing. You should set UNIX permissions for file \"config.php\" to 0666");
 $mystring = "";
 $first = true;
   $res = @mysql_list_tables ($mysqlbase);
  while ($row=@mysql_fetch_row($res)) {
	   $ctable=$row[0];
		if ($ctable=="products") 
		 $mystring .= "<p><font color=red>Warning. The Installation Wizard has found existing X-Cart tables in your database. If you continue, they will be deleted.</font><br>";

  }
 @mysql_close ($mylink);
?>

<form action="install.php" method=post>
<table width="100%" border=0>
<tr <? echo $clr1 ?>><td><b>Server host name</b><br>
Host name of your server (i.e. www.mywebstore.com)
</td><td><? echo $xcart_http_host ?></td></tr>
<tr <? echo $clr1 ?>><td><b>Secure server host name</b><br>
Host name of your secure (HTTPS) server (i.e. secure.mywebstore.com)
</td><td><? echo $xcart_https_host ?></td></tr>
<tr <? echo $clr1 ?>><td><b>X-Cart web directory</b><br>
Web directory where X-Cart files is located (i.e. /xcart)
</td><td><? echo $xcart_web_dir ?></td></tr>
<tr <? echo $clr1 ?>><td width=50%><b>MySQL host name</b><br>
Host name of MySQL server. It may be host name or ip number.
</td><td> <? echo $mysqlhost; ?></td></tr>
<tr <? echo $clr2 ?>><td><b>MySQL user name</b><br>
The name of the MySQL user.
</td><td> <? echo $mysqluser; ?></td></tr>
<tr <? echo $clr1 ?>><td><b>MySQL database name</b><br>
Database name You connect to.
</td><td> <? echo $mysqlbase; ?></td></tr>
<tr <? echo $clr2 ?>><td><b>MySQL password</b><br>
What password to use to connect.
</td><td> <? echo $mysqlpass; ?></td></tr>
<tr><td colspan=2>
<br>
<? if (!empty($mystring))
{
 echo $mystring;
}
echo "<BR>Push the button below to begin the installation:";
?>
</td></tr>
<tr><td colspan=2 align=center><input type=submit value=" Begin installation "></td></tr>
</table>

<input type=hidden name="mysqlhost" value="<? echo $mysqlhost; ?>">
<input type=hidden name="mysqluser" value="<? echo $mysqluser; ?>">
<input type=hidden name="mysqlpass" value="<? echo $mysqlpass; ?>">
<input type=hidden name="mysqlbase" value="<? echo $mysqlbase; ?>">
<input type=hidden name="xcart_http_host" value="<? echo $xcart_http_host; ?>">
<input type=hidden name="xcart_https_host" value="<? echo $xcart_https_host; ?>">
<input type=hidden name="xcart_web_dir" value="<? echo $xcart_web_dir; ?>">
<input type=hidden name="action" value="go">
</form>

<? elseif ($action=="go") : ?>
<?
$mylink = @mysql_connect ($mysqlhost, $mysqluser, $mysqlpass) or FatalError ("Cannot connect to MySQL server. This is unexpected error, so please start installation again.");
if (!@mysql_select_db ($mysqlbase))
 FatalError ("Couldn't find database \"$mysqlbase\". This is unexpected error, so please start installation again.");
 if (!is_writable("config.php"))
  FatalError ("Cannot open file \"config.php\" for writing. This is unexpected error, so please start installation again.");
  
#
# Create empty  directories
#
echo "<b>Creating directories...</b><br>\n";
if(!file_exists("templates_c"))
	if(!@mkdir("templates_c", 0777)) FatalError ("Unable to create ./templates_c directory. Please check permissions");
if(!file_exists($templates_directory))
	if(!@mkdir($templates_directory, 0777)) FatalError ("Unable to create ./$templates_directory directory. Please check permissions");
if(!file_exists("files"))
	if(!@mkdir("files", 0777)) FatalError ("Unable to create ./files directory. Please check permissions");

#
# Copy skin1_original tree
#
echo "<b>Copying templates...</b><br>\n";
CopyTemplates ($templates_repository,"");
echo "\n<p>\n";

?>
<script type="text/javascript">
function refresh() {
    window.scroll(0,100000);

	if(loaded==false)
		setTimeout('refresh()',1);
}
setTimeout('refresh()',1);
</script>

<?

// Now it's time to create bases from dump

 UpdatePGP ();

echo "<b>Creating tables...</b><br>\n";

 if (QueryUpload ($mysqlhost, $mysqluser, $mysqlbase, $mysqlpass, "sql/dbclear.sql") && QueryUpload ($mysqlhost, $mysqluser, $mysqlbase, $mysqlpass, "sql/xcart.sql"))
 {
  echo "<font color=\"darkgreen\"><h3>Database successfully imported.</h3>";
  echo "<h3>Installation complete.</h3></font>";
  ChangeConfig ();
?>
<h3>Before you proceed to using X-Cart, you must remove install.php file and make sure that you setup secure file permissions:<br>
chmod 644 config.php<br>
</h3>
<BR>
X-Cart has been successfully installed at the following URLs:<BR>
<A href="customer/home.php">CUSTOMER FRONTEND</A><BR><BR>
<a href="admin/home.php">ADMIN BACKOFFICE</a>(you only need it in the PRO mode):<br>
Username: admin<br>
Password: admin<br>
<p>
<a href="provider/home.php">BACKOFFICE FOR PRODUCT PROVIDERS</a>(if you have a Gold package, the provider interface is merged with the admin backoffice and includes admin controls):<br>
Username: provider (for PRO edition), master (for GOLD edition)<br>
Password: provider (for PRO edition), master (for GOLD edition)<br>
<br>
<?
 }
 else
 {
  echo "<h3>Fatal error occured while importing database. Please, check all again and start installation. Maybe you have not enought rights to that database</h3>";
 }
?>

<? endif; ?>
<hr size=1 noshade>
<font size=1>Copyright 2001-2002 <a href="http://www.x-cart.com">X-Cart.com</a><br>Copyright 2001-2002 <a href="http://www.rrfinc.com" target=_blank><font class="TableCenterSmallText">RRF.ru development</font></a></font>

</body>
</html>
