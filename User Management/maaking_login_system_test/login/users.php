<?PHP
###########################################
#-----------Users login system------------#
###########################################
/*=========================================\
Author      :  Mohammed Ahmed(M@@king)    \\
Version     :  1.0                        \\
Date Created:  Aug 20  2005               \\
----------------------------              \\
Last Update:   August 22 2005             \\
----------------------------              \\
Country    :   Palestine                  \\
City       :   Gaza                       \\
E-mail     :   m@maaking.com              \\
MSN        :   m@maaking.com              \\
AOL-IM     :   maa2pal                    \\
WWW        :   http://www.maaking.com     \\
Mobile/SMS :   00972-599-622235           \\
                                          \\
===========================================\
------------------------------------------*/
include ("config.php");

// the Default function.
//note for functions: if you want to include a value of some variables inside the funtions,
//then you have to GLOBAL it first.
function index($user) {
     global $db, $prefix;

     //check if the user is logged in or not.
     if (is_logged_in($user)) {
          include("header.php");
          //if the user is logged in then read the cookies.
          $cookie_read = explode("|", base64_decode($user));
          //define variables to hold cookie values.
          $userid = $cookie_read[0];
          $username = $cookie_read[1];
          $password = $cookie_read[2];
          $ipaddress = $cookie_read[3];
          $lastlogin_date = $cookie_read[4];
          $lastlogin_time = $cookie_read[5];
          
          //print wilcome message
          echo "Welcome <b>$username</b>, Last login from: [$ipaddress] on [$lastlogin_date @ $lastlogin_time] (<a href=users.php?maa=Logout>Logout</a>)";
          echo "<br><br><br><br>";
          include("footer.php");
     }else{
         //if the user is not logged in then show the login form.
         //  header("Location: users.php?maa=Login");  die();
         include("header.php");
         login_form();
         include("footer.php");
    }
}
################################################################################
#------------------------------------------------------------------------------#
#  login
#------------------------------------------------------------------------------#
################################################################################
//the login form
function login_form(){
         global $username,$user_err,$pass_err,$error_msg;

echo "<center><font class=\"title\">Please enter your username and password to log in.</font></center>\n";
echo "
<center>
      <form method=\"POST\" action=\"users.php\" name=\"loginform\">
        <table border=\"0\" cellspacing=\"2\" cellpadding=\"4\">
        <tr>
            <td bgcolor=\"#E2E2E2\">Username: </td>
            <td bgcolor=\"#E2E2E2\"><input type=\"text\" name=\"username\" value=\"$username\" size=\"11\"> $user_err</td>
        </tr>
        <tr>
            <td bgcolor=\"#E2E2E2\">Password: </td>
            <td bgcolor=\"#E2E2E2\"><input type=\"password\" name=\"password\" size=\"11\"> $pass_err</td>
        </tr>
        <tr>
             <td colspan=2>Remember me for 2 weeks <input type=\"checkbox\" name=\"remember\" value=\"ON\"></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td> <input type=\"hidden\" name=\"maa\" value=\"do_login\">
                 <input type=\"submit\" value=\"Login\"></p>
            </td>
        </tr>
        </table> $error_msg
      </form>[ <a href=\"users.php?maa=Register\">Register New!</a> <img src=\"images/register.gif\"> ] [ <a href=\"users.php?maa=Forgot_pwd\">Forgot password?.</a> <img src=\"images/forgot_pwd.gif\"> ]<br><br>";
}

//a login function to call the login form.
function Login(){
        include("header.php");
        login_form();
        include("footer.php");
}

//this function will do the login for you.
function do_login(){
         global $prefix,$db,$username,$password, $remember, $user_err,$pass_err,$error_msg,$REMOTE_ADDR;

         //prevent some SQL injections.
         $username = mysql_real_escape_string($_POST['username']);
         $password  = mysql_real_escape_string($_POST['password']);

         //check username and password fields.
         if((!$username) || (!$password)){
                include("header.php");
                $reqmsg= "(<font class=error>Required!</font>)";

                if(trim(empty($username))){
                   $user_err= $reqmsg;
                }
                if(empty($password)){
                   $pass_err= $reqmsg;
                }
                //$error_msg = "<center><font class=\"error\">Error:</font></center>\n";
                login_form();
                include("footer.php");
                exit();
         }

         //encyrpt  password for more Security
         $md5_pass = md5($password);
         $sql = mysql_query("SELECT * FROM ".$prefix."_users WHERE username='$username' AND password='$md5_pass'");
         $login_check = mysql_num_rows($sql);
         ///////////////////////////////////////////////////////////////////////
         if($login_check > 0){
            while($row = mysql_fetch_array($sql)){

                 $userid = $row['userid'];
                 $username = $row['username'];
                 $password = $row['password'];
                 $ipaddress = $row['ipaddress'];

                 $lastlogin = explode(" ", $row['lastlogin']);
                 $lastlogin_date =  $lastlogin[0];
                 $lastlogin_time = $lastlogin[1];

                 $info = base64_encode("$userid|$username|$password|$ipaddress|$lastlogin_date|$lastlogin_time");
                 if (isset($remember)){
                     setcookie("user","$info",time()+1209600);
                 }else{
                     setcookie("user","$info",0);
                 }
                 mysql_query("UPDATE ".$prefix."_users SET ipaddress='$REMOTE_ADDR', lastlogin=NOW() WHERE userid='$userid'") or die (mysql_error());

                 echo "Login success please wait..........";
                 echo "<META HTTP-EQUIV=Refresh CONTENT=\"2; URL=users.php\">";
                 //header("Location: users.php");
            }
         }else{
                //include("header.php");
                $error_msg = "<font class=error>Login error. Please check username/password.</font>";
                unset($username);
                unset($password);
                include("header.php");
                login_form();
                include("footer.php");
                exit();
         }
}


################################################################################
#------------------------------------------------------------------------------#
#  logout
#------------------------------------------------------------------------------#
################################################################################
function Logout($user) {

    $cookie = explode("|", base64_decode($user));
    $result = mysql_query("SELECT password FROM ".$prefix."_users WHERE username='$cookie[1]'");
    $row = mysql_fetch_array($result);
    $pass = $row['password'];
    if ($cookie[2] == $pass && $pass != "") {
	return $cookie;
    } else {
	unset($user);
	unset($cookie);
    }
    
    setcookie("user");
    $user = "";
    header("Location: users.php");
    
}
################################################################################
#------------------------------------------------------------------------------#
#  Register
#------------------------------------------------------------------------------#
################################################################################
function Register(){

         include("header.php");
         register_form();
         include("footer.php");
}

function register_form(){
         global $username, $password, $email, $fullname, $user_taken_err, $email_taken_err;
echo "<center><font class=\"title\">Registration form</font></center><br>\n";
echo "<center>Fields marked with a * are required.
      <form name=\"RegisterForm\" method=\"POST\" action=\"users.php\" onsubmit='return CheckRegisterForm(RegisterForm)'>
      <table align=\"center\" border=\"1\" width=\"400\" id=\"table1\" cellpadding=\"2\" bordercolor=\"#C0C0C0\">
		<tr>
			<td width=\"100\" align=\"right\">Username:</td>
			<td><input type=\"text\" name=\"username\" size=\"18\" value=\"$username\"> * $user_taken_err</td>
		</tr>
		<tr>
			<td align=\"right\">Password:</td>
			<td><input type=\"password\" name=\"password\" size=\"18\" value=\"$password\"> *</td>
		</tr>
		<tr>
			<td align=\"right\">Email:</td>
			<td><input type=\"text\" name=\"email\" size=\"27\" value=\"$email\"> * $email_taken_err</td>
		</tr>
		<tr>
			<td align=\"right\">Full Name:</td>
			<td><input type=\"text\" name=\"fullname\" size=\"27\" value=\"$fullname\"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td> <input type=\"hidden\" name=\"maa\" value=\"do_Register\">
                             <input type=\"submit\" value=\"Register\"></td>
		</tr>
	</table></form>";
}
function do_Register(){
          global $db, $prefix, $username, $password, $email, $fullname, $user_taken_err, $email_taken_err;
          global $site_name, $site_email, $site_url;
          
          $username = mysql_real_escape_string($_POST['username']);
          $password  = mysql_real_escape_string($_POST['password']);
          $email  = mysql_real_escape_string($_POST['email']);
          $fullname  = mysql_real_escape_string($_POST['fullname']);
         
          //this function will check fields incase of javascript not working.
          if((!$username) || (!$password) || (!$email)){

                if(trim(empty($username))){

                }
                if(empty($password)){

                }
                if(trim(empty($email))){

                }
                //print the error message and load the form.
                include("header.php");
                echo "<center><font class=\"error\">Error:<br>Please fill all fields.</font></center>\n";
                register_form();
                include("footer.php");
                exit();
          }
          /*--nothing empty? lets do the register-------------------------------------------------------------*/
          $sql_email_check = mysql_query("SELECT email FROM ".$prefix."_users WHERE email='$email'");
          $sql_username_check = mysql_query("SELECT username FROM ".$prefix."_users WHERE username='$username'");
          $email_check = mysql_num_rows($sql_email_check);
          $username_check = mysql_num_rows($sql_username_check);
          if(($email_check > 0) || ($username_check > 0)){

               //define error message for usage in multi plces.
               $exist_msg= "<font class=\"error\">(Already Taken!.)</font>";

               if($email_check > 0){
                  $email_taken_err =  $exist_msg;
                  unset($email);
               }

               if($username_check > 0){
                  $user_taken_err =  $exist_msg;
                  unset($username);
               }

               //if the username or email already been taken load the form and print errors.
               include("header.php");
               register_form();
               include("footer.php");
               exit();
          }
          $md5_password = md5($password);
          $result = mysql_query("INSERT INTO ".$prefix."_users ( username,password,email,fullname)
                                                         VALUES('$username','$md5_password','$email','$fullname')") or die ("Error in registration sql:". mysql_error());

$subject = "Your info at $site_name";
$message = "
Welcome to $site_name

Please keep this email for your records. Your account information is as follows:

----------------------------
Username: $username
Password: $password
----------------------------

Your account is currently active. You can use it by visiting the following link:

$site_url

Please do not forget your password as it has been encrypted in our database and we cannot retrieve it for you. However, should you forget your password you can request a new one which will be sent to your email.

Thank you for registering.

--
- $site_name
$site_url


This email was automatically generated.
Please do not respond to this email or it will ignored.";
                      
          if(!mail($email,$subject,$message, "FROM: $site_name <$site_email>")){
             die ("Faild sending registration email, please report this to the webmaster ($site_email)");
          }else{
                include("header.php");
                echo "registration was successfull.....!! you can now log in";
                login_form();
                include("footer.php");
         }
}

################################################################################
#------------------------------------------------------------------------------#
#  Forgot Password
#------------------------------------------------------------------------------#
################################################################################
function Forgot_pwd_form(){
global $error_msg;
echo "<center><font class=\"title\">Send me a new password</font>
<form method='POST' action='users.php'>
<table border='0' cellpadding='4'>
        <tr>
                <td bgcolor='#E2E2E2'>Username:</td>
                <td bgcolor='#E2E2E2'><input type='text' name='username' size='11'></td>
        </tr>
        <tr>
                <td bgcolor='#E2E2E2'>Email:</td>
                <td bgcolor='#E2E2E2'><input type='text' name='email' size='11'></td>
        </tr>
        <tr>
                <td>&nbsp;</td>
                    <td>
                    <input type='hidden' name='maa' value='do_Forgot_pwd'>
                    <input type='submit' value='Send password'></p>
                </td>
        </tr>
</table><center>$error_msg</center>
</form>";
}

function Forgot_pwd(){
         global $user, $prefix, $db;

         include("header.php");
         Forgot_pwd_form();
         include("footer.php");
}

function do_Forgot_pwd(){
         global $user, $prefix, $db, $email, $username, $error_msg, $site_name ,$site_email, $site_url;

         $username = mysql_real_escape_string($_POST['username']);
         $email  = mysql_real_escape_string($_POST['email']);

         $result = mysql_query("SELECT * FROM ".$prefix."_users WHERE username='$username' AND email='$email'");
         $check = mysql_num_rows($result);
         if($check == 1){

         function new_pwd() {
                  $chars = "abchefghjkmnpqrstuvwxyz0123456789";
                  srand((double)microtime()*1000000);
                  $i = 0;
                  while ($i <= 7) {
                            $num = rand() % 33;
                            $tmp = substr($chars, $num, 1);
                            $pwd = $pwd . $tmp;
                            $i++;
                  }
                  return $pwd;
         }
         $new_pwd = new_pwd();
         $md5_password = md5($new_pwd);
         $sql = mysql_query("UPDATE ".$prefix."_users SET password='$md5_password' WHERE email='$email'");






$subject = "New password";
$message = "
Hello $username,

You are receiving this email because you have (or someone pretending to be you has) requested a new password be sent for your account on $site_name.

Here it is below.
--------------------------
Username: $username
Password: $new_pwd
--------------------------
You may login below:
$site_url

You can of course change this password yourself via the profile page. If you have any difficulties please contact the webmaster.

--
-Thanks
$site_name

This email was automatically generated.
Please do not respond to this email or it will ignored.";

         mail($email,$subject,$message, "FROM: $site_name <$site_email>");

         include("header.php");
         echo "Your New Pass has been emailed to your email.";
         echo "<br>please wait...";
         include("footer.php");


         }else{
                include("header.php");
                Forgot_pwd_form();
                echo "<center><font class=\"error\">Error: Wrong username/email</font></center><br>";
                include("footer.php");
         }
}

################################################################################
#------------------------------------------------------------------------------#
#  a switch  for switching between functions
#------------------------------------------------------------------------------#
################################################################################
switch ($maa){

       case "Forgot_pwd":
            Forgot_pwd();
            break;

       case "do_Forgot_pwd":
            do_Forgot_pwd();
            break;
            
       case "Register":
            Register();
            break;

       case "do_Register":
            do_Register();
            break;
            
       case "Logout":
            Logout($user);
            break;
            
       case "Login":
            Login();
            break;

       case "do_login":
            do_login();
            break;

       Default:
               index($user);
               Break;
}
?>
