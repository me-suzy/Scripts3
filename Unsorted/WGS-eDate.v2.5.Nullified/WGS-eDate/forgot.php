<?
	require "_header.php";
        require "lib/mail.lib";

	$sval = "";

	if($action == "submit"){
		$db = c();
	 	$r = q("select * from members where login='$login' or email='$email'");
		if(e($r)) $es = "User not found!";
		else{
			$ff = f($r);
			$fname = $ff[fname];
			$lname = $ff[lname];
			$pswd = $ff[pswd];
			
			MsgFromTpl($email,"Forgot password info.","tpl/forgot.mtl");		
			$sval = "mail";
		}
		d($db);
 	}


 if($sval == ""){
?>
<blockquote>
<b><font size=4>Request password in email &gt; </font></b><br>
<br>
Please provide your username or email.
<br>
<table width="250" border="0" cellspacing="0" cellpadding="0">
  <form action="forgot.php?action=submit" method=post>
  <?
    if($es != "" && $action == "submit")
       echo "<tr><td colspan=2><b><font color=C00000>$es</td></tr>";
  ?>
  <tr>
      <td>Username:</td>
    <td>
        <input type="text" name="login" class=cmn>
      </td>
  </tr>
  <tr>
      <td>E-mail:</td>
    <td>
        <input type="text" name="email" class=cmn>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <input type="submit" value="Get password">
      </td>
  </tr>
  </form>
</table>
<br> If you can't access your account, please, <a href=mailto:<? echo $ADMIN_MAIL; ?>>contact our system administrator</a>.<br>
</blockquote>
<?
   } 

  if($sval == "mail"){
 	echo "<center>";
	echo "Dear $ff[fname] $ff[lname]!<br>";
	echo "Check your mail soon, we have sent you your account info.";
	echo "</center>";
  }
?>
<? 	require "_footer.php"; ?>