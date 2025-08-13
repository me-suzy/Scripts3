<? 

include_once("../base/define.inc.php");
include_once("../base/main.inc.php");

$dl = new TheDB();	
session_start();

$dl->connect() or die($dl->getError());
$dl->debug=false;

$cl = new TheCleaner();

if ( !session_is_registered("sladmin") && !$_POST["adminsubmit"]) {
?>
	<html>
	<head>
	<title>Admin Area</title>
	<link rel='stylesheet' href='<?=SL_ROOT_URL?>/base/html/Default/style.css'>
	</head>
	<body>
	<form method='post' action='index.php'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'>Admin Name</td><td><input type='text' name='adminname'></td>
		</tr><tr>
			<td width='20%'>Admin Password</td><td><input type='password' name='adminpassword'></td>	
		</tr><tr>
			<td colspan='2'><input type='submit' name='adminsubmit' value='log in'></td>
		</tr>
	</table>
	</form>
	</body>
	</html>		
<?
} else {
	$auth = $dl->select("*","sl18_users",array('urealname'=>$_POST["adminname"],'upass'=>$_POST["adminpassword"]));
	
	if( count( $auth ) == 0 || $auth[0]["uid"] != 1) {
?>

	<html>
	<head>
	<title>Admin Area</title>
	<link rel='stylesheet' href='<?=SL_ROOT_URL?>/base/html/Default/style.css'>
	</head>
	<body>
	<form method='post' action='index.php'>
	<table border='0' width='100%'>
		<tr>
			<td width='20%'>Admin Name</td><td><input type='text' name='adminname'></td>
		</tr><tr>
			<td width='20%'>Admin Password</td><td><input type='password' name='adminpassword'></td>	
		</tr><tr>
			<td colspan='2'><input type='submit' name='adminsubmit' value='log in'></td>
		</tr>
	</table>
	</form>
	</body>
	</html>	

<?
	
	} else {
		session_register("sladmin");
		$sladmin = 1;
?>
		<html>
		<head>
		<title>Admin Area</title>
		</head>

		<frameset cols="150,*" frameborder="NO" border="0" framespacing="0"> 
  		<frameset rows="*,150" frameborder="NO" border="0" framespacing="0"> 
  			<frame name="left" scrolling="NO" noresize src="contents.php">
  			<frame name="bottom" scrolling="NO" noresize src="http://www.iodesigns.co.uk/scripts/slv18/updates.html">
		</frameset>
  			<frame name="main" src="main.php">
		</frameset>

		</html>		
<?
	}
}

?>