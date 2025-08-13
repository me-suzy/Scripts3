<?#//v.1.0.0

		#///////////////////////////////////////////////////////
		#//  COPYRIGHT 2002 PHPAuction.org ALL RIGHTS RESERVED//
		#//  For Source code for the GPL version go to        //
		#//  http://phpauction.org and download               //
		#//  Supplied by CyKuH [WTN]                          //
		#///////////////////////////////////////////////////////


	  include "./includes/messages.inc.php";
	  include "./includes/config.inc.php";

		if($HTTP_POST_VARS[action] == "upload")
		{
		    #// Process file
		$starts = date ("YmdHis");
		$query = "SELECT * FROM PHPAUCTIONPROPLUS_users where nick='$HTTP_SESSION_VARS[PHPAUCTION_LOGGED_IN_USERNAME]'";
		$result = mysql_query($query);
			if (!$result)
			{
				MySQLError($query);
				exit;
			}
		$user_id = mysql_result ($result,0,"id");
		$new_file = "$image_upload_path$user_id.txt";

			if ( file_exists($new_file) )
				unlink ($new_file);
				copy("$file", "$new_file");

		$fcontents = file ($new_file);
		unlink ($new_file);

		while (list ($line_num, $line) = each ($fcontents)) {

		$auc_id = md5(uniqid(rand()));
		$tok = strtok($line,"\t");
		$title = $tok;
		$tok = strtok("\t");
		$desc = $tok;
		$tok = strtok("\t");
		$cat = $tok;
		$tok = strtok("\t");
		$price = $tok;
		$tok = strtok("\t");
		$res_price = $tok;
		$tok = strtok("\t");
		$auc_type = $tok;
		$tok = strtok("\t");
		$incr = $tok;
		$tok = strtok("\t");
		$loc = $tok;
		$tok = strtok("\t");
		$zip = $tok;
		$tok = strtok("\t");
		$ship_exp = $tok;
		$tok = strtok("\t");
		$ship = $tok;
		$tok = strtok("\t");
		$quant = $tok;

		#// Insert into database with closed auction sign
if ($ship=="2") { $ship=0; }

if (($user_id) && ($title))
{
	$query = "INSERT INTO PHPAUCTIONPROPLUS_auctions values ('$auc_id',
	'$user_id',
	'$title',
	'$starts',
	'$desc',
	'',
	'$cat',
	'$price',
	'$res_price',
	'',
	'$auc_type',
	'',
	'$incr',
	'$loc',
	'$zip',
	'$ship_exp',
	'',
	'$ship',
	'$starts',
	'',
	'1',
	'',
	'$quant',
	'8')";
		$result = mysql_query($query);
		if (!$result)
		{
			MySQLError($query);
			exit;
		}
		$MSG = $MSG_737;
									}

		}

			include "header.php";
			include "templates/template_bulkupload_php.html";
			include "footer.php";

}


		if(!isset($HTTP_POST_VARS[action]) || ($HTTP_POST_VARS[ACTION] == "upload" && isset($ERR)))
		{
			include "header.php";
			include "templates/template_bulkupload_php.html";
			include "footer.php";
    }
?>
