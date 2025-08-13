<?
session_start();
include_once("admin/config/header.inc.php");
include_once("admin/inc.php");
include_once("functions.php");

$banlist = split(",", $bans);

if (!$special_mode)
{ include("navigation.php"); }
// { print("$menu_ordinary<p />"); }
// print("<h2>$name_of_site</h2>");

if ($submit)
{
                        
				include "array.php";
				$name = strip_tags ("$name");
                $email = strip_tags ("$email");
                $usr_1 = strip_tags ("$usr_1");
                $usr_2 = strip_tags ("$usr_2");
                $usr_3 = strip_tags ("$usr_3");
                $usr_4 = strip_tags ("$usr_4");
                $usr_5 = strip_tags ("$usr_5");

				
                print '		    
				<table border="0" width="100%" cellspacing="0" cellpadding="10" class="warn">
				<tr>
				<td width="100%"><p class=\"warn\">
				';	          
                
                
                $result = mysql_query ("select * from $usr_tbl where email = '$email'");
                if (mysql_num_rows($result)>0)
                {
					$stop = 1;
						print "$la_error_msg20.<br />";
					                          
				}
                else
                {
                         if (ereg("^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$", $email))
                         {
                                 	$stop = 0;
                         }
                         else
                         {
									print "$la_error_msg21<br />";
									$stop = 1;
                         }
                         if (($passwd <> $passwd2) OR $passwd == '' OR $passwd2 = '')
                         {

								print "$la_error_msg22<br />";
								$stop = 1;
                         }
                         
                         
                         if ($usr_1_mandatory AND $usr_1 == "")
                         {
                         		print "$la_field $usr_1_text $la_mand<br />";
								$stop = 1;
                         }
                         
                         if ($usr_2_mandatory AND $usr_2 == "")
                         {
                         		print "$la_field $usr_2_text $la_mand<br />";
								$stop = 1;
                         }
                         
                         if ($usr_3_mandatory AND $usr_3 == "")
                         {
                         		print "$la_field $usr_3_text $la_mand<br />";
								$stop = 1;
                         }
                         
                         if ($usr_4_mandatory AND $usr_4 == "")
                         {
                         		print "$la_field $usr_4_text $la_mand<br />";
								$stop = 1;
                         }
                         
                         if ($usr_5_mandatory AND $usr_5 == "")
                         {
                         		print "$la_field $usr_5_text $la_mand<br />";
								$stop = 1;
                         }
                         
                         
                         
                         function ban($banlist, $email) 
                         { 
							global $la_error_msg23;
							global $la_error;
							global $stop;
							global $bans;
							
							foreach($banlist as $banned) 
							{ 
								$email_banned = explode("@", $banned); 
								if ($email_banned[0] == "*") 
								{ 
									$email_check = explode("@", $email); 
							        if (trim(strtolower($email_check[1])) == trim(strtolower($email_banned[1]))) 
							        {
							               $stop = 1;
							               print "$la_error : $la_error_msg23<br />";
							        }
								} 
							   	else 
								{ 
						           	if (trim(strtolower($email)) == trim(strtolower($banned))) 
						            {
						            	$stop = 1;
						            	print "$la_error : $la_error_msg23<br />";
						         	} 
								}	 
							} 
							 
						} 
						ban($banlist, $email); 
						// END banlistfunction
                         
                         print "</p></td></tr></table>";
                         if (!$stop)
                         {
							$password = $passwd;
							
							if ($opt_verify) {
								$random = rand(1000, 9000);
								$random = $random . $email;
							}
							else {
								$random = 0;	
							}
							$registered = date(Ymd);
							$sql_insert = "insert into $usr_tbl (password_enc, email, name,emelding,hide_email,usr_1,usr_2,usr_3,usr_4,usr_5,verify,registered,months,approve_from) values (password('$password'), '$email', '$name', '$emelding', '$hide_email','$usr_1_inn','$usr_2_inn','$usr_3_inn','$usr_4_inn','$usr_5_inn','$random','$registered','$months','$registered')";
							$result = mysql_query($sql_insert);

                            if ($result)
                            {
                            		$valid_user = $email;
                                    if (!$opt_verify)
                                    {
                            			session_register("valid_user");
                                    }

?>
    
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?
				    print "<br><b>$la_successreg</b><br />";
                                    print "$la_successreg2<p />";
                                                                      
                                    if (!$opt_verify)
                                    {
                                    	print "<a href='member.php'>$la_successreg3</a>. <p />";
                                    	
?>
    </td>
  </tr>
</table>

<? 
                                    	
                                    }
                                    else 
                                    {
                                    	print "$la_confirm_email<p />";
                                    	
                                    	
?>
    </td>
  </tr>
</table>
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1"></td>
  </tr>
</table>

<?  
                                    	
                                    }

require("admin/config/mail.inc.php");

if ($opt_verify)
{


$val_user_title = ereg_replace ("\{NAME\}", $name, $val_user_title);
$val_user_msg = ereg_replace ("\{NAME\}", $name, $val_user_msg);
//$val_user_msg = ereg_replace ("{NAME}", "$name", $val_user_msg);
$val_user_msg = ereg_replace ("\{URL\}", $url, $val_user_msg);
//$val_user_msg = ereg_replace ("{URL}", "$url", $val_user_msg);
$val_user_msg = ereg_replace ("\{EMAIL\}", "$email", $val_user_msg);
//$val_user_msg = ereg_replace ("{EMAIL}", "$email", $val_user_msg);
$val_user_msg = ereg_replace ("\{PASSWD\}", "$passwd", $val_user_msg);
//$val_user_msg = ereg_replace ("{PASSWD}", "$passwd", $val_user_msg);
$val_user_msg = ereg_replace ("\{SITENAME\}", "$name_of_site", $val_user_msg);
//$val_user_msg = ereg_replace ("{SITENAME}", "$name_of_site", $val_user_msg);
$val_user_msg = ereg_replace ("\{VERIFY\}", "$random", $val_user_msg);
//$val_user_msg = ereg_replace ("{SITENAME}", "$name_of_site", $val_user_msg);



$from = "$from_adress_mail";
$subject = "$val_user_title";

$sendto = "$email";
$headers .= "From: $name_of_site<$from_adress_mail>\n";
$headers .= "Reply-To: <$from_adress_mail>\n";
$headers .= "X-Sender: <$from_adress_mail>\n";
$headers .= "X-Mailer: PHP4\n"; //mailer
$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
$headers .= "Return-Path: <$from_adress_mail>\n";
mail($sendto, $subject, $val_user_msg, $headers);


}

if (!$opt_verify)
{

$welcome_newu_msg = ereg_replace ("\{NAME\}", $name, $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{NAME}", "$name", $welcome_newu_msg);
$welcome_newu_msg = ereg_replace ("\{URL\}", $url, $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{URL}", "$url", $welcome_newu_msg);
$welcome_newu_msg = ereg_replace ("\{EMAIL\}", "$email", $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{EMAIL}", "$email", $welcome_newu_msg);
$welcome_newu_msg = ereg_replace ("\{PASSWD\}", "$passwd", $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{PASSWD}", "$passwd", $welcome_newu_msg);
$welcome_newu_msg = ereg_replace ("\{SITENAME\}", "$name_of_site", $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{SITENAME}", "$name_of_site", $welcome_newu_msg);
$welcome_newu_msg = ereg_replace ("\{VERIFY\}", "$random", $welcome_newu_msg);
//$welcome_newu_msg = ereg_replace ("{SITENAME}", "$name_of_site", $welcome_newu_msg);

$subject = "$welcome_newu_ttl";
$message = "$welcome_newu_msg";


$sendto = "$email";
$headers .= "From: $name_of_site<$from_adress_mail>\n";
$headers .= "Reply-To: <$from_adress_mail>\n";
$headers .= "X-Sender: <$from_adress_mail>\n";
$headers .= "X-Mailer: PHP4\n"; //mailer
$headers .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal
$headers .= "Return-Path: <$from_adress_mail>\n";



mail($sendto, $subject, $message, $headers);

                            }                            

                                         }
                                         else
                                         {
                                          print "Error<p><hr>$sql_insert<hr>";
                                         }
                        }
} }

if (!$submit OR ($submit AND $stop))
{
	if (!$submit)
    {

?>
      
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%">
          
<?

		print "$la_why";
		
?>
    </td>
  </tr>
</table>

<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="images/spacerbig.gif" width="1" height="1"></td>
  </tr>
</table>

<?
		
    }
?>    

<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%"><br><form method="post" action="register.php">
 <table width="100%" cellspacing="0">
   <tr>
     <td><? echo $la_mailaddress ?>:</td>
     <td valign="top" class="star"><input type="text" name="email" class="txt" size="30" maxlength="100" /> *</td></tr>
   <tr>
     <td><? echo $la_fullname ?>:</td>
     <td valign="top" class="star"><input type="text" name="name" class="txt" size="16" maxlength="16" /> *</td></tr>
   <tr>
     <td><? echo $la_pass1 ?>:</td>
     <td valign="top" class="star"><input type="password" name="passwd" class="txt" size="16" maxlength="16" /> *</td></tr>
   <tr>
     <td><? echo $la_pass2 ?>:</td>
     <td valign="top" class="star"><input type="password" class="txt" name="passwd2" size="16" maxlength="16" /> *</td></tr>
<?
if ($approve_mem AND $member_ab)
{
    print "<td>$la_abb:</td><td valign=\"top\" class=\"star\">";
    print "<select name='months'>"; 
    
    $array = split(",", $member_ab);
    
    foreach ($array as $element)
    {
    	print "<option value='$element'>$element</option>";
    }
    
    print "</select> *</td></tr>";
}
     ?>
     
     
	<? include "fields.inc.php"; ?>

	<tr>
                  <td valign="top"><? echo $la_ne ?>:</td>
                  <td><input type="checkbox" name="emelding" value="1" /><? echo $la_no_email_please ?><br />
                 <input type="checkbox" name="hide_email" value="1" /><? echo $la_hide_email ?></td>
    </tr>    
	<tr>
     <td colspan="2" align="left">
     <input type="submit" name="submit" value="<? echo $la_reg ?>" /></td></tr>
 </table>
 </form>
 
     </td>
   </tr>
</table>
 
<?
}
include_once("admin/config/footer.inc.php");

?>
