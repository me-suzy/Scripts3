<?
/*
# File: admin/settings.php
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
?>
<?
// Get posted values
// ALSO: This conditional statement removes errors such as "Undefined index..."
//       which is brought about by looking for $_POST[fieldname] upon initial
//       access of the page (meaning, we haven't "posted" anything yet)
if (isset ($_POST['action']))
{
	$action = $_POST['action'];
	$ValidEmailDomains = $_POST['ValidEmailDomains'];
	$profile = $_POST['profile'];
	$defaultgroup = $_POST['defaultgroup'];
	$defaultlevel = $_POST['defaultlevel'];
	$autoapprove = $_POST['autoapprove'];
	$autosend = $_POST['autosend'];
	$autosendadmin = $_POST['autosendadmin'];
}
else
{
	$action = "";
}

// Initialize $message
$message = "";

// MODIFY SETTINGS
if ($action == "Save")
{
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);
	$updatesettings = mysql_query("UPDATE signupsetup SET validemail='$ValidEmailDomains', profile='$profile', defaultgroup = '$defaultgroup', defaultlevel = '$defaultlevel', autoapprove='$autoapprove', autosend='$autosend', autosendadmin='$autosendadmin'");
	if ($updatesettings) 
	{
		$message = "vSignup settings has been updated successfully.";
	}
}
?>
<?		
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);
	$ProfileList = mysql_query("SELECT profile FROM emailer WHERE profile <> 'Password Reminder'");
	$GroupList = mysql_query("SELECT teamname FROM authteam");
	
	$currentsettings = mysql_query("SELECT * FROM signupsetup");
	$row = mysql_fetch_array($currentsettings);
	
	$ValidEmailDomains = $row['validemail'];
	$profile = $row['profile'];
	$defaultgroup = $row['defaultgroup'];
	$defaultlevel = $row['defaultlevel'];
	$autoapprove = $row['autoapprove'];
	$autosend = $row['autosend'];
	$autosendadmin = $row['autosendadmin'];
?>

<html>
<head>
<title>vSignup Administrative Interface</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b>vSignup Administration 
  - Settings</b></font></p>
<table width="75%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000">
  <tr> 
    <td width="20%" bgcolor="#0099CC" height="16"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFCC">Administer</font></b></td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="index.php">Home</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authuser.php">Users</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authgroup.php">Groups</a></font></div>
    </td>
    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="emailer.php">Emailer</a></font></div>
    </td>

    <td width="16%" bgcolor="#FFFFCC" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<? echo $logout; ?>">Logout</a></font></div>
    </td>
  </tr>
</table><br>&nbsp;
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr valign="top"> 
    <td width="73%"> 
      <form name="FormSettings" method="POST" action="settings.php">
	    <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
          <tr bgcolor="#000000"> 
            <td colspan="2"> 
              <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3" color="#FFFFCC"><b>vSignup 
                Settings </b></font></div>
            </td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
              <?
					if ($autoapprove==1)
					{
						print "<input type=\"radio\" name=\"autoapprove\" value=\"1\" checked> Yes";
				  		print "<input type=\"radio\" name=\"autoapprove\" value=\"0\"> No";
					}
					else
					{
						print "<input type=\"radio\" name=\"autoapprove\" value=\"1\"> Yes";
				  		print "<input type=\"radio\" name=\"autoapprove\" value=\"0\" checked> No";
					}
			  	?>
              </font> </td>
            <td width="75%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              Automatically activate membership upon signup </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
              <?
					if ($autosend==1)
					{
						print "<input type=\"radio\" name=\"autosend\" value=\"1\" checked> Yes";
				  		print "<input type=\"radio\" name=\"autosend\" value=\"0\"> No";
					}
					else
					{
						print "<input type=\"radio\" name=\"autosend\" value=\"1\"> Yes";
				  		print "<input type=\"radio\" name=\"autosend\" value=\"0\" checked> No";
					}
			  	?>
              </font> </td>
            <td width="75%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              Send email notification upon signing up </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"> 
              <?
					if ($autosendadmin==1)
					{
						print "<input type=\"radio\" name=\"autosendadmin\" value=\"1\" checked> Yes";
				  		print "<input type=\"radio\" name=\"autosendadmin\" value=\"0\"> No";
					}
					else
					{
						print "<input type=\"radio\" name=\"autosendadmin\" value=\"1\"> Yes";
				  		print "<input type=\"radio\" name=\"autosendadmin\" value=\"0\" checked> No";
					}
			  	?>
              </font> </td>
            <td width="75%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              Automatically email the admin for every new signup </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center"> 
              <select name="profile">
                <? 
					while ($ProfileRow = mysql_fetch_array($ProfileList))
					{
						$ListFromProfile = $ProfileRow["profile"];
						
						if ($profile == $ListFromProfile)
						{
							print "<option value=\"$ListFromProfile\" SELECTED>$ListFromProfile</option>";
						}
						else
						{											
							print "<option value=\"$ListFromProfile\">$ListFromProfile</option>";
						}
					}	// End while
			  	?>
              </select>
            </td>
            <td width="75%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              Choose default profile if sending email notification</font> </td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center"> 
              <select name="defaultgroup">
                <? 
					while ($GroupRow = mysql_fetch_array($GroupList))
					{
						$ListFromGroup = $GroupRow["teamname"];
						
						if ($defaultgroup == $ListFromGroup)
						{
							print "<option value=\"$ListFromGroup\" SELECTED>$ListFromGroup</option>";
						}
						else
						{											
							print "<option value=\"$ListFromGroup\">$ListFromGroup</option>";
						}
					}	// End while
			  	?>
              </select>
            </td>
            <td width="75%"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
              &nbsp; Choose a default group for new members </font> </td>
          </tr>
          <tr valign="middle">
            <td width="25%" bgcolor="#33CCFF" align="center">
              <input type="text" name="defaultlevel" size="4" maxlength="4" value="<?php echo $defaultlevel; ?>">
            </td>
            <td width="75%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
              &nbsp; Choose a default level for new members </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="25%" bgcolor="#33CCFF" align="center">&nbsp; </td>
            <td width="75%"> 
              <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
                Enter a list of email domains that you want to allow signup to.<br>
                &nbsp; Leave it blank to allow anyone to sign up.<br>
                &nbsp; Include the @ in front of the email domain to ensure proper 
                processing.<br>
                &nbsp; Separate email domains with a space.<br>
                &nbsp; 
                <?
					print "<textarea name=\"ValidEmailDomains\" cols=\"50\" rows=\"7\" wrap=\"virtual\">";
					print $ValidEmailDomains;
					print "</textarea>";
                ?>
                </font></p>
            </td>
          </tr>
          <tr bgcolor="#CCCCCC" valign="middle"> 
            <td colspan="2" align="center"> 
              <input type="submit" name="action" value="Save">
              <input type="reset" name="Reset" value="Reset">
            </td>
          </tr>
        </table>
	  </form>
	  <p>&nbsp;</p>
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

      </td>
    <td width="27%">&nbsp; </td>
  </tr>
</table>

</body>
</html>
