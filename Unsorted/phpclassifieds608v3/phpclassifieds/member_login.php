<?
if ($logout)
{
		session_start();
	    session_unregister("valid_user");
		session_destroy();	
}
session_start( );

include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");
$fix2 = 1;

if ($submit)
{


         $sql = "select * from $usr_tbl where userid = '$username' AND pass = '$password'";
         $result_check = mysql_query ($sql);
         $num_check =  mysql_num_rows($result_check);
         $old = 1;

         // Check to see if we use old login, and if they exist there
         if (!$num_check)
         {
				$old = 0;
				$sql = "select * from $usr_tbl where email = '$username' AND password_enc = password('$password')";
                $result_check = mysql_query ($sql);
                $num_check =  mysql_num_rows($result_check);
         }

         if ($num_check)
         {
				$sql_line = "select status,verify,approve from $usr_tbl where email = '$username'";

				$result_line = mysql_query ($sql_line);
				while ($row_line = mysql_fetch_array($result_line))
				{
					$status = $row_line["status"];           
					$verify_code = $row_line["verify"];   
					$approve = $row_line["approve"];           
					
				}
				
				
                if ($status == 1)
                {
                	print $la_blocked_login;
                }
                
         		elseif ($verify_code AND $opt_verify)
         		{
         			print $la_need_to;	
         		}
         	elseif ($approve_mem AND !$approve)
         		{
         			print $la_wait_app;	
         		}
         		
                else
         		{
                	
         			$valid_user = $username;
             		session_register("valid_user");
             		
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?
             		
					echo $la_login_success;
					echo $valid_user;
					echo $la_login_success2;

?>
    </td>
  </tr>
</table>

<?
					
					$today = date(Ymd);
					
					$r = mysql_query("update $usr_tbl set last_login='$today'");
					$r2 =mysql_query("update $usr_tbl set num_logged=num_logged+1");
					
					
                	echo "<Script language=\"javascript\">window.location=\"member.php\"</script>";
         		}
         }
         else
         {
				// User with that username/id and password not found
?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?
                echo $la_not_authorized;

?>
    </td>
  </tr>
</table>

<?
                
         }
}

if ($logout)
{

		print "$la_session_remove";
		
		session_register("la");
}
?>


<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    
    
    
<form method="post" action="member_login.php">

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td align="left" valign="top" width="450">

<table>
<tr>
                <td>
                                <? echo $userid_text ?>
          		</td>
                <td>
                                <input type="text" class="txt" name="username" value="<? echo $valid_user ?>" />
                </td>
</tr>
<tr>
                <td>
                                <? echo $add_user_pass ?>
                </td>
                <td>
                                <input type="password" class="txt" name="password" value="<? echo $password ?>" /><br />

                </td>
</tr>
<tr>
                <td colspan="2">
                                <input type="submit" name="submit" value="<? echo $la_login ?>" />
                </td>
</tr>
<tr>
                <td colspan="2" height="10">
                </td>
</tr>
<tr>
                <td colspan="2">
                                </form><a href="register.php"><img src="images/pointer.gif" border="0"><? echo $la_add_user_session ?></a>   
    
    
    
                </td>
</tr>
</table>


    
</td>
    <td valign="top" align="left" width="250">
    
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="1">
  <tr>
    <td width="100%"><table border="0" width="100%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%"><? echo $la_forgotten_password ?>

<form method="post" action="forgot.php">
<p>
<? echo $add_user_email ?> : <br /><input type="text" name="email" class="txt" /><br />  
<input type="submit" value="Send" />
</p>
</form>

</td>
  </tr>
</table>

</td>
  </tr>
</table>

    </td>
  </tr>
</table>


</td>
  </tr>
</table>






<?


require("admin/config/footer.inc.php");
?>