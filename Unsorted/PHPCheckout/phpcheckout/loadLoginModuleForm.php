<!-- start of loadLoginModuleForm.php -->
<p>
	<form name="loadLoginModuleForm" method="post" action="loginResult.php">
		<input type="hidden" name="task" value="Verify Customer Record">
		<input type="hidden" name="email" value="<?php echo $email;?>">
		<input type="hidden" name="password" value="<?php echo $password;?>">
		<input class="submit" type="submit" name="submit" value="Click here to continue">
	</form>
</p>
<!-- end of loadLoginModuleForm.php -->
