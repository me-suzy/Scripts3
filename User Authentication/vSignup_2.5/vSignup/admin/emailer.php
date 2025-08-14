<?
/*
# File: admin/emailer.php
# Script Name: vSignup 2.5
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vSignup is a member registration script which utilizes vAuthenticate
# for its security handling. This handy script features email verification,
# sending confirmation email message, restricting email domains that are 
# allowed for membership, and much more.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
?>
<?
	include_once ("../auth.php");
	include_once ("../authconfig.php");
	include_once ("../check.php");
	
	if ($check["team"] != "Admin")
	{
		// Feel free to change the error message below. Just make sure you put a "\" before
		// any double quote.
		print "<font face=\"Arial, Helvetica, sans-serif\" size=\"5\" color=\"#FF0000\">";
		print "<b>Illegal Access</b>";
		print "</font><br>";
  		print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"#000000\">";
		print "<b>You do not have permission to view this page.</b></font>";
		
		exit; // End program execution. This will disable continuation of processing the rest of the page.
	}	
	
	$user = new auth();
	
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);

	// Get value from posted variables
	// Check if we have instantiated $action and $act variable
	// If yes, get the value from previous posting
	// If not, set values to null or ""
	 
	if (isset($_POST['action']))
	{
		$action = $_POST['action'];
		$act = "";	
		$profile = $_POST['profile'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		$emailmessage = $_POST['emailmessage'];
	}
	elseif (isset($_GET['act']))
	{
		$act = $_GET['act'];
		$profile = $_GET['profile'];
		$action = "";
	}
	else
	{
		$action = "";
		$act = "";
		$profile = "";
		$name = "";
		$email = "";
		$subject = "";
		$emailmessage = "";
	}
	
	$currentprofile = mysql_query("SELECT * FROM emailer WHERE profile='$profile'");
	
	$message = "";
	
	// ADD PROFILE
	if ($action == "Add") {
		
		// Error Checking
		if (trim($profile) == "") {
			$message = "Profile field cannot be blank.";
			$action = "";
		}
		elseif (trim($name) == "") {
			$message = "Name field cannot be blank.";
			$action = "";
		}
		elseif (trim($email) == "") {
			$message = "Email field cannot be blank.";
			$action = "";
		}
		elseif (trim($subject) == "") {
			$message = "Subject field cannot be blank.";
			$action = "";
		}
		elseif (trim($emailmessage) == "") {
			$message = "Message field cannot be blank.";
			$action = "";
		}
		elseif (mysql_num_rows($currentprofile)) {
			$message = "Profile already exists in the database. Please enter a new one.";
			$action = "";
		}
		elseif ((substr_count($emailmessage, "[[NEWPASS]]") > 0) && ($profile != "Password Reminder"))
		{
			$message = "You cannot use [[NEWPASS]] on any profile except Password Reminder";
			$action = "";
		}
		elseif ((substr_count($emailmessage, "[[PASSWD]]") > 0) && ($profile == "Password Reminder"))
		{
			$message = "You cannot use [[PASSWD]] on the Password Reminder profile. It is strictly 
							recommended to be used on the welcome email.";
			$action = "";
			$go = false;
		}
		
		if ($action == "Add")
		{
			// Insert the new record to database
			$insertprofile = mysql_query("INSERT INTO emailer VALUES('','$profile','$email','$name','$subject','$emailmessage')");
			if ($insertprofile) {
				$message = "New profile detail has been added successfully.";
				$action = "Add New";
			}
			else{
				$message = "Error in inserting new record!";
			}
		}
	}
	
	// DELETE PROFILE
	if ($action=="Delete") 
	{
		// If Profile is Password Reminder, do not alow delete action
		if ($profile=="Password Reminder")
		{
			$message = "Password Reminder profile cannot be deleted.";
		}
		else
		{
			$deleteprofile = mysql_query("DELETE FROM emailer WHERE profile='$profile'");
		}
		
		if ($deleteprofile) {
			$profile = "";
			$name = "";
			$email = "";
			$subject = "";
			$emailmessage = "";
			$message = "The profile has been deleted.";
		}
	}
	
	// MODIFY PROFILE
	if ($action == "Modify") {
		// Error Checking
		if (trim($name) == "") {
			$message = "Name field cannot be blank.";
			$action = "";
			$go = false;
		}
		elseif (trim($email) == "") {
			$message = "Email field cannot be blank.";
			$action = "";
			$go = false;
		}
		elseif (trim($subject) == "") {
			$message = "Subject field cannot be blank.";
			$action = "";
			$go = false;
		}
		elseif (trim($emailmessage) == "") {
			$message = "Message field cannot be blank.";
			$action = "";
			$go = false;
		}
		elseif ((substr_count($emailmessage, "[[NEWPASS]]") > 0) && ($profile != "Password Reminder"))
		{
			$message = "You cannot use [[NEWPASS]] on any profile except Password Reminder";
			$action = "";
			$go = false;
		}
		elseif ((substr_count($emailmessage, "[[PASSWD]]") > 0) && ($profile == "Password Reminder"))
		{
			$message = "You cannot use [[PASSWD]] on the Password Reminder profile. It is strictly 
							recommended to be used on the welcome email.";
			$action = "";
			$go = false;
		}

		if ($action == "Modify")
		{
			// Update profile	
			$updateprofile = mysql_query("UPDATE emailer SET name='$name', email='$email', subject='$subject', emailmessage='$emailmessage' WHERE profile='$profile'");
			// echo $name . ' ' . $email . ' ' . $subject . ' ' . $emailmessage . ' ' . $profile;	// DEBUGGER
			
			if ($updateprofile) {
				$message = "Profile detail updated successfully.";
				//$action = "Add New";
			}
		}
	}
	
	// EDIT PROFILE (accessed from clicking on profile links)
	if ($act == "Edit") {
		$message = "Modify profile details.";
		$profile = $_GET['profile'];
		$currentprofile = mysql_query("SELECT * FROM emailer WHERE profile='$profile'");
		$rows = mysql_fetch_array($currentprofile);
		$name = $rows['name'];
		$email = $rows['email'];
		$subject = $rows['subject'];
		$emailmessage = $rows['emailmessage'];
	}
	
	// CLEAR FIELDS
	if ($action == "Add New") {
		$profile = "";
		$name = "";
		$email = "";
		$subject = "";
		$emailmessage = "";
		/* $message = ""; */
	}
?>

<html>
<head>
<title>vSignup Administrative Interface</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b>vSignup Administration - Emailer</b></font></p>
<table width="75%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000">
  <tr> 
    <td width="20%" bgcolor="#0099CC" height="16"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFCC">Administer</font></b></td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="index.php">Home</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="settings.php">Settings</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authuser.php">Users</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authgroup.php">Groups</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<? echo $logout; ?>">Logout</a></font></div>
    </td>
  </tr>
</table><br>&nbsp;
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr valign="top"> 
    <td width="50%"> 
      
	  <form name="AddProfile" method="Post" action="emailer.php">
	    <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
          <tr bgcolor="#000000"> 
            <td colspan="2"> 
              <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3" color="#FFFFCC"><b>PROFILE 
                DETAILS</b></font></div>
            </td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Profile 
              Name </font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <?   
			  	if (($action == "Modify") || ($action=="Add") || ($act=="Edit")) {
					print "<input type=\"hidden\" name=\"profile\" value=\"$profile\">"; 
					print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#006666\" size=\"2\">$profile</font>";
				}
				else {	
					print "<input type=\"text\" name=\"profile\" size=\"35\" maxlength=\"20\" value=\"$profile\">"; 
				}
				
			  ?>
             
			  </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Name</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <? print "<input type=\"text\" name=\"name\" size=\"35\" maxlength=\"50\" value=\"$name\">"; ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Email</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <? print "<input type=\"text\" name=\"email\" size=\"35\" maxlength=\"40\" value=\"$email\">"; ?>
             </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Subject</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <? print "<input type=\"text\" name=\"subject\" size=\"35\" maxlength=\"100\" value=\"$subject\">"; ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Message</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <? 
			  	print "<textarea name=\"emailmessage\" cols=\"33\" rows=\"10\" wrap=\"virtual\">";
			    print stripslashes($emailmessage);
				print "</textarea>";
			  ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%" bgcolor="#33CCFF"><b><font face="Verdana" size="1">&nbsp;</font></b></td>
            <td width="73%"> 
              <p><font face="Verdana" size="1"> <b>NOTES:<br>
                </b>1. The Password Reminder profile cannot be deleted because 
                it is used for sending the password reminder to members who requested 
                it.<br>
                2. Just plug in any of the available keys for this version in 
                your email message above and the system will replace it with the 
                value entered by the user during signup. For a list of available 
                keys (vSignup 2.5, please refer below).<br>
                3. Keys are case sensitive. [[UNAME]] is not the same as [[uname]] 
                or [[Uname]].<br>
                <br>
                <b>KEYS:</b></font><br>
                <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
				1. [[UNAME]] - username<br>
                2. [[PASSWD]] - password<br>
                3. [[FNAME]] - first name<br>
                4. [[LNAME]] - last name<br>
                5. [[EMAIL]] - email address<br>
                6. [[CONFIRM]] - confirmation URL + confirmation key<br>
                7. [[NEWPASS]] - random password for password reminder</font></p>
              </td>
          </tr>
          <tr bgcolor="#CCCCCC" valign="middle"> 
            <td colspan="2"> 
              <div align="center"><font size="2"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif"> 
                <?
					
				if (($action=="Add") || ($action == "Modify") || ($act=="Edit")) {
					print "<input type=\"submit\" name=\"action\" value=\"Add New\"> ";
					print "<input type=\"submit\" name=\"action\" value=\"Modify\"> ";
					print "<input type=\"submit\" name=\"action\" value=\"Delete\"> ";
				}
				else {
					print "<input type=\"submit\" name=\"action\" value=\"Add\"> ";
                }
				
				?>
                <input type="reset" name="Reset" value="Clear">
				</font></font></font></font></div>
            </td>
          </tr>
        </table>
	  </form>
	  
		
      <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
        <tr> 
          <td bgcolor="#990000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFCC">Message:</font></b></td>
        </tr>
        <tr> 
          <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000FF">
		  <?
		  	if ($message) {
			 	print $message;
		  	}
			else {
				print "<BR>&nbsp;";
			}
		  ?>
		  </font></td>
        </tr>
      </table>

      <p>&nbsp;</p>
      </td>
    <td width="50%"> 
      
	  
	  <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
        <tr bgcolor="#000000"> 
          <td colspan="5"> 
            <div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#FFFFCC"><b>PROFILE 
              LIST</b></font></div>
          </td>
        </tr>
        <tr bgcolor="#CCCCCC"> 
          <td width="30%"> 
            <div align="center"><font size="1"><b><font face="Verdana, Arial, Helvetica, sans-serif">Profile</font></b></font></div>
          </td>
          <td width="30%"> 
            <div align="center"><font size="1"><b><font face="Verdana, Arial, Helvetica, sans-serif">Name</font></b></font></div>
          </td>
          <td width="40%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Email</b></font></div>
          </td>
        </tr>

<?
	// Fetch rows from AuthUser table and display ALL users
	$listprofiles = mysql_query("SELECT * from emailer ORDER BY id");
	
	$row = mysql_fetch_array($listprofiles);
	while ($row) {  		
		print "<tr>"; 
        print "  <td width=\"30%\">";
        print "    <div align=\"left\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">";
		print "		<a href=\"emailer.php?act=Edit&profile=".$row["profile"]."\">";
		print 		$row["profile"];
		print "		</a>";
		print "	   </font></div>";
        print "  </td>";
        print "  <td width=\"30%\">";
        print "    <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">".$row["name"]."</font></div>";
        print "  </td>";
        print "  <td width=\"40%\">";
        print "    <div align=\"center\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".($row["email"])."</font></div>";
        print "  </td>";
        print "</tr>";
		
		$row = mysql_fetch_array($listprofiles);
	}
?>
     
	  </table>      
    </td>
  </tr>
</table>

</body>
</html>
