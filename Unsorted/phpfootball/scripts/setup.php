<?php 
/*
***************************************************************************
Parameters :

$test 1,0
$write 1,0
$create 1,0
$insert 1,0
***************************************************************************
*/
?>

<?php include("inc.functions.php"); ?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>

<?php
$link = mysql_connect($dbhost,$dbuser,$dbpass);
$select = mysql_select_db("$dbname",$link);
$list = mysql_num_rows(mysql_list_tables("$dbname",$link));
?>
<br>
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td class=tdark><center>Configuration test result</center></td></tr>
<tr><td>
<form name="form" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?test=1"; ?>">
<textarea name="textfield" rows=4 cols="60%" wrap="VIRTUAL">
<?php
if($test){
if ($link != ""){ echo "Sucesfully conected to mysql \n"; }
else { echo "Failed to connect to $dbhost (Update configuration)\n"; }
if ($select){ echo "Sucesfully opened database \n"; } 
else { echo "Failed to open $dbname (Create database)\n"; }
if ($list){ echo "Sucesfully read tables \n"; }
else { echo "Failed to read tables (Insert tables)\n"; }
}
?>
</textarea>
<br><center><input type="submit" value="Test configuration" class="submit"></center>
</form>
</td></tr>
</table>



<?php
$fname = "inc.config.php";
$mesg = "Read configuration from $fname";

if($write){
//make sure file is writable if not chmod it
do_chmod($fname);
$mesg = "Updated Configuration to $fname";
	if($_POST["dbname"] && $_POST["dbhost"] && $_POST["dbuser"] && $_POST["dbpass"]){
	$file_pointer = fopen($fname, "w");
	fwrite($file_pointer,"<?
\$dbname=\"" . $_POST["dbname"] . "\";
\$dbhost=\"" . $_POST["dbhost"] . "\";
\$dbuser=\"" . $_POST["dbuser"] . "\";
\$dbpass=\"" . $_POST["dbpass"] . "\";
?>");
	fclose($file_pointer);
	}else{ $mesg = "Fill all fields of the form"; }
}
?>
<br>
<form name="form" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?write=1"; ?>">
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td colspan=2 class=tdark><center><?php echo $mesg; ?></center></td></tr>
<tr width=60% ><td class=tddd>Database </td><td class=td><center><input size=60% type=text name=dbname class=input value="<?php echo $dbname; ?>"></center></td></tr>
<tr width=60% ><td class=tddd>Hostname </td><td class=td><center><input size=60% type=text name=dbhost class=input value="<?php echo $dbhost; ?>"></center></td></tr>
<tr width=60% ><td class=tddd>Username </td><td class=td><center><input size=60% type=text name=dbuser class=input value="<?php echo $dbuser; ?>"></center></td></tr>
<tr width=60% ><td class=tddd>Password </td><td class=td><center><input size=60% type=text name=dbpass class=input value="<?php echo $dbpass; ?>"></center></td></tr>
<tr><td colspan=2><br><center><input type="submit" value="Update Configuration" class="submit"></center></td></tr>
</table>
</form>

<?php
$db_list = mysql_list_dbs ($link);
$mesg = "Read databases on $dbhost";
if($create && !$select){
$mesg = "Created database $dbname on $dbhost";
mysql_create_db ("$dbname");
}
?>
<br>
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td colspan=2 class=tdark><center><?php echo $mesg; ?></center></td></tr>
<?php
while ($row = mysql_fetch_object($db_list)) {
    echo "<tr width=60% ><td width=25% class=tddd>$dbhost.$row->Database</td><td width=75% class=td>$row->Database</td></tr>";
}
?>
<tr><td colspan=2><br><center>
<form name="form" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?create=1"; ?>">
<input type="submit" value="Create Database" class="submit">
</form>
</center></td></tr>
</table>

<?php
$tb_list = mysql_list_tables ("$dbname",$link);
$t = 0;
$mesg = "Read tables from $dbname";
if($insert && !$list){
$mesg = "Inserted tables into $dbname";
	$filename = 'phpfootball.sql';     
	do_import($filename);
}
?>
<br>
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td colspan=2 class=tdark><center><?php echo $mesg; ?></center></td></tr>
<?php
while ($t < mysql_num_rows($tb_list)) {
    $tb_names[$t] = mysql_tablename ($tb_list, $t);
    echo "<tr width=60% ><td width=25% class=tddd>$dbname.$tb_names[$t]</td><td width=75% class=td>$tb_names[$t]</td></tr>";
    $t++;
}
?>
<tr><td colspan=2><br><center>
<form name="form" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?insert=1"; ?>">
<input type="submit" value="Insert tables" class="submit">
</form>
</center></td></tr>
</table>




<?php
$mesg_ad = "Showing administration accounts";
if($admin){
$mesg_ad = "Sucesfully created administration account";
	if($_POST["suiteuser"] && $_POST["suitepass"]){
	$suitepass = md5($suitepass);
	$query = "INSERT INTO Accounts (Username,Password,Userlevel) VALUES ('$suiteuser','$suitepass','admin')";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	}else{ $mesg_ad = "Fill all fields of the form"; }
}
?>
<br>
<form name="form" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?admin=1"; ?>">
<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr><td colspan=2 class=tdark><center><?php echo $mesg_ad; ?></center></td></tr>
<?php
$query = "SELECT * FROM Accounts WHERE Userlevel = 'admin'";
$result = mysql_query($query) or die ("Failed read admins<br>Debug info: $query");
while ($line = mysql_fetch_assoc($result)) {
  foreach($line as $col_name=> $col_value) {
	if ($col_name == "Username"){
	$a_n .= " ".$col_value.","; 
	}
 }
}

$admin_names = substr($a_n, 0, -1);
echo "<tr width=60% ><td width=25% class=tddd>Current admins</td><td width=75% class=td>$admin_names&nbsp;</td></tr>";

?>
<tr width=60% ><td class=tddd>Username </td><td class=td><center><input size=50% type=text name=suiteuser class=input ></center></td></tr>
<tr width=60% ><td class=tddd>Password </td><td class=td><center><input size=50% type=text name=suitepass class=input ></center></td></tr>
<tr><td colspan=2><br><center><input type="submit" value="Create Admin Account" class="submit"></center></td></tr>
</table>
</form>




<table width=50% cellpadding="0" cellspacing="1" bordercolorlight="#666666" bordercolordark="#CCCCCC">
<tr>
<td colspan=4 class=tdark><b>MySQL Status</b></td>
</tr>
<tr>
<td colspan=4 class=td>
<?php
if (function_exists(mysql_stat)) { print_r (mysql_stat($link)); }
else { echo "Sorry but mysql_stat() is not allowed with this user permisions"; }
?>
</td>
</tr>

<tr>
<td class=tdark><b>Server Version</b></td>
<td class=tdark><b>Client Version</b></td>
</tr>
<tr>
<td class=td><?php echo mysql_get_server_info(); ?></td>
<td class=td><?php echo mysql_get_client_info(); ?> </td>
</tr>

<tr>
<td class=tdark><b>Host Information</b></td>
<td class=tdark><b>Protocol Information</b></td>
</tr>
<tr>
<td class=td><?php echo mysql_get_host_info(); ?> </td>
<td class=td><?php echo mysql_get_proto_info(); ?> </td>
</tr>
</table>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
