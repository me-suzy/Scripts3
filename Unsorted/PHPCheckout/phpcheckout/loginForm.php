<?php echo"\n\n\n\n";?>
<!-- Start of loginForm.php-->

<form name="loginForm" method="post" action="loginResult.php">
<input type="hidden" name="task" value="Verify Customer Record">
<!--onSubmit="return checkForm(this)"-->


<?php
$email = !isset($_POST['email'])?NULL:$_POST['email']; // initialize or capture variable
$password = !isset($_POST['password'])?NULL:$_POST['password']; // initialize or capture variable
$customerid = !isset($_COOKIE['customeridCookie'])?$_POST['customerid']:$_COOKIE['customeridCookie']; 
$remember_me = !isset($_REQUEST['remember_me'])?'on':$_REQUEST['remember_me'];



///////////////     START OF retrieve the email and password     ///////// 
/* 
	START OF retrieve the email and password if a customeridCookie is set
	We do this to get the email and password, and put these values 
	into the login form. This saves the user from entering these 
	values which they may not remember, or they may be too lazy to enter them 
	for themselves.
*/
if(isset($customerid)) { // if a customerid is present
	// get the customer email and password
	$query = "SELECT email, password FROM " . TABLECUSTOMER . " WHERE customerid='$customerid'";
	// pconnect, select and query
	if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
		if ( mysql_select_db(DBNAME, $link_identifier)) {
			// run the query
   		$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
			$rows = mysql_num_rows($queryResultHandle);
			if($rows > 0){ // then a customer record exists so retrieve it
				// retreive the data
				$data = mysql_fetch_array($queryResultHandle);
				$email = $data["email"];
				$password = $data["password"];
			} // $rows > 0
		}else{ // select
			echo mysql_error();
		} // select	
	}else{ // pconnect
		echo mysql_error();
	} // pconnect
} // if(isset($customerid
///////////////     END OF retrieve the email and password     /////////
?>


<input type="hidden" name="task" value="Verify Customer Record">
<input type="hidden" name="customerid" value="<?php echo $customerid;?>">
<table class="favcolor">
<tr>
<th colspan=2>Login<?php if(isset($customerid)){echo" for CustomerID #$customerid";}?></th>
</tr>


<tr>
<td>Email</td>
<td><input type="text" name="email" value="<?php echo $email;?>" size="25" maxlength="120"></td>
</tr>


<tr>
<td>Password</td>
<td><input type="password" name="password"  value="<?php echo $password;?>" size="10" maxlength="15"> <a href="password.php">Look Up Password</a></td>
</tr>


<tr>
<td>&nbsp;</td>
<td>
<input type="checkbox" name="remember_me"<?php if($remember_me == "on"){echo " CHECKED";}?>> Remember me
</td>
</tr>


<tr>
<td>&nbsp;</td>
<td><input type="submit" name="submit" value="Click Here to Log In" class="submit">

</td>
</tr>


</table>
</form>
<!-- end loginForm.php-->
