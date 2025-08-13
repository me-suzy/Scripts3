<?PHP 

$domain		= "www.yourdomain.com"; // Your domain name (include www. if used BUT NOT http://) 
$server         	= "localhost"; // Your MySQL server address (usually 'localhost') 
$db_user        	= "dbuser"; // Your MySQL database username 
$db_pass        	= "dbpass"; // Your MySQL database password 
$database       	= "dbname"; // Your MySQL database name 
$currency		= "US Dollars"; // The currency that your affiliates will be paid in
$emailinfo                     = "your@email.com"; // Your email address
$yoursitename	= "Your Site Name"; // Your sites name
$language 		= "eng.php";  // language of control panel (only eng.php so far)

//-------------------------------------------------------------------------------
// DO NOT MODIFY ANYTHING BELOW THIS POINT UNLESS
// YOU KNOW WHAT YOU ARE DOING
//-------------------------------------------------------------------------------
$clientdate         	= (date ("Y-m-d")); // Do Not Touch 
$clienttime		= (date ("H:i:s")); // Do Not Touch 
$clientbrowser	= getenv("HTTP_USER_AGENT"); // Do Not Touch 
$clientip		= getenv("HTTP_CLIENT_IP"); // Do Not Touch 
$clienturl		= getenv("HTTP_REFERER"); // Do Not Touch 

// helper functions
function aff_check_security()
{
  if(!isset($_SESSION['aff_valid_user']) || $_SESSION['aff_valid_user']=='')
    return false;
  else
    return true;
}

function aff_admin_check_security()
{
  if(!isset($_SESSION['aff_valid_admin']) || $_SESSION['aff_valid_admin']=='')
    return false;
  else
    return true;
}

function aff_redirect($url, $time = 0)
{
  echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$time;URL=$url\">";
  echo "If you will be not redirected within few seconds click <a class=leftLink href=$url>".here.'</a>';
}
?> 