<?
//How this script works:
//	Username and password are checked for authenticity. 
//		If success:
//			Generate a MD5 string from TIME()
//			Store that string in the database for that user
//			Set that username, password, and string as a cookie on user's machine
//		If failure:
//			Log username and IP address to a file

//During subsequent page loads:
//		Retrieve data stored in cookie
//		Match all three fields stored in cookie with database info.
//			If success:
//				Page load may continue
//			Otherwise user is presented with login form

//New user query: "insert into acl (username, password) VALUES ('theusername', encrypt('thepassword','theusername'));"
//Change password query: "update acl set password=encrypt('mypass','theusername') where username='theusername';"

// Database settings .. these must work!!
$db_hostname     = 'localhost';						//Server where MySQL is running.
$db_user         = 'john';						//Username to connect with.
$db_pass         = 'yourpass';						//Password to connect with.

define( "DATABASE", "your_database" );					//Database name where table 'acl' is located.

//Logging defines. Comment out the following two lines for no logging.
define( "BASE_DIR", "/usr/local/myappdir" ); 		//Your site's base directory (outside of docroot)
define( "AUTH_LOG", BASE_DIR . "/logs/auth_log" );	//Filename/subdirectory of logfile. Make sure the file 
							//  exists and is writeable by the owner of your webserver
							//  process. Usually 'nobody'.

//define( "IMAGE", "images/ourimage.jpg" );			//Image for the title page. Comment out the line for none.
define( "TITLE", "Please Login" );				//Title for the login page.
define( "EXPIRE", 14400 );					//Seconds until the cookie expires.

$bg_color        = '#FFFFFF';
$text_color      = '#000000';
$link_color      = '#BC80C3';
$vlink_color     = '#BC80C3';
$alink_color     = '#9d9d9d';


function DisplayLoginForm ($err_string) {
	require('htmldoc.inc');

	global $THIS_URL;

	global $bg_color;
	global $text_color;
	global $link_color;
	global $vlink_color;
	global $alink_color;

	$html = new htmldoc();
	$html->printheader( $bg_color, $text_color, $link_color, $vlink_color, $alink_color, TITLE );

	?>
		<CENTER>
		<? if (defined("IMAGE")) { ?>
		<IMG SRC="<? echo $root_url . IMAGE?>" BORDER="0">
		<? } ?>
		<BR><BR>
		<FONT SIZE=+1 COLOR="#FF0000"><? echo $err_string ?></FONT>
		<FORM NAME=login ACTION=<? echo $THIS_URL ?> METHOD=post>
		<TABLE BORDER=0>
		<TR>
		<TD><B>Username:</B></TD>
		<TD><INPUT NAME="username" TYPE="text" SIZE="10"></TD>
		</TR>
		<TR>
		<TD><B>Password:</B></TD>
		<TD><INPUT NAME="password" TYPE="password" SIZE="10"></TD>
		</TR>
		</TABLE>
		<BR>
		<INPUT TYPE="submit" VALUE="Log in">
		</FORM>

	<?
		$html->printfooter();
	exit;

}

function GenerateSecret ( $username, $encrypted_password ) {

	$md5str = MD5( TIME() );
	$cookie_val = "$username-$encrypted_password-$md5str";
	setcookie( "php_mini_auth", $cookie_val, time()+EXPIRE);
	$arg = "update acl set string='$md5str' where username='$username'";
	$row = mysql_db_query( DATABASE, $arg );

}

function AuthenticateUser ( $username, $password ) {
	
	global $ip;
	global $host;
	global $referer;

	$arg = "select password, 1 as auth from acl where username='$username' and password=encrypt('$password','$username')";
	$row = mysql_fetch_array(mysql_db_query( DATABASE, $arg ));

	if ($row[auth]) {
		if (defined( "AUTH_LOG" ))
		error_log( date("Ymd H:i:s") . " -- $ip -- Username: '$username' authenticated\n", 3, AUTH_LOG);
		GenerateSecret( $username, $row[password] );
		}
		else {
		if (defined( "AUTH_LOG" ))
			error_log( date("Ymd H:i:s") . " -- $ip -- Username: '$username' authentication failure\n", 3, AUTH_LOG);
			DisplayLoginForm( "Please log in .." );
		}
}

function AuthenticateCookie ( $cookie, $username, $password ) {
	
	$cookie_var = split("-", $cookie);
	$ck_username = $cookie_var[0];
	$ck_password = $cookie_var[1];
	$secret = $cookie_var[2];
	
	$arg = "select 1 as auth from acl where username='$ck_username' and password='$ck_password' and string='$secret'";
	$row = mysql_fetch_array(mysql_db_query( DATABASE, $arg ));
	
	if (!($row[auth]))
		AuthenticateUser ( $username, $password );
		else return $ck_username;
	
}

mysql_connect($db_hostname,$db_user,$db_pass) or 
   die("Unable to connect to the SQL server...");

$THIS_URL=getenv("SCRIPT_NAME");
$ip = getenv("REMOTE_ADDR");
$host = getenv("REMOTE_HOST");
$referer = getenv("REMOTE_REFERER");

if ($php_mini_auth)
   $username = AuthenticateCookie( $php_mini_auth, $username, $password );
	else
	if ($username)
		AuthenticateUser( $username, $password );
		else
			DisplayLoginForm( "Please log in ..." );

$result = mysql_db_query( DATABASE,"SELECT * from acl WHERE username='$username'");
$row=mysql_fetch_row($result);

?>
