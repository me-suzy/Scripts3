<?
// Set this to the name of the users database table
$tablename="strictCJ_USERS";

// Set this to the name of hourly stats datase table
$thour="strictCJ_HOUR";

// Set this to the name of daily stats datase table
$tday="strictCJ_DAY";

// you site domain beginning with a period (dot)
$site_cookie_domain = ".yourdomain.com";

// MAIN path where your html index file, index.php and out.php are located
// This path should contain scj directory also (no trailing slash)
$index_path = "/your/path/to/yourdomain.com";

//DO NOT TOUCH ANYTHING BELOW THIS!!!!
//DO NOT TOUCH ANYTHING BELOW THIS!!!!
//DO NOT TOUCH ANYTHING BELOW THIS!!!!
$dbconnect = $index_path."/scj/admin/dbconnect.inc.php";
$outfile = $index_path."/scj/data/outgoing.dat";
$outsize=10;
$uniquetime=36400;
?>