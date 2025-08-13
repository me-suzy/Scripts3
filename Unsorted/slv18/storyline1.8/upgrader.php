<html>
<head>
<link rel='stylesheet' href='base/html/Default/style.css'>
</head>
<body>
<table border='0' width='100%'>
	<tr>
		<td class='heavydis'>Storyline v1.5 -> v1.8.0 upgrader</td>
	</tr><tr>
		<td class='cleardis'>
<?
$subber = "Miscellaneous";

include_once("base/define.inc.php");
include_once("base/classes/dbal.class.php");

$dl = new TheDB();
$dl->connect() or die($dl->getError());
$dl->debug=false;


if ($_POST["adid"]) {

if ($_POST["adid"] == 1) {
	$table = $dl->select("*","users");
	foreach($table as $row) {
		if ( $row["uid"] != 1 ) {
			$dl->insert("sl18_users", array(
				'uid'=>$row["uid"],
				'urealname'=>$row["urealname"],
				'upenname'=>$row["upenname"],
				'uurl'=>$row["uurl"],
				'umsn'=>$row["umsn"],
				'uaol'=>$row["uaol"],
				'upass'=>$row["upass"],
				'uicq'=>$row["uicq"],
				'ubio'=>$row["ubio"],
				'uemail'=>$row["uemail"],
				'ustart'=>date("Y-m-d"))
			);
		} else {
			$dl->update("sl18_users", array(
				'upenname'=>$row["upenname"],
				'uurl'=>$row["uurl"],
				'umsn'=>$row["umsn"],
				'uaol'=>$row["uaol"],
				'upass'=>$row["upass"],
				'uicq'=>$row["uicq"],
				'ubio'=>$row["ubio"]),

			array('uid'=>$_POST["adid"])
			);
		}
	}
}

if ($_POST["adid"] != 1) {
	$table = $dl->select("*","users",array('uid'=>$_POST["adid"]));
		$dl->update("sl18_users", array(
			'urealname'=>$table[0]["urealname"],
			'upenname'=>$table[0]["upenname"],
			'uurl'=>$table[0]["uurl"],
			'umsn'=>$table[0]["umsn"],
			'uaol'=>$table[0]["uaol"],
			'upass'=>$table[0]["upass"],
			'uicq'=>$table[0]["uicq"],
			'ubio'=>$table[0]["ubio"]),
		array('uid'=>1)
		);

	$table = $dl->select("*","users");
	foreach($table as $row) {
		if ( $row["uid"] != 1 && $row["uid"] != $_POST["adid"]) {
			$dl->insert("sl18_users", array(
				'uid'=>$row["uid"],
				'urealname'=>$row["urealname"],
				'upenname'=>$row["upenname"],
				'uurl'=>$row["uurl"],
				'umsn'=>$row["umsn"],
				'uaol'=>$row["uaol"],
				'upass'=>$row["upass"],
				'uicq'=>$row["uicq"],
				'ubio'=>$row["ubio"],
				'uemail'=>$row["uemail"],
				'ustart'=>date("Y-m-d"))
			);
		}
	}

	$table = $dl->select("*","users",array('uid'=>1));
	if( count( $table ) > 0 ) {
			$dl->insert("sl18_users", array(
				'urealname'=>$table[0]["urealname"],
				'upenname'=>$table[0]["upenname"],
				'uurl'=>$table[0]["uurl"],
				'umsn'=>$table[0]["umsn"],
				'uaol'=>$table[0]["uaol"],
				'upass'=>$table[0]["upass"],
				'uicq'=>$table[0]["uicq"],
				'ubio'=>$table[0]["ubio"],
				'uemail'=>$table[0]["uemail"],
				'ustart'=>date("Y-m-d"))
			);
	}
}

$table = $dl->select("*","sl18_users");
print "User table transferred: " . count( $table ) . " records ... <br>";

$table = $dl->select("*","category");

foreach($table as $row) {
	$dl->insert("sl18_category", array(
		'caid'=>$row["caid"],
		'caname'=>$row["caname"],
		'cadescript'=>$row["cadescript"],
		'capic'=>$row["capic"])
	);
}

print "Category table transferred: " . count( $table ) . " records ... <br>";

$table = $dl->select("*","sl18_category");

foreach($table as $row) {
	$dl->insert("sl18_subcategory", array(
		'subname'=>$subber,
		'subcatid'=>$row["caid"])
	);
}

print " 'Miscellanous' table added to each category  ... <br>";

$table = $dl->select("*","stories");

foreach($table as $row) {
	$tableb = $dl->select("sl18_subcategory.subid AS subid","sl18_subcategory",array('subcatid'=>$row["scid"]));
	$tablec = $dl->select("sl18_users.uid AS uid","sl18_users",array('urealname'=>$row["surealname"]));

	if( $row["srating"] == "U" )
		$row["srating"] = 1;
	elseif( $row["srating"] == "PG" )
		$row["srating"] = 2;
	elseif( $row["srating"] == "15" )
		$row["srating"] = 3;
	elseif( $row["srating"] == "18" )
		$row["srating"] = 4;
	elseif( $row["srating"] == "NC-17" )
		$row["srating"] = 5;
	else
		$row["srating"] = 1;		


		$dl->insert("sl18_stories", array(
			'sid'=>$row["sid"],
			'sname'=>$row["sname"],
			'sdescrip'=>$row["sdescrip"],
			'srating'=>$row["srating"],
			'sdate'=>$row["sdate"],
			'scid'=>$row["scid"],
			'sadd'=>$row["sadd"],
			'stime'=>$row["stime"],
			'suid'=>$tablec[0]["uid"],
			'scdate'=>$row["scdate"],
			'sthits'=>$row["sthits"],
			'ssubid'=>$tableb[0]["subid"])
		);
}

print "Story table transferred: " . count( $table ) . " records ... <br>";

$table = $dl->select("*","chapters");

foreach($table as $row) {
	$tableb = $dl->select("sl18_users.uid AS uid","sl18_users",array('urealname'=>$row["curealname"]));
	$row["cbody"] =str_replace("<P>", "\r\n\r\n" , $row["cbody"] );
	$row["cbody"] =str_replace("<p>", "\r\n\r\n" , $row["cbody"] );
	$row["cbody"] =str_replace("<br>", "\r\n" , $row["cbody"] );
	$row["cbody"] =str_replace("<BR>", "\r\n" , $row["cbody"] );
	$row["cbody"] =str_replace("<Br>", "\r\n" , $row["cbody"] );

	$dl->insert("sl18_chapters", array(
		'cid'=>$row["cid"],
		'cname'=>$row["cname"],
		'cbody'=>$row["cbody"],
		'cdate'=>$row["cdate"],
		'csid'=>$row["cid2"],
		'cuid'=>$tableb[0]["uid"])
	);
}

print "Chapters table transferred: " . count( $table ) . " records ... <br>";

$table = $dl->select("*","rate");

foreach($table as $row) {
	$dl->insert("sl18_rate", array(
		'ratsid'=>$row["ratsid"],
		'ratnovote'=>$row["ratnovote"],
		'rattotvote'=>$row["rattotvote"])
	);
}

print "Rate table transferred: " . count( $table ) . " records ... <br>";

$table = $dl->select("*","review");

foreach($table as $row) {
	$dl->insert("sl18_review", array(
		'rid'=>$row["rid"],
		'rbody'=>$row["rbody"],
		'ruser'=>$row["ruser"],
		'rdate'=>$row["rdate"],
		'rsid'=>$row["rsid"],
		'remail'=>$row["remail"])
	);
}

print "Review table transferred: " . count( $table ) . " records ... <br><br>";

print "Finished. You are being forwarded to your main page, delete this file once happy everything is transferred!<br>";
print "<META HTTP-EQUIV='Refresh' CONTENT='4;URL=" . SL_ROOT_URL . "'>";

} else {
?>
	<form method='post' action='upgrader.php'>
	Old Personal Account Number : <input type='text' name='adid'> [All details from this account will be transferred to the admin account]<br>
	<input type='submit' value='upgrade'>
	</form>
	<p>
	Once the installer is complete, you will have to enter the admin panel and edit the category picture information to include the full url of the picture.
	<p>
	Warning: If you have changed your account login details (username, penname, password), and your id number is not 1, this will reset them.
<?
}
?>
		</td>
	</tr><tr>
		<td class='heavydis'><font class='small'>&copy; IO Designs 2002. By using this script, you agree to the terms of the license included.</font></td>
	</tr>
</table>
</body>
</html>