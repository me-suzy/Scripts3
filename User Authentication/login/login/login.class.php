<?
/******************************************************
Simple Login Class v.1.0
Developed By: Jairus T. Bondoc jairusbondoc@yahoo.com

Usage:
see index.php
*******************************************************/
class login
{
	var $usernameLabel;
	var $passwordLabel;
	var $usernameVar;
	var $passwordVar;
	var $width;
	var $height;
	var $postto;	
	var $mode;
	var $method;
	function login($method='POST', $postto='', $width='100', $height='', $mode='default')
	{
		$this->method = $method;
		$this->postto = $postto;
		$this->height = $height;
		$this->width = $width;
		$this->mode = $mode;
		$this->usernameLabel = "Username";
		$this->passwordLabel = "Password";
		$this->submitLabel = "Submit";
		$this->usernameVar = "username";
		$this->passwordVar = "password";
		$this->submitVar = "sbmt";
	}

	function show()
	{
		$this->showMode($this->mode);
	}	

	function showMode($mode)
	{
		if($mode=='default')
		{
		?>
			<form method="<?=$this->method?>" action="<?=$this->postto?>">
			<table width="<?=$this->width?>" cellpadding="3">
			  <tr>
				<td><?=$this->usernameLabel?></td>
				<td width="100%"><input type="text" name="<?=$this->usernameVar?>" size="20"></td>
			  </tr>
			  <tr>
				<td><?=$this->passwordLabel?></td>
				<td width="100%"><input type="password" name="<?=$this->passwordVar?>" size="20"></td>
			  </tr>
			  <tr>
				<td width="100%" colspan="2"><input type="submit" value="<?=$this->submitLabel?>" name="<?=$this->submitVar?>"></td>
			  </tr>
			</table>
			</form>
		<?
		}
		elseif($mode=="crisp")
		{
		?>
			<form method="<?=$this->method?>" action="<?=$this->postto?>">
			<table width="<?=$this->width?>" cellpadding="3">
			  <tr>
				<td><?=$this->usernameLabel?></td>
				<td width="100%"><input style="border:1px solid" type="text" name="<?=$this->usernameVar?>" size="20"></td>
			  </tr>
			  <tr>
				<td><?=$this->passwordLabel?></td>
				<td width="100%"><input style="border:1px solid" type="password" name="<?=$this->passwordVar?>" size="20"></td>
			  </tr>
			  <tr>
				<td width="100%" colspan="2"><input style="border:1px solid" type="submit" value="<?=$this->submitLabel?>" name="<?=$this->submitVar?>"></td>
			  </tr>
			</table>
			</form>
		<?		
		}
		
	}

	function setUsernameVar($username)
	{
		$this->usernameVar = $username;
	}

	function setPasswordVar($password)
	{
		$this->passwordVar = $password;
	}
		
	function setSubmitVar($submit)
	{
		$this->submitVar = $submit;
	}			

	function setUsernameLabel($username)
	{
		$this->usernameLabel = $username;
	}

	function setPasswordLabel($password)
	{
		$this->passwordLabel = $password;
	}	
			
	function setSubmitLabel($submit)
	{
		$this->submitLabel = $submit;
	}		

	function getUsernameVar()
	{
		if(strtolower($this->method)=="post")
			return $_POST[$this->usernameVar];
		else
			return $_GET[$this->usernameVar];
	}

	function getPasswordVar()
	{
		if(strtolower($this->method)=="post")
			return $_POST[$this->passwordVar];
		else
			return $_GET[$this->passwordVar];
	}	
	
	function getSubmitVar()
	{
		if(strtolower($this->method)=="post")
			return $_POST[$this->submitVar];
		else
			return $_GET[$this->submitVar];
	}	
	
}
?>