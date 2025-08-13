<?PHP 
 

	include "../config.php"; 
	
	
{ 
	mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 8)"); 


$chk_email = mysql_db_query($database, "select refid from affiliates where refid='$ausername'");

$chkd_email = mysql_num_rows($chk_email);

if ($chkd_email != "") {
        
	include "signup3.php"; 
	
        } else {
        
	    $aemailbody = "Dear ".$afirstname.",\n\nThank you for signing up to our affiliate program.\nYour account details are below:\n\n"
	                 ."Username: ".$ausername."\nPassword: ".$apassword."\n\n"
	                 ."You can log into your account and view your 'real-time' statistics by going to:\n"
	                 ."http://".$domain."/user/index.php\n\n"
	                 ."Thank you once again, and we wish you luck with your profit making!\n\n\n"
	                 ."Affiliate Manager\n"
	                 ."".$emailinfo."\n\n\n\n";
	     
	    {   
	    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 29)"); 

		mysql_db_query($database, "INSERT INTO affiliates VALUES ('$ausername', '$apassword', '$acompany', '$atitle', '$afirstname', '$alastname', '$awebsite', '$aemail', '$apayable', '$astreet', '$atown', '$acounty', '$apostcode', '$acountry', '$aphone', '$afax', '$adate')") or die("Database INSERT Error (line 31)"); 
		}
		     
        
        include "thankyou.php"; 
        
        mail($aemail, "Welcome New Affiliate!", $aemailbody, "From:".$emailinfo."\nReply-To:".$emailinfo."\n"); 




        } 
        
        }
?> 