<?
	require "settings.php";
        require "lib/mysql.lib";

	$sval = "";

	if($action == "submit"){
		$db = c();
	 	$r = q("select id,status from members where login='$login' and pswd='$pswd'");
		if(e($r)) $es = "Invalid password!";
		else{
			$ff = f($r);

			switch($ff[status]){
			 	case 0:
				$sval = "confirm";
				break;
				case 1:
					setcookie("auth",$ff[id]);
					setcookie("pass",$pswd);
					q("update profiles set ldate='".strtotime(date("d M Y H:i:s"))."' where id='$ff[id]'");
					d($db);
					header("Location: member_center.php");
				break;
				case 2:
				$sval = "contact";
				break;
			}
		}
		d($db);
 	}

include "_header.php";


 if($sval == ""){
?>
<blockquote> 
  <p><b><font size=4 color=555555>Member Log In </font></b><font size=4 color=555555><strong>&gt;</strong></font><br>
  </p>
  <p>&nbsp;</p>
  <p>Please enter your NeoDate Username and Password to sign-in.</p>
  <p><br>
  </p>
  <table width="250" border="0" cellspacing="0" cellpadding="0">
    <form action="login.php?action=submit" method=post>
      <?
    if($es != "" && $action == "submit")
       echo "<tr><td colspan=2><b><font color=C00000>$es</td></tr>";
  ?>
      <tr> 
        <td>Username</td>
        <td> <input type="text" name="login" value="<?php echo $username; ?>" class=cmn> 
        </td>
      </tr>
      <tr> 
        <td>Password</td>
        <td> <input type="password" name="pswd" value="<?php echo $password; ?>" class=cmn> 
        </td>
      </tr>
      <tr> 
        <td colspan="2"> <div align="center">
            <input type="submit" value="Sign in">
          </div></td>
      </tr>
    </form>
  
  </table>
  <p>Forgot your password? Don't worry! Click <a href="forgot.php">here</a> to 
    have it sent to your email address instantly</p>
  <p>Are you new to NeoDate? Click <a href="register.php">here</a> to 
    Create a Profile for FREE! <br>
    (If you cannot access your NeoDate account for any reason, please 
    email contact@yourdomain.com)</p>
  <p>&nbsp;</p>
  <p><br>
    <?
   } 
   if($sval == "confirm"){
?>
  </p>
  <p>You cannot access your account now!<br>
    Please check your e-mail. You should receive confirmation letter.<br>
    Only after your account confirmation you will be able to access your account!<br>
    <?
   }

   if($sval == "contact"){
?>
    Dear user <? echo $login ?>,<br>
    Your account has been disabled by administrator!<br>
    For any questions about this reason, please <a href=mailto:<? echo $ADMIN_MAIL ?>>contact 
    our system administrator</a>. 
    <?
   }
?>
  </p>
  </blockquote>
<? 	include "_footer.php"; ?>