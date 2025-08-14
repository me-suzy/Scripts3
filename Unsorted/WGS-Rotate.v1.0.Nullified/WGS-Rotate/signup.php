<?

// ******************************************************************
// WGS-Rotate Script Admin Area
// Done by Webguy Studios © 2003
// ******************************************************************

include_once("config.inc.php");

// ******************************************************************
// Script Part
// Normally nothing should be changed below
// ******************************************************************


        print $design_top ;

    
    if (isset($_POST['reg']))
    {
      if ($_POST['eml1']==$_POST['eml2'])
      {
        if ($_POST['pass1']==$_POST['pass2'])
        {
          $query = "SELECT * FROM rotate_usr WHERE login='".trim($_POST['username'])."';";
          $res = mysql_query($query);
          if (mysql_num_rows($res)==0)
          {
            $query = "INSERT INTO rotate_usr (login, password, email, account_type, sign_up_date)values('".$_POST['username']."','".$_POST['pass1']."','".$_POST['eml1']."','Standard','".$datetime."')";
            mysql_query($query);
            print 'Sign-Up successful!<br /><a href="member.php">Login</a>';
          }
          else
          {
            print 'The username you entered is already taken, please browse back and choose another!<br /><a href="javascript:history.back()" onMouseOver="self.status=document.referrer;return true">GO BACK</a>';
          }
        }
        else
        {
          print 'The passwords you entered do not match, please go back and correct them.<br /><a href="javascript:history.back()" onMouseOver="self.status=document.referrer;return true">GO BACK</a>';
        }
      }
      else
      {
        print 'The e-mails you entered do not match, please go back and enter identical e-mails<br /><a href="javascript:history.back()" onMouseOver="self.status=document.referrer;return true">GO BACK</a>';
      }
    }
    else
    {
      print '<h3>Sign-Up</h3>
      <form action="signup.php" method="post">
        <table cellspacing="0" cellpadding="2" width="300" border="1" bordercolor="'.$bcolor.'">
          <tr bgcolor="'.$tcolor.'">
            <th colspan="2">Member Sign-up</th>
          </tr>
          <tr>
            <td>e-mail address:</td>
            <td><input name="eml1" /></td>
          </tr>
          <tr>
            <td>confirm e-mail:</td>
            <td><input name="eml2" /></td>
          </tr>
          <tr>
            <td>username</td>
            <td><input name="username" /></td>
          </tr>
          <tr>
            <td>password:</td>
            <td><input name="pass1" type="password" /></td>
          </tr>
          <tr>
            <td>confirm password:</td>
            <td><input name="pass2" type="password" /></td>
          </tr>
          <tr>
            <td colspan="2"><center><input type="submit" name="reg" value="Register" /></center></td>
          </tr>
        </table>
      </form>';
    }
print $design_bottom;
?>
