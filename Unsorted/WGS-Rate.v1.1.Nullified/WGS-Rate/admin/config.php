<?

/*
 * $Id: config.php-dist,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

// No trailing slash on any of these

		$base_path = "C:/Documents and Settings/Administrator/Desktop/Server/date";
		$include_path = "C:/Documents and Settings/Administrator/Desktop/Server/date/include";
		$base_url = "http://localhost/server/date";


// MySQL connection information, if you are unsure contact your ISP

		$dbhost = "localhost";
		$dbuser = "root";
		$dbpasswd = "dbpass";
		$dbname = "date";


// If your ISP only allows you a single MySQL database you may
// need to change the following table names to avoid conflicts.

		$tb_sessions = "sessions";
		$tb_users = "users";
		$tb_ratings = "ratings";
		$tb_comments =	 "comments";
		$tb_pms = "pms";
		$tb_admin = "adminarea";
		$tb_settings = "settings";
		$tb_templates = "templates";
		$tb_image_types = "image_types";


// Your identity, as the administrator

		$owner_name = "Your Name";
		$owner_email = "webmaster@yourdomain.com";


// Debug level, "true" shows all errors, "false" only shows the
// most serious errors.  This is for development use mostly,
// feel free to leave it alone.

		$debug = false;


// WGS-RATE Version

		$version = "0.5.0";

/*
 * $Id: config.php-dist,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>