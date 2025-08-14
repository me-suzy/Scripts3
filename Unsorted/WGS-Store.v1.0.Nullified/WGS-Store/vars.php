<?

// VARS
$adminmail = 'you@you.com'; //admin's mail
$dblogin = 'login'; // login from database
$dbpass = 'pass'; // password from database
$db = 'db name'; // database's name
$adminpass = 'demo'; // password for admin area
$adminlogin = 'admin'; // login for admin area



// CONNECT MySQL

mysql_connect(localhost,$dblogin,$dbpass);
mysql_select_db($db);

// READ FILES

function file_reader($fileurl)
	{
		$file=fopen($fileurl,'r') or die("File Does'nt Exists");
		if (filesize($fileurl) > 0 )
		{
			//or die("$fileurl Could Not Able To Read The File");
			$contents=fread($file,filesize($fileurl));

			fclose($file);
			return $contents;
		}
	}

?>