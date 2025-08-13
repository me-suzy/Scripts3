<?php echo"\n\n\n";?>
<!-- start of registerForm.php -->
<?php
// initialize or capture variables
$task = !isset($task)?"Register":$_POST['task'];
$customerid = !isset($_POST['customerid'])? NULL: $_POST['customerid'];
$email = !isset($_POST['email'])? NULL: $_POST['email'];
$password = !isset($_POST['password'])? NULL: $_POST['password'];
$confirmPassword = !isset($_POST['confirmPassword'])? NULL: $_POST['password'];
$privacy = !isset($_POST['privacy'])? "high": $_POST['privacy'];
$news = !isset($_POST['news'])? "no": $_POST['news'];
$firstname = !isset($_POST['firstname'])? NULL: $_POST['firstname'];
$lastname = !isset($_POST['lastname'])? NULL: $_POST['lastname'];
$role = !isset($_POST['role'])? NULL: $_POST['role'];
$organization = !isset($_POST['organization'])? NULL: $_POST['organization'];
$address = !isset($_POST['address'])? NULL: $_POST['address'];
$city = !isset($_POST['city'])? NULL: $_POST['city'];
$stateprov = !isset($_POST['stateprov'])? NULL: $_POST['stateprov'];
$country = !isset($_POST['country'])? NULL: $_POST['country'];
$postalcode = !isset($_POST['postalcode'])? NULL: $_POST['postalcode'];
$areacode = !isset($_POST['areacode'])? NULL: $_POST['areacode'];
$phone = !isset($_POST['phone'])? NULL: $_POST['phone'];
$fax = !isset($_POST['fax'])? NULL: $_POST['fax'];
$cellphone = !isset($_POST['cellphone'])? NULL: $_POST['cellphone'];
$website = !isset($_POST['website'])? NULL: $_POST['website'];
?>

<a name="registerForm">
<?php 
/* 
	NEW REGISTRANT
	If the user is a new registrant then the form action calls the standard 
	registerResult.php page. 

	OLD REGISTRANT
	If the user is returning, and therefore has already registered, then the form action 
	calls loginResult.php 

	These actions are combined on registerForm.php in order to reuse just one form 
	for both the register and the login modules.

   If the value for the variable 'task' is "Register" 
	then use registerResult.php

	For all other tasks use loginResult.php with the registerForm.php
*/
if( $task == "Register" ) {
	$myFormAction = "registerResult.php";
}else{
	$myFormAction = "loginResult.php";	
}
?>
<form name="registerForm" method="post" action="<?php echo $myFormAction;?>">
</a>


<input type="hidden" name="task" value="<?php echo $task;?>">
<input type="hidden" name="customerid" value="<?php echo $customerid;?>">

<table align="left" class="favcolor" cellpadding=3>
<tr>
	<th colspan=2>
		<?php if(!empty($customerid)){echo "$task for $firstname $lastname";}else{echo"Register";}?>
	</th>
</tr>


<tr>
	<td colspan=2 align="right">
		<?php if(!empty($customerid)){echo "ID#$customerid";}else{echo"&nbsp;";}?>
	</td>
</tr>


<tr><td class="required" colspan=2>
<b>* an asterisk means the data is required</b><br><br>
</td></tr>


<tr><td class="required">

*Email</td>
<td> 
<input type="text" name="email" size=30 maxlength=80 value="<?php echo $email;?>"> 
</td></tr>


<tr><td class="required">
*Password</td>
<td>
<input type="password" name="password" size=15 maxlength=15 value="<?php echo $password;?>">
<div class="note">[enter any password you wish]</div>
</td></tr>


<tr><td class="required">
*Confirm<br>
Password</td>
<td>
<input type="password" name="confirmPassword" size=15 maxlength=15 value="<?php echo $password;?>">
<div class="note">[confirm the password you entered above]</div>
</td></tr>


<tr>
<td>Privacy</td>
<?php if(empty($privacy)){$privacy="medium";}?>
<td><input type="radio" name="privacy" value="low"<?php if($privacy=="low"){echo" CHECKED";}?>> low 
    <input type="radio" name="privacy" value="medium"<?php if($privacy=="medium"){echo" CHECKED";}?>> medium 
    <input type="radio" name="privacy" value="high"<?php if($privacy=="high"){echo" CHECKED";}?>> high 
</td></tr> 


<tr><td>
Newsletter</td>
<td><input type="checkbox" name="news" value="yes"<?php if($news=="yes"){echo" CHECKED";}?>> 
<span class="note">OK send infrequent <?php echo ORGANIZATION;?> news</span>
</td></tr>



<tr><td class="required">
*First Name</td>
<td>
<input type="text" name="firstname" size=30 maxlength=35 value="<?php echo $firstname;?>">
</td></tr>

<tr><td class="required">
*Last Name
</td>
<td>
<input type="text" name="lastname" size=30 maxlength=45 value="<?php echo $lastname;?>">
</td></tr>


<tr>
<td>Role</td>
<td><?php include("role.php");?></td>
</tr>


<tr><td>
Organization</td>
<td>
<input type="text" name="organization" size=30 maxlength=45 value="<?php echo $organization;?>">
</td></tr>


<tr><td>
Address</td>
<td>
<input type="text" name="address" size=30 maxlength=70 value="<?php echo $address;?>">
</td></tr>

 
<tr><td>
City</td>
<td>
<input type="text" name="city" size=30 maxlength=25 value="<?php echo $city;?>"> 
</td></tr>


<tr><td>
State</td>
<td>
<input type="text" name="stateprov" size=30 maxlength=25 value="<?php echo $stateprov;?>"> 
</td></tr>


<tr><td class="required">
*Country</td>
<td><?php include("countries.php");?></td></tr>

<tr><td> 
Postal Code</td>
<td>
<input type="text" name="postalcode" size=10 maxlength=10 value="<?php echo $postalcode;?>">
</td></tr>

 
<tr><td>
Area Code
(&nbsp;<input type="text" name="areacode" size=3 maxlength=3 value="<?php echo $areacode;?>">&nbsp;) 
</td><td>
Phone&nbsp;&nbsp;
<input type="text" name="phone" size=20 maxlength=20 value="<?php echo $phone;?>">
</td></tr>

<tr><td>&nbsp;</td>
<td>
Fax&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="fax" size=20 maxlength=20 value="<?php echo $fax;?>">
</td></tr>

<tr><td>&nbsp;</td>
<td>
Cellular
<input type="text" name="cellphone" size=20 maxlength=20 value="<?php echo $cellphone;?>">
</td></tr>


<tr><td>
Website</td>
<td>
<input type="text" name="website" size=25 maxlength=140 value="<?php echo $website;?>"><br>
<span class="note">Example: http://www.yourDomain.com</span>
</td></tr>


<tr class="favcolor2"><td colspan=2>
<input class="submit" type="submit" name="submit" value="<?php echo $task;?>"> 
<input type="checkbox" name="remember_me" CHECKED> Remember me
</td></tr></table>
</form>

</table>
<!-- end of registerForm.php -->