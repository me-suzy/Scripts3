<?

/*
Do you code?  Did you code your own blog script (in any language)?  Join the Powered By Me webring at http://powered.usr-bin-mom.com -- the only webring for bloggers who wrote their own scripts!
*/

/*
The Webring Script was written by Michelle of http://usr-bin-mom.com.  Linkbacks are appreciated!
This script is released under the GPL.  Please read the enclosed license for more information!
*/


// Modify the following variables:
$database = '';				// your database name
$db_username = '';			// your database username
$db_password = '';			// your database password
$db_table = 'webring';			// the name you would like for the table

$admin_email = '';			// your email address
$admin_password = '';			// the password you want to use
$admin_name = '';			// the name you want to be used to sign outgoing emails

$ring_name = '';		// the name of your webring

// The following should be relative to the location of webring.php.  If you don't know what that means, use full urls:
$ring_url = 'http://yourdomain.com/webring';	// the FULL URL to the directory where webring.php is, without trailing slash
$index_php = 'index.php';			// the location of your main ring page
$join_php = 'index.php';			// the page you included your join form on
$queue_php = 'index.php';			// the page you included your queue list on
$members_php = 'index.php';			// the page you included your members list on

// Don't touch the following unless you know what you are doing!
mysql_connect ('localhost', $db_username, $db_password) ;
mysql_select_db ($database);

?>
