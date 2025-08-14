<?


  if ($_SESSION['valid_user'])
  {
	echo '<table><tr><td>You are logged in as: '.$_SESSION['valid_user'].'</td></tr></table>';
    echo '<table align="right"><tr><td><a href="index.php?log=logout">Log out</a></td></tr>';
    echo '<tr><td><a href="index.php?log=change">Change password</a></td></tr></table>';
  }
  
  else
  {
	if (isset($_POST['userid']))
    {
      // if they've tried and failed to log in
      echo 'Could not log you in';
	echo '<a href="index.php?log=forgot&&user='.$_POST['userid'].'" />Forgotten your<br />password?</a>';
      
    }
	
	
    else 
    {
      // they have not tried to log in yet or have logged out
      echo 'You are not logged in.';
    }

    // provide form to log in 
    echo '<form method=post action="index.php">';
    echo '<table class="log">';
    echo '<tr><td>Userid:</td></tr>';
    echo '<tr><td><input type=text name=userid style="font-size:10px;border:solid 1px;"></td></tr>';
    echo '<tr><td>Password:</td></tr>';
    echo '<tr><td><input type=password name=password style="font-size:10px;border:solid 1px;"></td></tr>';
    echo '<tr><td colspan=2 align=center>';
    echo '<input type=image src="buttons\login.gif" name="logbut" value="Log in"></td></tr>';
    echo '</table></form>';
    echo '<a href="index.php?page=reg" />Register here.</a><br />';
    
  }
?>