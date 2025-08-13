<html>
<head>
<link rel='stylesheet' href='base/html/Default/style.css'>
</head>
<body>
<table border='0' width='100%'>
	<tr>
		<td class='heavydis'>Storyline v1.8.0 Installer</td>
	</tr><tr>
		<td class='cleardis'>

<?
if ( $_POST["checkall"] ) {

	$string = '<?' . "\r\n";
	$string .= 'define("SL_ROOT_PATH","'.$_POST["rp"].'");' . "\r\n";
	$string .= 'define("SL_ROOT_URL","'.$_POST["ru"].'");' . "\r\n";
	$string .= 'define("SL_TITLE","'.$_POST["ti"].'");' . "\r\n";
	$string .= 'define("THE_HOST","'.$_POST["se"].'");' . "\r\n";
	$string .= 'define("USER_NAME","'.$_POST["un"].'");' . "\r\n";
	$string .= 'define("PASS_WORD","'.$_POST["ps"].'");' . "\r\n";
	$string .= 'define("DB_NAME","'.$_POST["db"].'");' . "\r\n";
	$string .= '?>';

	$fp = fopen("base/define.inc.php","w",0777);
	fwrite($fp, $string);
	fclose($fp);	

	include_once("base/define.inc.php");

	$checker = array ("index.php", "main.php", "register.php", "review.php", "story.php", "userpanel.php", "search.php", "authors.php", "latest.php","addto.php",
	"admin/index.php", "admin/contents.php", "admin/main.php",
	"admin/set/config.inc.php", "admin/set/language.inc.php", "admin/set/mancat.inc.php", "admin/set/mansub.inc.php", "admin/set/manstory.inc.php",
	"admin/set/style.inc.php","admin/set/layout.inc.php","admin/set/file.inc.php","admin/set/newsadd.inc.php","admin/set/newsedit.inc.php",
	"admin/set/newsdelete.inc.php", "admin/set/main.inc.php",
	"base/main.inc.php", "base/trans.php", "base/catsubcat.inc.php", "base/functions.func.php",
	"base/classes/dbal.class.php","base/classes/thecleaner.class.php","base/classes/display.class.php",
	"base/html/Default/index.tmpl.php","base/html/Default/banner.inc.php","base/html/Default/menu.inc.php","base/html/Default/banner.inc.php",
	"base/html/Default/footer.inc.php","base/html/Default/stats.inc.php","base/html/Default/copyright.inc.php","base/html/Default/style.css",
	"base/html/Default/images/default.gif","base/html/Default/images/1.gif","base/html/Default/images/pip.gif","base/html/Default/images/0.gif",
	"base/html/Default/images/arrowf.gif","base/html/Default/images/arrowb.gif","base/html/Default/images/arrowff.gif","base/html/Default/images/arrowbb.gif",
	"set/main/index.inc.php","set/main/register.inc.php","set/main/cats.inc.php","set/main/list.inc.php","set/main/story.inc.php","set/main/chapter.inc.php",
	"set/main/search.inc.php","set/main/authors.inc.php","set/main/latest.inc.php","set/main/reviews.inc.php","set/main/addto.inc.php",
	"set/userpanel/add.inc.php","set/userpanel/main.inc.php","set/userpanel/userpanel.inc.php","set/userpanel/profile.inc.php","set/userpanel/edit.inc.php",
	"set/userpanel/delete.inc.php","set/userpanel/recs.inc.php","set/userpanel/reviews.inc.php",
	"images/avatars/No-Avatar.gif");

	foreach( $checker as $is ) {
		if( file_exists(SL_ROOT_PATH."/".$is) ) {
			$f = "x";
		} else {
			print "<font color='red'>Cannot locate " . $is . "</font> - installation halted";
			break;
		}
	}

	$con = @mysql_connect(THE_HOST, USER_NAME , PASS_WORD);
	if( $con ) 
		$c = "x";

	$db = @mysql_select_db(DB_NAME);
	if ($db)
		$d = "x";

	if ($f == "x" && $c == "x" && $d == "x") {
		print "[+] All required files found <br>";
		print "[+] Connection valid <br>";
		print "[+] Database found<p>";

		print "<form method='post' action='installer.php'>";
		print "<input type='submit' value='insert tables' name='inserts'>";
		print "</form>";
	} else {
		if ($c != "x")
			print "Error: Cannot connect to dbase, make sure that your username, server and password information was correct<br>";
		if ($d != "x")
			print "Error: Cannot find database, check the name of the database<br>";
	}


} elseif ($_POST["inserts"] ) { 

	include("base/define.inc.php");
	$con = mysql_connect(THE_HOST, USER_NAME, PASS_WORD); 
	$db = mysql_select_db(DB_NAME); 

	$query = "CREATE TABLE sl18_category ( ";
	$query .= " caid int(11) NOT NULL auto_increment,";
	$query .= " caname varchar(255) NOT NULL default '',";
	$query .= " cadescript varchar(255) NOT NULL default '',";
	$query .= " capic varchar(255) NOT NULL default '',";
	$query .= " PRIMARY KEY (caid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_category" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_chapters (";
	$query .= "cid int(11) NOT NULL auto_increment,";
	$query .= "cname varchar(255) NOT NULL default '',";
	$query .= "cuid int(11) NOT NULL default '0',";
	$query .= "cbody text NOT NULL,";
	$query .= "cdate date NOT NULL default '0000-00-00',";
	$query .= "csid int(11) NOT NULL default '0',";
	$query .= "PRIMARY KEY  (cid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_chapters" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_news (";
	$query .= "nid int(11) NOT NULL auto_increment,";
	$query .= "nname varchar(255) NOT NULL default '',";
	$query .= "nbody text NOT NULL,";
	$query .= "ndate date NOT NULL default '0000-00-00',";
	$query .= "PRIMARY KEY  (nid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_news" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_rate (";
	$query .= "ratsid int(11) NOT NULL default '0',";
	$query .= "ratnovote int(11) NOT NULL default '0',";
	$query .= "rattotvote int(11) NOT NULL default '0'";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_rate" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_review (";
	$query .= "rid int(11) NOT NULL auto_increment,";
	$query .= "rbody text NOT NULL,";
	$query .= "ruser varchar(255) NOT NULL default '',";
	$query .= "rdate date NOT NULL default '0000-00-00',";
	$query .= "rsid int(11) NOT NULL default '0',";
	$query .= "remail varchar(255) NOT NULL default '',";
	$query .= "ruid int(11) NOT NULL default '0',";
	$query .= "PRIMARY KEY  (rid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_review " . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_stories (";
	$query .= "  sid int(11) NOT NULL auto_increment,";
	$query .= "  sname varchar(255) NOT NULL default '',";
	$query .= "  sdescrip varchar(255) NOT NULL default '',";
	$query .= "  suid int(11) NOT NULL default '0',";
	$query .= "  srating varchar(255) NOT NULL default '',";
	$query .= "  sdate date NOT NULL default '0000-00-00',";
	$query .= "  scid int(11) NOT NULL default '0',";
	$query .= "  sadd int(1) NOT NULL default '1',";
	$query .= "  stime bigint(14) NOT NULL default '0',";
	$query .= "  scdate date NOT NULL default '0000-00-00',";
	$query .= "  sthits int(11) NOT NULL default '0',";
	$query .= "  ssubid int(11) NOT NULL default '0',";
	$query .= "  PRIMARY KEY  (sid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_stories" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_subcategory (";
	$query .= "  subid int(11) NOT NULL auto_increment,";
	$query .= "  subname varchar(255) NOT NULL default '',";
	$query .= "  subcatid int(11) NOT NULL default '0',";
	$query .= "  PRIMARY KEY  (subid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_subcategory" . mysql_error() . "<br>");

	$query = "CREATE TABLE sl18_users (";
	$query .= "  uid int(11) NOT NULL auto_increment,";
	$query .= "  urealname varchar(255) NOT NULL default '',";
	$query .= "  upenname varchar(255) NOT NULL default '',";
	$query .= "  uemail varchar(255) NOT NULL default '',";
	$query .= "  uurl varchar(255) NOT NULL default '',";
	$query .= "  umsn varchar(255) NOT NULL default '',";
	$query .= "  uaol varchar(255) NOT NULL default '',";
	$query .= "  upass varchar(255) NOT NULL default '',";
	$query .= "  uicq varchar(255) NOT NULL default '',";
	$query .= "  uactive datetime NOT NULL default '0000-00-00 00:00:00',";
	$query .= "  ubio text NOT NULL,";
	$query .= "  ustart date NOT NULL default '0000-00-00',";
	$query .= "  urecs varchar(255) NOT NULL default '',";
	$query .= "  uava varchar(255) NOT NULL default '',";
	$query .= "  PRIMARY KEY  (uid)";
	$query .= ") TYPE=MyISAM;";
	$result = mysql_query($query) or print ("Error: Cannot create sl18_users" . mysql_error() . "<br>");

	if ( $result ) {
		print "Tables successfully inserted, create your admin account";
		print "<p>";
		print "<form method='post' action='installer.php'>";
		print "<table border='0' width='100%' cellspacing='4'>";
		print "<tr>";
		print "<td width='20%'>User Name</td> <td> <input type='text' name='username' maxlength='10'></td>";
		print "</tr><tr>";
		print "<td width='20%'>Pen Name</td> <td> <input type='text' name='penname' maxlength='10'></td>";
		print "</tr><tr>";
		print "<td width='20%'>Password</td> <td> <input type='text' name='password' maxlength='10'></td>";
		print "</tr><tr>";
		print "<td width='20%'>Email</td> <td> <input type='text' name='email'></td>";
		print "</tr><tr>";
		print "<td colspan='2'><input type='submit' value='create admin account' name='createacc'></td>";
		print "</tr>";
		print "</table>";
		print "</form>";
	}

} elseif ($_POST["createacc"] ) { 

	include_once("base/define.inc.php");
	include_once("base/classes/dbal.class.php");

	$dl = new TheDB();
	$dl->connect() or die($dl->getError());
	$dl->debug=false;

	$result = $dl->insert("sl18_users",array('urealname'=>$_POST["username"],
				'upenname'=>$_POST["penname"],
				'upass'=>$_POST["password"],
				'ustart'=>date("Y-m-d"),
				'uemail'=>$_POST["email"])) or print ("Error: Cannot create admin account" . $dl->getError());

	if( $result ) {
		print "Installation complete, remember to delete the installer.php file!<p>";
		print "You are being forwarded to your admin control panel<br>";
		print "<META HTTP-EQUIV='Refresh' CONTENT='2;URL=" . SL_ROOT_URL . "/admin/'>";
	}

} else {
	if( file_exists("base/define.inc.php") ) {
?>
	<form method='post' action='installer.php'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'> Site Title </td><td><input type='text' name='ti'></td>
		</tr><tr>
			<td width='20%'> Root Path </td>
			<td class='small'>
				<input type='text' name='rp' value='<?=$_SERVER['DOCUMENT_ROOT'].substr($_SERVER['PHP_SELF'],0,-14)?>'>
				 [no trailing forward slash!]
			</td>
		</tr><tr>
			<td width='20%'> Root Url </td>
			<td class='small'>
				<input type='text' name='ru' value='http://<?=$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,-14)?>'>
				 [no trailing forward slash!] 
			</td>
		</tr><tr>
			<td width='20%'> Server </td><td class='small'><input type='text' name='se' value='localhost'> [usual value]</td>
		</tr><tr>
			<td width='20%'> DB User Name </td><td><input type='text' name='un'></td>
		</tr><tr>
			<td width='20%'> DB Password </td><td><input type='text' name='ps'></td>
		</tr><tr>
			<td width='20%'> Database </td><td><input type='text' name='db'></td>
		</tr><tr>
			<td colspan='2'><input type='submit' value='next' name='checkall'></td>
		</tr>
	</table>

<?	
	} else {
		print "Error: The installer cannot locate the file 'define.inc.php', which should be located in the 'base' folder.";
	}
}
?>

		</td>
	</tr><tr>
		<td class='heavydis'><font class='small'>&copy; IO Designs 2002. By installing this script, you agree to the terms of the license included.</font></td>
	</tr>
</table>
</body>
</html>