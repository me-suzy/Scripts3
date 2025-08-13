<?php echo"\n\n\n\n";?>
<!-- Start of loginForm.php-->
<form name="userlogin" method="post" action="yellowgoal.php" onSubmit="return checkForm(this)">
<table class="form">
<tr>
<th colspan=2> Login</th>
</tr>


<tr>
<td>Goal</td>
<td><select name="goal" class="input">
	<option value="Oops!" SELECTED>Choose a Goal</option>
	<option value="Add">Add New Listing</option>
	<option value="Edit">Edit a Listing</option>
	<option value="Delete">Delete a Listing</option>
</select>
</td>
</tr>

<tr>
<td>Email</td>
<td><input type="text" name="yemail" value="<?php echo $yemail;?>" size="25" maxlength="120" class="input"></td>
</tr>


<tr>
<td>Password</td>
<td><input type="password" name="ypassword"  value="<?php echo $password;?>" size="10" maxlength="15" class="input"> <a href="password.php">Look Up Password</a></td>
</tr>


<tr>
<td>&nbsp;</td>
<td><input type="submit" name="submit" value="Click Here to Log In" class="input"></td>
</tr>


</table>
</form>
<!-- end loginForm.php-->
