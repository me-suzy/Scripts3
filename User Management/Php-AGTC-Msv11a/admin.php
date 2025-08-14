<?php
// *************************************************************************************************
// Title: 		PHP AGTC-Membership system v1.0a
// Developed by: Andy Greenhalgh
// Email:		andy@agtc.co.uk
// Website:		agtc.co.uk
// Copyright:	2005(C)Andy Greenhalgh - (AGTC)
// Licence:		GPL, You may distribute this software under the terms of this General Public License
// *************************************************************************************************
//
session_start();
include "level4_check.php";

$msg = "To edit a record click on the user name, to list by order click on titles.";

if(isset($_POST['Amend'])) {
 
 		$username = $_POST['username'];
        $userpass = $_POST['userpass'];
        $userlevel = $_POST['userlevel'];
        $useremail = $_POST['useremail'];
		$userid = $_POST['userid'];
		        

$result = mysql_query("Update login_table set user_name='$username', user_pass='$userpass', user_email='$useremail', user_level='$userlevel' where userid=".$_POST['userid']);
$msg = "Record is updated<br>To edit a record click on the user name";
$edit = "";
       

}






if(isset($_POST['Submit']))
{
	$total = $_POST['total'];
	$td = 0;
	$i = 0;
	
	for($i = 1; $i <= $total; $i++)
	{
		if(isset($_POST["d$i"]))
		{
			mysql_query("DELETE FROM login_table WHERE userid=".$_POST["d$i"],$con);
			$td++;
		}
	}

	$msg = "$td record(s) deleted!<br>To edit a record click on the user name";
}


if ($order == "") {$order = "userid";}

$result = mysql_query("Select * from login_table ORDER BY '$order'",$con);
$num = mysql_num_rows($result);
$n = 0;
?>
<html>
<head>
<title>Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CBC9F5">
<h3>AGTC - PHP Login management v1.0a - Administration </h3>

<form name="form1" method="post" action="">
  <p class="smallTextBlack"><?php echo $msg; ?><br>USER LEVELS:<BR>1 = (HIGH RESTRICTION - CAN ACCESS LEVEL 1 ONLY)<BR>2 = (MEDIUM RESTRICTION - CAN ACCESS BOTH LEVELS 2 & 1)<BR>3 = (LOW RESTRICTION - CAN ACCESS BOTH LEVELS 3, 2 & 1)<BR>4 = (ADMIN AND ALL LEVELS)</p>
  <table width="100%" border="0" cellpadding="1" cellspacing="1" bordercolor="#000000">
    <tr bgcolor="#A39FF6" class="standardText"> 
      <td width="5%"><div align="center">DELETE</div></td>
      <td width="5%"><div align="center"><a class="standardText" href="admin.php?order=userid">ID</a></div></td>
      <td width="20%"><div align="center"><a class="standardText" href="admin.php?order=user_name">USER NAME</a> </div></td>
      <td width="20%"><div align="center">PASSWORD </div></td>
      <td width="15%"><div align="center"><a class="standardText" href="admin.php?order=user_level">USER LEVEL</a></div></td>
      <td width="20%"><div align="center"><a class="standardText" href="admin.php?order=user_email">EMAIL</a></div></td>
      <td width="10%"><div align="center">USER IP </div></td>
      <td width="10%"><div align="center"><a class="standardText" href="admin.php?order=date">DATE REGISTERED</a> </div></td>
    </tr>
    <?php while($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$n++;
?>
    <tr> 
      <td width="5%"><div align="center">
        <input type="checkbox" name="d<?php echo $n;?>" value="<?php echo $row['userid'];?>">
      </div></td>
      <td width="5%"><?php echo $row['userid'];?></td>
      <td width="20%"><a href="admin.php?edit=<?php echo $row['user_name']?>"><?php echo $row['user_name'];?></a></td>
      <td width="20%"><?php echo $row['user_pass'];?></td>
	  <td width="5%"><center><?php echo $row['user_level'];?></center></td>
      <td width="20%"><?php echo $row['user_email'];?></td>
      <td width="20%"><?php echo $row['user_ip'];?></td>
      <td width="20%"><?php echo $row['date'];?></td>
    </tr>
    <?php

 }?>
    
      
    </tr>
      <div align="center"></div>
    
  </table>
  <input type="submit" name="Submit" value="Delete"> <input name="total" type="hidden" id="total" value="<?php echo $n?>">
<p>&nbsp;</p></form>
<!-- FORM FOR AMENDMENT -->
<?php if ($edit) {$msg = "Edit record below";
$result = mysql_query("Select * from login_table WHERE user_name = '$edit'",$con);

$row = mysql_fetch_array($result)


?>


<form name="form2" method="post" action="">
<div class="smallTextBlack">Edit user :-</div>
<table width="100%" border="0" cellpadding="1" cellspacing="1" bordercolor="#000000">
 <tr bgcolor="#999999" class="standardText">
   
   <td><div align="center">ID</div></td>
   <td><div align="center">USER NAME </div></td>
   <td><div align="center">PASSWORD</div></td>
   <td width="20%"><div align="center">USER LEVEL </div></td>
   <td><div align="center">EMAIL</div></td>
   <td><div align="center">USER IP </div></td>
   <td><div align="center">DATE REGISTERED </div></td>
 </tr>
 <tr> 
      <td width="5%"><center><?php echo $row['userid'];?></center></td>
      <td width="15%"><input type="username" name="username" value="<?php echo $row['user_name'];?>"></a></td>
      <td width="35%"><input type="userpass" name="userpass" value="<?php echo $row['user_pass'];?>" size="40"></td>
	  <td width="15%"><select name="userlevel">	<option>1</option>
	  											<option>2</option>
												<option>3</option>
												<option>4</option></select>&nbsp;SET AT LEVEL:&nbsp;<?php echo $row['user_level'];?></td>
      <td width="20%"><input type="useremail" name="useremail" value="<?php echo $row['user_email'];?>"></td>
      <td width="10%"><?php echo $row['user_ip'];?></td>
      <td width="10%"><?php echo $row['date'];?></td>
    </tr>
  </table> 
<input type="hidden" name="userid" value="<?php echo $row['userid'];?>">	
<input type="Submit" name="Amend" value="Update"></form>
<?php }?> 

<p><a href="index.php">Back to index page</a></p>

</body>
</html>
