<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
require("global.php");
//---------------------------------------------------------------------
//logged in?
if($logged_in==true){
//DO PAGE HEADER
//get account title
$query="SELECT custom,usergroup FROM $user_table WHERE username = '$username';";
$result=mysql_query($query,$link);
$a_row=mysql_fetch_array($result);
$account_title=$username;
$usergroup=$a_row['usergroup'];

//TEST USERGROUP
if($usergroup!="3"){
	die("The username that you are logged in under is not an administrator.");
}

//do header
?>
<HTML>
<HEAD>
<TITLE><?php echo $title; ?> Administration Area :: <?php echo $account_title; ?></TITLE>
<link rel="STYLESHEET" type="text/css" href="../style.css">
<?php
if($action=="kb"){
?>
<style>
.header{margin-left: 10px; font-family: Verdana; font-size: 15px; color: white; font-weight: bold}
.header2{margin-left: 10px; font-family: Verdana; font-size: 13px; color: #003399; font-weight: bold}
</style>
<?php
}
?>
<meta http-equiv="REFRESH" content="300; url=<?php print $PHP_SELF."?".$QUERY_STRING; ?>">
</HEAD>
<BODY BGCOLOR="#FFFFFF" LEFTMARGIN="8" TOPMARGIN="8" MARGINHEIGHT="8" MARGINWIDTH="8">
<?php
if(isset($mssg)){
	print "<Div align=\"center\"><b>$mssg</b></Div><br>";
}
?>
<table width="100%" height="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
	<?php
	//print the admin area menu
	?>
    <td rowspan="3" valign="top">
      <UL>
	  <b>TICKETS</b>
	  <li><a href="<?php print $PHP_SELF; ?>">Respond to Tickets</a></li>
	  <li><a href="<?php print $PHP_SELF; ?>?action=searchnum">Display Ticket by Number</a></li>
	  <li>Search Tickets</li>
	  <li>Ticket Assignment</li>
	  <br><br>
	  <b>USERS</b>
	  <li><a href="<?php print $PHP_SELF; ?>?action=finduser">Manage Users</a></li>
	  <li><a href="<?php print $PHP_SELF; ?>?action=findadmin">Manage Admins</a></li>
	  <br><br>
	  <b>KNOWLEDGE BASE</b>
	  <li><a href="<?php print $PHP_SELF; ?>?action=kb">KB Main</a></li>
	  <ul>
	  	<li><a href="<?php print $PHP_SELF; ?>?action=kb&load=new">New Article</a></li>
	  	<li><a href="<?php print $PHP_SELF; ?>?action=kb&load=manage">Manage Existing Articles</a></li>
	  </ul>
	  <li>New FAQ</li>
	  <li>Manage Existing FAQs</li>
	  <li><a href="<?php print $PHP_SELF; ?>?action=subject">Subjects</a></li>
	  <ul>
	  	<li><a href="<?php print $PHP_SELF; ?>?action=subject&view=edit">Edit Exisiting Subjects</a></li>
	  	<li><a href="<?php print $PHP_SELF; ?>?action=subject&view=new">Add New Subject</a></li>
	  </ul>
	  <br>
	  <b>SETTINGS</b>
	  <li><a href="<?php print $PHP_SELF; ?>?action=settings">Change Settings</a></li>
	  <br><br>
	  <li><a href="index.php?system=logout">Log Out</a></li>
	  </UL>
	  </td>
    <td></td>
</tr>
<tr>
    <td></td>
</tr>
<tr>
    <td valign="top">
<TABLE BGCOLOR="#000000" BORDER="0" CELLPADDING="1" CELLSPACING="0" WIDTH="550">
<TBODY>
<TR>
<TD>
<TABLE BORDER="0" CELLPADDING="4" CELLSPACING="1" WIDTH="100%">
<TBODY>
<TR><TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1"><?php echo $page_title; ?> Administration Area
<?php
	$query="SELECT * FROM $ticket_table WHERE CHILD='0';";
	$result=mysql_query($query,$link);
	$total=mysql_numrows($result);
	$query="SELECT * FROM $ticket_table WHERE CHILD='0' AND STATUS='Open';";
	$result=mysql_query($query,$link);
	$open=mysql_numrows($result);
	$query="SELECT * FROM $ticket_table WHERE CHILD='0' AND STATUS='Closed';";
	$result=mysql_query($query,$link);
	$closed=mysql_numrows($result);
?><img src="pixel.gif" width="10" height="1" alt="" border="0">
REQUESTS&nbsp;[Open<b>:</b>&nbsp;<?php print $open; ?>
&nbsp;<b>|</b>&nbsp;Closed<b>:</b>&nbsp;<?php print $closed; ?>
&nbsp;<b>|</b>&nbsp;Total<b>:</b>&nbsp;<?php print $total; ?>]</FONT>
</TD></TR>
<TR><TD CLASS="headrow" COLSPAN="1"><B>Admin:</B> <?php echo $account_title; ?></TD></TR>
<TR><TD bgcolor="white">
<br>
<?php
//---------------------------------------------------------------------
	//NO ACTION
if(((!isset($action))||($action==""))&&(!isset($ticket))&&(!isset($act))){
	//show open tickets
	
	//ORDER BY DATE
	$query="SELECT NUMBER,SUBJECT,DATE,TIME,URGENCY,STATUS FROM $ticket_table WHERE CHILD=0 AND STATUS='Open' ORDER BY NUMBER DESC,DATE DESC,TIME DESC;";
	$result=mysql_query($query,$link);
	$rows=mysql_numrows($result);
?>
<!--
<TABLE BORDER="1" bordercolor="#000000" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="headrow" COLSPAN="1">&nbsp;<B>Recent Tickets</B></TD>
</TR>
</TABLE>
<TABLE BORDER="1" bordercolor="#000000" CELLPADDING="3" CELLSPACING="0" ALIGN="center" WIDTH="100%">
<TBODY>-->
<TABLE WIDTH="100%" BORDER="1" BORDERCOLOR="000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE BORDERCOLOR="000000" BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1">&nbsp;Tickets Requiring Response: <?php print $rows; ?></FONT></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="3" CELLSPACING="2" ALIGN="center">
<TBODY>
<TR>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Ticket #</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Subject</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Date</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Time</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Urgency</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Status</TD>
</TR>
<!--Tickets-->
<?php
//list tickets
if($rows>0){
	//set stop point
	$until=$rows;
	$at=0;
while($at<$until){
	$indiv=1;
	$a_row=mysql_fetch_row($result);
	foreach($a_row as $field){
		if($indiv==1){
			print"<TR>\n";
			print "<TD ALIGN=\"CENTER\" CLASS=\"indiv\"><a href=\"$PHP_SELF?ticket=";
			//link
			print $field;
			print "\">$field</a></TD>";
		}
		elseif($indiv==2){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//subject
        	print $field;
			print '</TD>';
		}
		elseif($indiv==3){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//date
       		print $field;
			print '</TD>';
		}
		elseif($indiv==4){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//time
       		print $field;
			print '</TD>';
		}
		elseif($indiv==5){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//urgency
        	if($field=="High"){
				//font color red
				print '<FONT color=#ff9900>';
			}
			else{
				//font color green
				print '<FONT color=Black>';
			}
        	print $field;
			//end font color
			print '</font>';
			print '</TD>';
		}
		elseif($indiv==6){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//status
        		if($field=="Open"){
					//font color red
					print '<FONT color=#cc0000>';
				}
				else{
					//font color green
					print '<FONT color=#00cc00>';
				}
        	print $field;
			//end font color
			print '</font>';
			print "</TD>\n</TR>";
		}
		$indiv++;
	}
	$at++;
//end while
}
//end if
}
else{
    print '<TR><TD COLSPAN="6" ALIGN="CENTER" CLASS="indiv">';
    print 'No Tickets Requiring Response';
    print '</TD></TR>';
}
?>
<!--END TICKETS-->
<?php
//END Open TICKETS PART
	}
//---------------------------------------------------------------------
elseif($action=="searchnum"){
	print "<form action=\"$PHP_SELF\" method=\"post\">";
?>
Enter Ticket Number: <input type="text" name="ticket" size="25">
<br><input type="submit" class="but" value="Display">
</form>
<?php
}
//---------------------------------------------------------------------
elseif($action=="Respond"){
		//add child ticket response
		
		//get email
		$query="SELECT ACCOUNT,SUBJECT,NAME,EMAIL,URGENCY FROM $ticket_table WHERE NUMBER='$num';";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_row($result);
		$times=1;
		foreach($a_row as $field){
			//account
			if($times==1){
				$acct=$field;
			}
			//subject
			elseif($times==2){
				$subj=$field;
			}
			//name
			elseif($times==3){
				$name=$field;
			}			
			//email
			elseif($times==4){
				$email=$field;
			}			
			//urgency
			elseif($times==5){
				$urgency=$field;
			}
			//increment times
			$times++;
		}
		
		//$number=original number
		$number=$num;
		//$account is account title
		$account=$acct;
		//$name is name
			//same
		//$email is email
			//same
		//$subject is subject
		$subject=$subj;
		//$urgency is urgency
			//same
		//$question is question
			$question=$response;
		//admin is admin
		//set child to true
		$child="1";
		//generate date in format MONTH-DAY-YEAR
		$date=date(m).'-'.date(d).'-'.date(Y);
		//generate time in format HOUR(24)-MIN-SEC
		$time=date(H).':'.date(i).':'.date(s);
		//status is status
		
		//get signature & append
		$query="SELECT sig FROM $user_table WHERE username='$account_title';";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_array($result);
		$sig=$a_row['sig'];
		if($signature=="yes"){
			$question.="\n\n";
			$question.=$sig;
		}
		
		$question=htmlentities($question);
		$question=nl2br($question);
		$question=stripslashes($question);
		
		//formulate query to insert ticket
		$query="INSERT INTO $ticket_table (NUMBER, ACCOUNT, SUBJECT, DATE, TIME, STATUS, NAME, EMAIL, URGENCY, ADMIN, CHILD, QUESTION) VALUES ('$num', '$account', '$subject', '$date', '$time', '$status', '$name', '$email', '$urgency', '$admin', '$child', '$question');";
		$result=mysql_query($query,$link);
		$query="UPDATE $ticket_table SET STATUS = '$status' WHERE NUMBER=$num;";
		$result2=mysql_query($query,$link);
		if((!$result)||(!$result2)){
			//Error!
			print '<!--<tr><td colspan=\"5\" bgcolor="white">--><b>Error!</b>';
			echo '<br>';
			if(!$result){
				print "Error in ticket insertion!";
			}
			else{
				print "Error in status update!";
			}
			echo "<br>";
			print $num;
			echo "<br>";
			print $account;
			echo "<br>";
			print $name;
			echo "<br>";
			print $email;
			echo "<br>";
			print $subject;
			echo "<br>";
			print $urgency;
			echo "<br>";
			print $question;
			echo "<br>";
			print $date;
			echo "<br>";
			print $time;
			echo "<br>";
			print $admin;
			echo "<br>";
			print $child;
			echo "<br>";
			print $status;
			print "<br><br>";
			print mysql_error();
			print '<br>';
			//show recent button
			recent_button();
			//echo "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
		}
		else{
			//Success!
			print '<!--<tr><td colspan=\"5\" bgcolor="white">--><b>Success!</b>';
			echo "<br>";
			print "Your response to Ticket # $num was successfully added.";
			echo "<br>";
			//show recent button
			recent_button();
			//print '</td></tr></TD></TR></TABLE></TD></TR></TABLE>';
			
			//send alert email to user using messages set in config
			alert_mail($ae_mail,$a_subject,$a_message);
			
		}
//end add
}
//---------------------------------------------------------------------
elseif($action=="modify"){
	if($saction=="Delete"&&$confirm=="Yes"){
		//OK to delete
		$query="DELETE FROM $ticket_table WHERE NUMBER = '$number' AND SUBJECT = '$subject';";
		$rs=mysql_query($query) or die("Delete Failed!");
		$mssg="Ticket%20and%20Responses%20Deleted";
		//urlencode($mssg);
		$url="$PHP_SELF?mssg=$mssg";
	}
	else{
		//Not OK to delete
		$url=$urlback;
	}
	//new location
	print '<a href="'.$url.'">Click to Continue</a>';
}
//---------------------------------------------------------------------
elseif($action=="findadmin"){
//send username as $user5 to ?action=user
	print "<form action=\"$PHP_SELF?action=admin\" method=\"post\">";
?>
Enter Username: <input type="text" name="user5" size="25">
<br><input type="submit" class="but" value="Manage">
</form>
<?php
}//end find admin
//---------------------------------------------------------------------
elseif($action=="admin"){
	if(!isset($edit)){
		//manage admins
		$result=mysql_query("SELECT * FROM $user_table WHERE username = '$user5';");
		$value=mysql_fetch_array($result);
?>
<form action="<?php print $PHP_SELF; ?>?action=admin" method="post">
<FONT face=Verdana><b><?php print $user5; ?>'s status: </b></FONT>
<input type="hidden" name="edit" value="true">
<input type="hidden" name="user5" value="<?php print $user5; ?>">
<select class="prefinput" name="isadmin" size="1">
	<?php
	if($value['usergroup']==3){
	?>
<option value="1">Normal User</option>
<option value="3" SELECTED>Administrator</option>
	<?php
	}
	else{
	?>
<option value="1" SELECTED>Normal User</option>
<option value="3">Administrator</option>
	<?php
	}//end else
	?>
</select>&nbsp;&nbsp;
<input type="submit" value="Modify" class="but">
</form>
<?php
	}//end edit is not set
	elseif($edit==true){
	//update user table
	$sql="UPDATE $user_table SET usergroup='".$isadmin."' WHERE username='".$user5."';";
	//Process Query
	$err=false;
	$result="";
	if(!$result=mysql_query($sql)){
		print "<p>Error in updating data!<br>";
		print mysql_error();
		print '<br><a href="';
		print $PHP_SELF;
		print '?action=admin&user5='.$user5.'">Click Here to try again</a><br><br>';
		print "$sql</p>";
		$err=true;
	}//end error
	
	if($err!=true){
		?><br><br><div align="center">
		<STRONG><FONT face=Verdana size=2>All settings have been updated.</FONT></STRONG>
		<?php
		print '<br><br><a href="';
		print $PHP_SELF;
		print '?action=admin&user5='.$user5.'">Click Here to Continue</a></div><br><br>';
	}//end no error
	else{
		print "Error in updating information.  Please try again.";
	}//end error
	
	}//end edit is == true
}//end ADMINS
//---------------------------------------------------------------------
elseif($action=="finduser"){
//send username as $user5 to ?action=user
	print "<form action=\"$PHP_SELF?action=user\" method=\"post\">";
?>
Enter Username: <input type="text" name="user5" size="25">
<br><input type="submit" class="but" value="Display">
</form>
<?php
}//end find user
//---------------------------------------------------------------------
elseif($action=="user"){
//show/edit user preferences for the present user
	print '<FONT FACE="Verdana"><P ALIGN="CENTER"><b>';
	if(!isset($edit)){//check to see if edit var is set
	/*
	print out a form field for each below bit
	have the user submit the form with editted fields
	password blank for no change - click on link to change = popup
	*/
	$result=mysql_query("SELECT * FROM $user_table WHERE username = '$user5';");
	$value=mysql_fetch_array($result);
	?>
	<script language="JavaScript" type="text/javascript">
	//check to make sure that email is valid
	function validEmail(email){
		invalidChars=" /:,;";
		if(email==""){
			return false
		}
		for(i=0; i<invalidChars.length; i++){
			badChar = invalidChars.charAt(i);
			if(email.indexOf(badChar,0)>-1){
				return false
			}
		}//end for
		atPos=email.indexOf("@",1);
		if(atPos==-1){
			return false
		}
		if(email.indexOf("@",atPos+1)>-1){
			return false
		}
		periodPos=email.indexOf(".",atPos);
		if(periodPos==-1){
			return false
		}
		if(periodPos+3 > email.length){
			return false
		}
		return true
	}//end validEmail
	
	function submitIt(prefForm){
		if(!validEmail(prefForm.email.value)){
			alert("Invalid Email Address");
			prefForm.email.focus();
			prefForm.email.select();
			return false
		}
		return true
	}//end submitIt
	
	function passChg(){
		window.open('passchg.php', 'newwindow', config='height=400, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no')
	}//end passChg
	
	//show status bar messages
	var statusmsg="Change your password"
	function statusbar(){
		window.status=statusmsg
		return true
	}
	
	var statusmsg1=""
	function statusbar2(){
		window.status=statusmsg1
		return true
	}
	//end show status bar message functions
	</script>
	<style type="text/css">
	<!--
	.prefinput{
		color: #333333;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 11px;
		font-weight: normal;
		border-color: #333333;
		text-indent: 2px; 
		border-top-width: 1px;
		border-right-width: 1px;
		border-bottom-width: 1px;
		border-left-width: 1px; 
		background: #f8f8f8;
	}
	-->
	</style>
	<font size="3">Profile for <u><?php print $user5; ?></u></font></b></p>
	<form onSubmit="return submitIt(this)" action="<?php print $PHP_SELF; ?>" method="post" name="prefs">
	<input type="hidden" name="edit" value="true">
	<input type="hidden" name="action" value="user">
	<input type="hidden" name="user5" value="<?php print $user5; ?>">
	<table width="70%" align="center" cellspacing="0" cellpadding="2" border="0">
	<tr>
	    <td colspan="2">Use the below form to edit the user's profile.</td>
	</tr>
	<tr>
	    <td colspan="2" bgcolor="#b7b7b7"><b>User Profile</b></td>
	</tr>
	<tr><td colspan="2" bgcolor="black"></td></tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right><b>Contact Information:</b>&nbsp;</P></td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Email Address:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="email" value="<?php print $value['email'];?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Homepage:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="homepage" value="<?php print $value['homepage']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Homepage Description:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="homepagedesc" value="<?php print $value['homepagedesc']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right><b>Instant Messaging:</b>&nbsp;</P></td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>ICQ:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="icq" value="<?php print $value['icq']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>AIM:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="aim" value="<?php print $value['aim']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Yahoo:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="yahoo" value="<?php print $value['yahoo']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>MSN:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="msn" value="<?php print $value['msn']; ?>" size="25"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right><b>Login Information:&nbsp;</b></P></td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Username:&nbsp;</P></td>
	    <td>&nbsp;<?php print $value['username'];?></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Password:&nbsp;</P></td>
	    <td>&nbsp;****** (<a href="javascript:passChg()" onMouseover="return statusbar()" onMouseout="return statusbar2()">click to change</a>)</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right><b>Other Information:</b>&nbsp;</P></td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Biography:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="note" value="<?php print $value['note']; ?>" size="20"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Occupation:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="occupation" value="<?php print $value['occupation']; ?>" size="20"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Location:&nbsp;</P></td>
	    <td>&nbsp;<input class="prefinput" type="text" name="location" value="<?php print $value['location']; ?>" size="20"></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Signature:&nbsp;</P></td>
	    <td>&nbsp;<textarea cols="40" rows="7" name="sig" class="prefinput"><?php print $value['sig']; ?></textarea></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right><b>User Information:</b>&nbsp;</P></td>
	    <td>&nbsp;</td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Date Registered:&nbsp;</P></td>
	    <td>&nbsp;<?php print date("l F d, Y",$value['joindate']); ?></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Number of Tickets Submitted:&nbsp;</P></td>
	    <td>&nbsp;<?php print $value['TICKETS']; ?></td>
	</tr>
	<tr>
	    <td bgcolor="#efefef">
	      <P align=right>Usergroup:&nbsp;</P></td>
	    <td>&nbsp;<?php print $value['usergroup']; ?></td>
	</tr>
	<tr><td colspan="2" bgcolor="black"></td></tr>
	<tr>
	    <td colspan="2" bgcolor="#b7b7b7">
	      <P align=center><input type="submit" value="Modify" class="but"></P></td>
	</tr>
	</table></form>
	<?php
	}
	elseif($edit==true){
		//make changes
	
	$sqlcode=array("UPDATE $user_table SET email='$email', homepage='$homepage', homepagedesc='$homepagedesc', icq='$icq', aim='$aim', yahoo='$yahoo', msn='$msn', note='$note', occupation='$occupation', location='$location', sig='$sig' WHERE username = '$user5';");
	
	foreach($sqlcode as $sql){
		$result="";
		if(!$result=mysql_query($sql)){
			print "<p>Error in updating data!<br>";
			print mysql_error();
			print '<br><a href="';
			print $PHP_SELF;
			print '?loc=pref">Click Here to try again</a><br><br>';
			print "$sql</p>";
			$err=true;
		}//end error
	}//end loop
	
	if($err!=true){
	?><br><br><div align="center">
	<STRONG><FONT face=Verdana size=2>All settings have been updated.</FONT></STRONG>
	<?php
		print '<br><br><a href="';
		print $PHP_SELF;
		print '?action=finduser">Click Here to Continue</a></div><br><br>';
	}//end no error
	else{
		print "Error in updating information.  Please try again.";
	}
	
	}//end
}//END USER
//---------------------------------------------------------------------
elseif($action=="settings"){
	//////////////////////////////////////////////////////////////////////
	if(!isset($edit)){
	//Main Screen
		?>
		<form action="<?php echo $PHP_SELF."?".$QUERY_STRING; ?>" method="post">
		<input type="hidden" name="edit" value="modify">
		<table width="550" align="center" cellspacing="0" cellpadding="2" border="0">
		<tr>
		    <td colspan="2" bgcolor="#b7b7b7"><b>System Settings</b></td>
		</tr>
		<tr><td colspan="2" bgcolor="black"></td></tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Path to Temporary Directory (with trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="session_path1" size="45" value="<?php echo $setting["session_path"]; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Path to SSM (with trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="base_path1" size="45" value="<?php echo $base_path; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to SSM (with trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="web_path1" size="45" value="<?php echo $web_path; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to Administration Area (with trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="admin_web_path1" size="45" value="<?php echo $admin_web_path; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<!--<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Path to OpenBB (no trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="openbb_path" size="45" value="<?php //echo $openbb_path; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>-->
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Web URL to OpenBB (with trailing slash)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="openbb_webpath1" size="45" value="<?php echo $openbb_webpath; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>System Title&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="title1" size="45" value="<?php echo $title; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Email Alerts On/Off&nbsp;</P></td>
		    <td>&nbsp;<select name="alerts1" size="1" class="prefinput">
					<?php
						if($alerts=="ON"){
						?>
						<option value="ON" SELECTED>ON</option>
						<option value="OFF">OFF</option>
						<?php
						}
						else{
						?>
						<option value="ON">ON</option>
						<option value="OFF" SELECTED>OFF</option>
						<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Reply to Address for User Alerts&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="reply_to1" size="35" value="<?php echo $reply_to; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>User Email Alert Message&nbsp;</P></td>
		    <td>&nbsp;<textarea class="prefinput" cols="40" rows="5" name="a_message1"><?php echo $a_message; ?></textarea></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>User Email Alert Subject&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="a_subject1" size="40" value="<?php echo $a_subject; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Email Address (for admin alerts)&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="admin_email_1" size="35" value="<?php echo $admin_email; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Alert Email Subject&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="ae_subj1" size="40" value="<?php echo $ae_subj; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Administrator Alert Email Message&nbsp;</P></td>
		    <td>&nbsp;<textarea class="prefinput" cols="40" rows="5" name="ae_mssg1"><?php echo $ae_mssg; ?></textarea></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Registration System (if you have OpenBB installed, we encourage you to use OpenBB)&nbsp;</P></td>
		    <td>&nbsp;<select name="reg_system1" size="1" class="prefinput">
					<?php
						if($reg_system=="1"){
						?>
						<option value="1" SELECTED>OpenBB System</option>
						<option value="2">SSM System</option>
						<?php
						}
						else{
						?>
						<option value="1">OpenBB System</option>
						<option value="2" SELECTED>SSM System</option>
						<?php
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Client Side Body Background Color&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="background1" size="15" value="<?php echo $background; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">
		      <P align=right>Client Side Main Table Background Color&nbsp;</P></td>
		    <td>&nbsp;<input type="text" name="maintabc1" size="15" value="<?php echo $maintabc; ?>"></td>
		</tr>
		<tr>
		    <td bgcolor="#efefef">&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr><td colspan="2" bgcolor="black"></td></tr>
		<tr>
		    <td colspan="2" bgcolor="#b7b7b7">
		      <P align=center><input class="but" type="submit" value="Edit Settings" class="button"></P></td>
		</tr>
		</table>
		<input type="hidden" name="edit" value="true">
		</form>
		<?php
	}
	//////////////////////////////////////////////////////////////////////
	//Make database changes
	else{
		$base_path=addslashes($base_path);
		//$openbb_path=addslashes($openbb_path);
		$sqlarray=array(
			"UPDATE $config_table SET base_path='".$base_path1."';",
			"UPDATE $config_table SET web_path='".$web_path1."';",
			"UPDATE $config_table SET admin_web_path='".$admin_web_path1."';",
			//"UPDATE $config_table SET openbb_path='$openbb_path'",
			"UPDATE $config_table SET openbb_web_path='".$openbb_webpath1."';",
			"UPDATE $config_table SET session_path='".$session_path1."';",
			"UPDATE $config_table SET background='".$background1."';",
			"UPDATE $config_table SET maintable_background='".$maintabc1."';",
			"UPDATE $config_table SET title='".$title1."';",
			"UPDATE $config_table SET alerts='".$alerts1."';",
			"UPDATE $config_table SET reply_to='".$reply_to1."';",
			"UPDATE $config_table SET a_message='".$a_message1."';",
			"UPDATE $config_table SET a_subject='".$a_subject1."';",
			"UPDATE $config_table SET admin_email='".$admin_email_1."';",
			"UPDATE $config_table SET ae_subject='".$ae_subj1."';", 
			"UPDATE $config_table SET ae_message='".$ae_mssg1."';",
			"UPDATE $config_table SET reg_system='".$reg_system1."';"
		);
		//Process Query
		
		$err=false;
		foreach($sqlarray as $sql){
			$result="";
			if(!$result=mysql_query($sql)){
				print "<p>Error in updating data!<br>";
				print mysql_error();
				print '<br><a href="';
				print $PHP_SELF;
				print '?action=settings">Click Here to try again</a><br><br>';
				print "$sql</p>";
				$err=true;
			}//end error
		}//end loop
		if($err!=true){
			?><br><br><div align="center">
			<STRONG><FONT face=Verdana size=2>All settings have been updated.</FONT></STRONG>
			<?php
				print '<br><br><a href="';
				print $PHP_SELF;
				print '?action=settings">Click Here to Continue</a></div><br><br>';
		}//end no error
		else{
			print "Error in updating information.  Please try again.";
		}
	}
	//////////////////////////////////////////////////////////////////////
}//END action=SETTINGS
//---------------------------------------------------------------------
elseif($action=="kb"){
	//---------------------------------------------------------------------
	if(!isset($load)){
?>
<table width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td colspan="2" class="" bgcolor="#204D79">
<div class="header"><?php print $title; ?></div>
<div class="header">Knowledge Base</div>
     </td>
</tr>
<tr>
    <td><div class="header2">Knowledge Base Administration</div></td>
    <td>
      <P align=center>
	  <INPUT TYPE="button" Name="load" onClick="window.open('<?php print $PHP_SELF; ?>?action=kb&load=help&area=main', 'newwindow', config='height=400, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no');" Value="Help" class="button">
	  </P>
	</td>
</tr>
<tr>
    <td colspan="2">Please use the below options to manage 
      you knowledge base.&nbsp; Use the above Help button to read information on using 
      this portion of the system.</td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>

<tr>
	<td colspan="2" bgcolor="white">
	<ul>
	<li><a href="<?php print $PHP_SELF; ?>?action=kb&load=new">Create a New Article</a><br>
	Use this option to add articles to your knowledge base.  It is 
	these articles that users will read and search to attempt to 
	solve their issues before requesting support.</li>
	</ul>
	</td>
</tr>
<tr>
	<td colspan="2" bgcolor="white">
	<ul>
	<li><a href="<?php print $PHP_SELF; ?>?action=kb&load=manage">Manage Existing Articles</a><br>
	Use this option to manage articles that you have already added to 
	your knowledge base.  This option allows you to preview, modify, 
	and delete existing articles.</li>
	</ul>
	</td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
</table>
<?php
}//end no load command
//---------------------------------------------------------------------
elseif(isset($load)){
	if($load=="new"){
?>
<table width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td colspan="2" class="" bgcolor="#204D79">
<div class="header"><?php print $title; ?></div>
<div class="header">Knowledge Base</div>
     </td>
</tr>
<tr>
    <td nowrap><div class="header2">Create a New Article</div></td>
    <td>
      <P align=center><INPUT TYPE="button" Name="load" onClick="window.open('<?php print $PHP_SELF; ?>?action=kb&load=help&area=new', 'newwindow', config='height=400, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no');" Value="Help" class="button">&nbsp;&nbsp;<INPUT TYPE="button" Name="load" onclick="window.location.href='<?php print $PHP_SELF; ?>?action=kb';" Value="Cancel" class="button"></P></td>
</tr>
<tr>
    <td colspan="2">Create a new article using the form 
      below.&nbsp; HTML content is allowed - make sure that you change the setting to reflect your content.  If you are entering HTML to be displayed as <i>code</i> to your user, turn HTML OFF.</td>
</tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7"><b>New Article</b>&nbsp;&nbsp;[Author: <?php print $username; ?>]</td>
</tr>
<form action="<?php print $PHP_SELF; ?>?act=add" method="post">
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Title:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
    <td nowrap><img src="../pixel.gif" width="5" height="1" alt="" border="0"><input type="text" name="atitle" size="25" class="prefinput"></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Insert&nbsp;Under:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
    <td><img src="../pixel.gif" width="5" height="1" alt="" border="0"><?php
print '<SELECT NAME="subject" class="prefinput">
<OPTION VALUE="notvalid">Select a Subject&nbsp;:</OPTION>';
$query="SELECT VALUE FROM $subj_table ORDER BY NUMBER ASC;";
if($result=mysql_query($query,$link)){
	while($row=mysql_fetch_array($result)){
		print "<OPTION VALUE=\"$row[VALUE]\">&#149;&nbsp;$row[VALUE]</OPTION>";
	}
}
print '</SELECT>';
?>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=subject&view=new">(new subject)</a></td>
</tr>
<tr>
    <td bgcolor="#efefef">
      <P align=right>Content:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
    <td><img src="../pixel.gif" width="5" height="1" alt="" border="0"><textarea cols="60" rows="10" name="content" class="prefinput"></textarea></td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7">
      <P align=center>
		<select name="html" size="1" class="prefinput">
			<option value="on">HTML ON</option>
			<option value="off">HTML OFF</option>
		</select>
<input type="submit" value="Create" class="but"></P></td>
</tr>
</form>
</table>
<?php
	}//end new
	elseif($load=="manage"){
?>
<table width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td colspan="2" class="" bgcolor="#204d79">
<div class="header"><?php print $title; ?></div>
<div class="header">Knowledge Base</div>
     </td>
</tr>
<tr>
    <td><div class="header2">Manage Existing Articles</div></td>
    <td>
      <P align=center><INPUT TYPE="button" Name="load" onClick="window.open('<?php print $PHP_SELF; ?>?action=kb&load=help&area=manage', 'newwindow', config='height=400, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no');" Value="Help" class="button">&nbsp;&nbsp;<INPUT TYPE="button" Name="load" onclick="window.location.href='<?php print $PHP_SELF; ?>?action=kb&load=new';" Value="New Article" class="button"></P></td>
</tr>
<tr>
    <td colspan="2">Your existing categories and their 
      associated articles are displayed below.&nbsp; Use the functions on the 
      right side of the table to modify, preview, or 
      delete your articles and categories.</td>
</tr>
<tr>
    <td bgcolor="#b7b7b7">
      <P align=center><STRONG>Document Name</STRONG></P> </td>
	<td bgcolor="#b7b7b7">
      <P align=center><STRONG>Functions</STRONG></P></td>
</tr>
<tr><td colspan="2" bgcolor="black"></td></tr>
<script language="JavaScript" type="text/javascript">
<!--Hide Script from Old Browsers
//Check to make sure that the user really wants to close request
function doVerify(closerequest){
	var txt="Are you sure that you want to delete this item?\nThere is no way to reverse this delete command once executed!\nClick OK to Delete or CANCEL to leave the item as is."
	if(!confirm(txt)){
		alert("Delete Canceled!");
		return false;
	}
	else{
		return true;
	}
}
//End Hide-->
</script>
<?php
//LIST CATEGORIES & ARTICLES
$query="SELECT NUMBER,VALUE FROM $subj_table ORDER BY NUMBER ASC;";
$result=mysql_query($query);
while($row=mysql_fetch_array($result)){
?>
<!--CATEGORY-->
<tr>
    <td bgcolor="#dbdbdb">
      <P align=left><STRONG><?php print "$row[VALUE]"; ?></STRONG></P></td>
    <td bgcolor="#dbdbdb" valign="middle">
	<!--FUNCTION TABLE-->
		<table align="center" height="10" cellspacing="0" cellpadding="2" border="0">
			<tr>
   				<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=subject&view=edit">Modify</a>&nbsp;</td>
    			<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?act=preview&type=cat&id=<?php print $row['VALUE']; ?>">Preview</a>&nbsp;</td>
    			<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=subject&view=edit">Delete</a>&nbsp;</td>
			</tr>
		</table>
	<!--END FUNCTION TABLE-->
	</td>
</tr>
<!--END CATEGORY-->
<?php
	//LIST ARTICLES FOR EACH TITLE
	$sql="SELECT id,title FROM $kbart_table WHERE category='$row[VALUE]';";
	$result1=mysql_query($sql);
		while($row1=mysql_fetch_array($result1)){//while2
?>
<!--DOCUMENT-->
<tr>
    <td bgcolor="#efefef">
      <P align=left><IMG height=1 alt="" src="pixel.gif" width=15 border=0><b>-</b><?php print $row1['title']; ?></P></td>
    <td bgcolor="#efefef" valign="middle">
	<!--FUNCTION TABLE-->
		<table align="center" height="10" cellspacing="0" cellpadding="2" border="0">
			<tr>
   				<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?act=modify&type=art&id=<?php print $row1['id']; ?>">Modify</a>&nbsp;</td>
    			<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?act=preview&type=art&id=<?php print $row1['id']; ?>&c=<?php print $row['VALUE']; ?>">Preview</a>&nbsp;</td>
    			<td>&nbsp;<a href="<?php print $PHP_SELF; ?>?act=delete&type=art&id=<?php print $row1['id']; ?>" onclick="return doVerify(this);">Delete</a>&nbsp;</td>
			</tr>
		</table>
	<!--END FUNCTION TABLE-->
	</td>
</tr>
<!--END DOCUMENT-->
<?php
		}//end while2
	//END ARTICLE LIST
}//end while
?>
<tr><td colspan="2" bgcolor="black"></td></tr>
<tr>
    <td colspan="2" bgcolor="#b7b7b7">&nbsp;</td>
</tr>
</table>
<?php
	}//end manage
	elseif($load=="help"){
		//DO HELP-organized by area
	}
	else{
		print "You did not select a valid choice";
	}//end no valid command
}//end load command set
kb_footer();//print kb footer
}
//---------------------------------------------------------------------
elseif($action=="subject"){

	if(!isset($saction)){
?>
<table align="center" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td valign="top">
      <UL type=square>
	  <li><a href="<?php print $PHP_SELF; ?>?action=subject&view=main">Main</a></li>
	  <li><a href="<?php print $PHP_SELF; ?>?action=subject&view=edit">Edit</a></li>
	  <li><a href="<?php print $PHP_SELF; ?>?action=subject&view=new">New</a></li>
	  </UL>
	</td>
    <td valign="top">
<?php
if($view=="main"){
	screen_main($link,$subj_table);
}
elseif($view=="edit"){
	screen_edit($link,$subj_table,$PHP_SELF);
}
elseif($view=="new"){
	screen_new($PHP_SELF);
}
else{
	//same as main
	screen_main($link,$subj_table);
}
?>
	</td>
</tr>
</table>
<?php
}
//sAction is set
else{
	if($saction=="Add"){
		//add new subject
		if(isset($subj)){
			$query="INSERT INTO $subj_table (NUMBER, VALUE) VALUES ('', '$subj')";
			if($result=mysql_query($query,$link)){
				$mssg="Subject%20Added";
				print '<a href="'.$PHP_SELF.'?action=subject&mssg='.$mssg.'">Click To Continue</a>';
			}
			else{
				print "Error!<br>$sql<br>".mysql_error();
			}
		}
	}
	elseif($saction=="Delete"){
		//delete specified subject
		if(isset($num)){
			$query="DELETE FROM $subj_table WHERE NUMBER = '$num'";
			if($result=mysql_query($query,$link)){
				$mssg="Subject%20Deleted";
				print '<a href="'.$PHP_SELF.'?action=subject&view=edit&mssg='.$mssg.'">Click To Continue</a>';
			}
			else{
				print "Error!<br>$sql<br>".mysql_error();
			}
		}
	}
	elseif($saction=="Edit"){
		//edit subjects
		if(isset($num)&&isset($val)){
			$query="UPDATE $subj_table SET VALUE='$val' WHERE NUMBER='$num';";
			if($result=mysql_query($query,$link)){
				//Removed from the subject table!
				$query="UPDATE $kbart_table SET category='$val' WHERE category='$preval';";
				if($result=mysql_query($query,$link)){
					$mssg="Subject%20Edited";
					print '<a href="'.$PHP_SELF.'?action=subject&view=edit&mssg='.$mssg.'">Click To Continue</a>';
				}
				else{
					print "Error!<!--KB Table--><br>$sql<br>".mysql_error();
				}
			}//end successful query
			else{
				print "Error!<!--Subject Table--><br>$sql<br>".mysql_error();
			}
		}
	}
}

//END SUBJECTS
}
//---------------------------------------------------------------------
if(isset($act)){
	if($act=="add"){
		//Add a new topic to the KB
		$author=$username;
		//VARIABLES:
		//	$author
		//	$subject
		//	$atitle
		//	$content
		
		//generate id ($id)
		$sql="SELECT id FROM $kbart_table ORDER BY id DESC;";
		$result=mysql_query($sql);
		$a_row=mysql_fetch_row($result);
		foreach($a_row as $field){
			$last=$field;
			break;
		}
		$id=$last+1;
		
		if($html=="off"){
			//HTML IS OFF
			//convert HTML Special Chars to Correct Notation
			$content=htmlspecialchars($content);
		}
		//otherwise, leave HTML content as is
		//convert newlines to characters
		$content=nl2br($content);
		
		$sql="INSERT INTO $kbart_table (`id`, `category`, `title`, `content`, `author`) VALUES ('$id', '$subject', '$atitle', '$content', '$author');";
		$result=mysql_query($sql);
		if(!$result){
			print "Error adding the new article to the database.  Please try again.";
		}
		else{
			print '<a href="'.$PHP_SELF.'?action=kb&load=manage">Click Here to Continue</a>';
		}
	}//end add
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	elseif($act=="add_m"){
		//Modify a topic in the KB
		$author=$username;
		//VARIABLES:
		//	$author
		//	$subject
		//	$atitle
		//	$content
		//	$id
		
		if($html=="off"){
			//HTML IS OFF
			//convert HTML Special Chars to Correct Notation
			$content=htmlspecialchars($content);
		}
		//otherwise, leave HTML content as is
		//convert newlines to characters
		$content=nl2br($content);
		
		$sql="UPDATE $kbart_table SET category='$subject', title='$atitle', content='$content', author='$author' WHERE id='$id';";
		$result=mysql_query($sql);
		if(!$result){
			print "Error editing the article in the database.  Please try again.";
		}
		else{
			print '<a href="'.$PHP_SELF.'?action=kb&load=manage">Click Here to Continue</a>';
		}
	}//end Modify add
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	elseif($act=="modify"){
		//MODIFY
		if($type=="art"){
			//article
			$sql="SELECT * FROM $kbart_table WHERE id='$id';";
			$result=mysql_query($sql);
			$row=mysql_fetch_array($result);
			?>
			<style>
				.header{margin-left: 10px; font-family: Verdana; font-size: 15px; color: white; font-weight: bold}
				.header2{margin-left: 10px; font-family: Verdana; font-size: 13px; color: #003399; font-weight: bold}
			</style>
			<table width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
			<tr>
			    <td colspan="2" class="" bgcolor="#204D79">
			<div class="header"><?php print $title; ?></div>
			<div class="header">Knowledge Base</div>
			     </td>
			</tr>
			<tr>
			    <td nowrap><div class="header2">Modify <u><?php print $row['title']; ?></u></div></td>
			    <td>
			      <P align=center><INPUT TYPE="button" Name="load" onClick="window.open('<?php print $PHP_SELF; ?>?action=kb&load=help&area=new', 'newwindow', config='height=400, width=400, toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no');" Value="Help" class="button">&nbsp;&nbsp;<INPUT TYPE="button" Name="load" onclick="window.location.href='<?php print $PHP_SELF; ?>?action=kb';" Value="Cancel" class="button"></P></td>
			</tr>
			<tr>
			    <td colspan="2">Modify the article using the form 
			      below.&nbsp; HTML content is allowed - make sure that you change the setting to reflect your content.  If you are entering HTML to be displayed as <i>code</i> to your user, turn HTML OFF.</td>
			</tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7"><b><?php print $row['title']; ?></b>&nbsp;&nbsp;[Author: <?php print $username; ?>]</td>
			</tr>
			<form action="<?php print $PHP_SELF; ?>?act=add_m" method="post">
			<input type="hidden" name="id" value="<?php print $row['id']; ?>">
			<tr><td colspan="2" bgcolor="black"></td></tr>
			<tr>
			    <td bgcolor="#efefef">
			      <P align=right>Title:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
			    <td nowrap><img src="../pixel.gif" width="5" height="1" alt="" border="0"><input type="text" name="atitle" size="25" class="prefinput" value="<?php print $row['title']; ?>"></td>
			</tr>
			<tr>
			    <td bgcolor="#efefef">
			      <P align=right>Insert&nbsp;Under:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
			    <td><img src="../pixel.gif" width="5" height="1" alt="" border="0"><?php
			print '<SELECT NAME="subject" class="prefinput">
			<OPTION VALUE="notvalid">Select a Subject&nbsp;:</OPTION>';
			$query="SELECT VALUE FROM $subj_table ORDER BY NUMBER ASC;";
			if($result=mysql_query($query,$link)){
				while($row1=mysql_fetch_array($result)){
					if($row1['VALUE']==$row['category']){
						print "<OPTION VALUE=\"$row1[VALUE]\" SELECTED>&#149;&nbsp;$row1[VALUE]</OPTION>";
					}
					else{
						print "<OPTION VALUE=\"$row1[VALUE]\">&#149;&nbsp;$row1[VALUE]</OPTION>";
					}
				}//end while
			}
			print '</SELECT>';
			?>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=subject&view=new">(new subject)</a></td>
			</tr>
			<tr>
			    <td bgcolor="#efefef">
			      <P align=right>Content:<img src="../pixel.gif" width="10" height="1" alt="" border="0"></P></td>
			    <td><img src="../pixel.gif" width="5" height="1" alt="" border="0"><textarea cols="60" rows="10" name="content" class="prefinput"><?php print $row['content']; ?></textarea></td>
			</tr>
			<tr><td colspan="2" bgcolor="black"></td></tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7">
			      <P align=center>
					<select name="html" size="1" class="prefinput">
						<option value="on">HTML ON</option>
						<option value="off">HTML OFF</option>
					</select>
			<input type="submit" value="Save" class="but"></P></td>
			</tr>
			</form>
			</table>
			<?php
		}
		//cats are being handled by the subject manager
		else{
			print "Invalid Type";
		}
	}//end modify
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	elseif($act=="delete"){
		//DELETE
		if($type=="art"){
			//article
			//id=$row1['id']
			$query="DELETE FROM $kbart_table WHERE id='$id'";
			if($result=mysql_query($query,$link)){
				$mssg="Item%20Deleted";
				print '<a href="'.$PHP_SELF.'?action=kb&load=manage&mssg='.$mssg.'">Click To Continue</a>';
			}
			else{
				print "Error!<br>$sql<br>".mysql_error();
			}
		}//end art delete
		//cats are being handled by the subject manager
		else{
			print "Invalid Type";
		}
	}//end delete
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	elseif($act=="preview"){
		//PREVIEW
		if($type=="cat"){
			//article
			?>
			<style>
				.header{margin-left: 10px; font-family: Verdana; font-size: 15px; color: white; font-weight: bold}
				.header2{margin-left: 10px; font-family: Verdana; font-size: 13px; color: #003399; font-weight: bold}
			</style>
			<table align="center" cellspacing="0" cellpadding="2" border="0">
			<tr>
			    <td colspan="2" class="" bgcolor="#204D79">
					<div class="header"><?php print $title; ?></div>
					<div class="header">Knowledge Base</div>
			     </td>
			</tr>
			<tr>
			    <td><div class="header2">Preview</div></td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
			    <td colspan="2">This page shows you what the <b><?php print $id; ?></b> category will look like to your users in the Knowledge Base.</td>
			</tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7">Category Preview</td>
			</tr>
			<tr><td colspan="2" bgcolor="black"></td></tr>
			<tr>
		    <td bgcolor="white">
			<table width="100%" cellpadding="3">
			<tr>
			  <td valign="top" width="1%"><img src="pixel.gif" width="1" height="8"></td>
			  <td width="99%" valign="top">
				<div class="header2">Category: <?php print $id; ?></div>
				<div class="">&nbsp;</div>
				<div class="">
				The following articles are associated with this category:<br><br>
			<?php
			//LIST ARTICLES FOR THE CATEGORY
			$sql="SELECT id,title FROM $kbart_table WHERE category='$id';";
			$result1=mysql_query($sql);
				while($row1=mysql_fetch_array($result1)){//while
			?>
			<!--DOCUMENT-->
			<table cellpadding="1" cellspacing="1">
			  <tr><td class="sb2">
			    &nbsp;&nbsp;<a href="<?php print $PHP_SELF; ?>?load=display&art=<?php print $row1['id']; ?>&c=<?php print $cat; ?>" target="mainbody"><?php print $row1['title']; ?></a>
			  </td></tr>
			</table>
			<!--END DOCUMENT-->
			<?php
			}//end while
			?>
			</div>	
			</td></tr>
			</table>
			<!--END KB CONTAINER-->
			<tr>
				<td colspan="2" bgcolor="black"></td>
			</tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7">&nbsp;</td>
			</tr>
			</table>
			<?php
			kb_footer();
		}//end article preview
		elseif($type=="art"){
			//subject
			?>
			<style>
				.header{margin-left: 10px; font-family: Verdana; font-size: 15px; color: white; font-weight: bold}
				.header2{margin-left: 10px; font-family: Verdana; font-size: 13px; color: #003399; font-weight: bold}
			</style>
			<table align="center" cellspacing="0" cellpadding="2" border="0">
			<tr>
			    <td colspan="2" class="" bgcolor="#204D79">
					<div class="header"><?php print $title; ?></div>
					<div class="header">Knowledge Base</div>
			     </td>
			</tr>
			<tr>
			    <td><div class="header2">Preview</div></td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
				<?php
				//article display
				$sql="SELECT * FROM $kbart_table WHERE id='$id';";
				$result=mysql_query($sql);
				$stuff=mysql_fetch_array($result);
				?>
			    <td colspan="2">This page shows you what the <b><?php print $stuff['title']; ?></b> article will look like to your users in the Knowledge Base.</td>
			</tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7">Article Preview</td>
			</tr>
			<tr><td colspan="2" bgcolor="black"></td></tr>
			<tr>
 			<td bgcolor="white">
			<table width="100%" cellpadding="3">
			<tr>
			  <td valign="top" width="1%"><img src="pixel.gif" width="1" height="8"></td>
			  <td width="99%" valign="top">
				<div class="header2"><a href="<?php print $PHP_SELF; ?>?act=preview&type=cat&id=<?php print $c; ?>"><?php print $c; ?></a>&nbsp;&raquo;&nbsp;<?php print $stuff['title']; ?></div>
				<div class=""><?php print $stuff['author']; ?></div>
				<div class="">&nbsp;</div>
				<div class="">
					<hr width="80%" size="1" color="Black">
					<br><?php print $stuff['content']; ?><br>
					<hr width="80%" size="1" color="Black"><br>
				</div>
				</td>
			</tr>
			</table>
			<!--END KB CONTAINER-->
			<tr>
				<td colspan="2" bgcolor="black"></td>
			</tr>
			<tr>
			    <td colspan="2" bgcolor="#b7b7b7">&nbsp;</td>
			</tr>
			</table>
			<?php
			kb_footer();
		}//end category preview
		else{
			print "Invalid Type";
		}
	}//END PREVIEW
	//^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	else{
		print "Invalid act";
	}
}//end $act check
//---------------------------------------------------------------------
//SHOW INDIVIDUAL TICKET INFO
if(isset($ticket)){
	//show the ticket specified
	//------------
	$indiv=0;
	//set up arrays
	$date=array();
	$time=array();
	$admin=array();
	$child=array();
	$question=array();
	//array part counter
	$row_at=0;
	$on=0;
	
	$query="SELECT NUMBER,ACCOUNT,SUBJECT,DATE,TIME,STATUS,NAME,EMAIL,URGENCY,ADMIN,CHILD,QUESTION FROM $ticket_table WHERE NUMBER=$ticket ORDER BY DATE,TIME";
	$result=mysql_query($query,$link);
	$rows=mysql_num_rows($result);
	
while($on<$rows){
$indiv=0;
	$a_row=mysql_fetch_row($result);
	foreach($a_row as $field){
		if($indiv==0){
			$num=$field;
		}
		elseif($indiv==1){
			$acct=$field;
		}
		elseif($indiv==2){
			$subj=$field;
		}
		elseif($indiv==3){
			$date[$row_at]=$field;
		}
		elseif($indiv==4){
			$time[$row_at]=$field;
		}
		elseif($indiv==5){
			$status=$field;
		}
		elseif($indiv==6){
			$name=$field;
		}
		elseif($indiv==7){
			$email=$field;
		}
		elseif($indiv==8){
			$urgency=$field;
		}
		elseif($indiv==9){
			$admin[$row_at]=$field;
		}
		elseif($indiv==10){
			$child[$row_at]=$field;
		}
		elseif($indiv==11){
			$question[$row_at]=$field;
			
			$row_at++;
		}
		$indiv++;
//end foreach
}
$on++;
//end while
}

$at=0;
while($at<$rows){
$times=$at;

	//output header first time only
	if(($at==0)&&($child[$times]==0)){
?>
<!--<TR CLASS="back">
<TD COLSPAN="1" ALIGN="CENTER">--><Div align="center"><BR>
<TABLE BORDER="1" BORDERCOLOR="#000000" CELLPADDING="0" CELLSPACING="0">
<TR><TD>
<TABLE CELLPADDING="3" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="headrow" COLSPAN="3"><B>Trouble Ticket #<?php echo $num; ?> Information</B></TD>
</TR>
</TABLE>
<TABLE BORDER="0" WIDTH="250"> 
<TR><TD WIDTH="50%"><B>Name:</B></TD><TD WIDTH="50%"><?php echo $name; ?></TD></TR>
<TR><TD WIDTH="50%"><B>Account:</B></TD><TD WIDTH="50%"><?php echo $acct; ?></TD></TR>
<TR><TD WIDTH="50%"><B>Status:</B></TD><TD WIDTH="50%"><?php echo $status; ?></TD></TR>
<TR><TD WIDTH="50%"><B>Urgency:</B></TD><TD WIDTH="50%"><?php echo $urgency; ?></TD></TR>
<TR><TD WIDTH="50%"><B>Subject:</B></TD><TD WIDTH="50%"><?php echo $subj; ?></TD></TR>
<TR><TD WIDTH="50%"><B>Email:</B></TD><TD WIDTH="50%"><?php echo $email; ?></TD></TR>
<?php $ae_mail=$email; ?>
</TABLE>
</TD></TR></TABLE>
<BR>
<TABLE BORDER="1" BORDERCOLOR="#000000" CELLPADDING="0" CELLSPACING="0">
<TR><TD>
<TABLE CELLPADDING="3" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="headrow" COLSPAN="3"><B>Dialog</B>
</TD>
</TR>
</TABLE>
<TABLE BORDER="0" WIDTH="551" bgcolor="#E3E3E3"> 
<!--Question-->
<tr>
    <td bgcolor="#E3E3E3"><b>Question:</b></td>
</tr>
<tr>
    <td bgcolor="white"><br><?php echo $question[$times]; ?><br><br></td>
</tr>
<tr>
    <td bgcolor="#E3E3E3"><B>Date: </B><?php echo $date[$times]; ?><br>
		<B>Time: </B><?php echo $time[$times]; ?>
	</td>
</tr>
<!--End Question-->
	<?php
	}//end false child
	else{
?>
<tr><td bgcolor="black"></td></tr>
<!--Response <?php echo $times; ?>-->
<tr>
    <td bgcolor="#E3E3E3"><b>Response <?php echo $times; ?>:</b></td>
</tr>
<tr>
    <td bgcolor="white"><br><?php echo $question[$times]; ?><br><br></td>
</tr>
<tr>
    <td bgcolor="#E3E3E3"><b>Answered by:</b> <?php echo $admin[$times]; ?><br>
	<B>Date:</B> <?php echo $date[$times]; ?><br>
		<B>Time:</B> <?php echo $time[$times]; ?>
	</td>
</tr>
<!--End Response <?php echo $times; ?>-->
<?php
	//end true child
	}
$at++;
//end while loop
}
?>
</TABLE>
</TD>
</TR>
</TABLE>
<BR>
<FORM NAME="Ticket System" ACTION="<?php echo $PHP_SELF; ?>" METHOD="post">
<TABLE BORDER="1" BORDERCOLOR="#000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE CELLPADDING="3" CELLSPACING="0" WIDTH="100%">
<TR><TD CLASS="headrow" COLSPAN="3"><B>Respond:</B></TD></TR>
</TABLE>
<TABLE BORDER="0" WIDTH="508"> 
<TR><TD WIDTH="100%">
<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0">
<TR><TD><TEXTAREA name="response" rows=12 cols=50 class="prefinput"></TEXTAREA></TD>
<TD VALIGN="top" ALIGN="middle">
<INPUT TYPE="hidden" NAME="num" VALUE="<?php echo $num; ?>">
<INPUT TYPE="submit" Name="action" Value="Respond" class="but">
<br><br><b>Status:</b><br>
<select name="status" size="1" class="prefinput">
<option value="Closed" Selected>Closed</Option>
<option value="Awaiting Response">Awaiting Response</Option>
</select>
<input type="hidden" name="ae_mail" value="<?php print $ae_mail; ?>">
<br><b>Admin:</b><br>
<input size="15" maxlength="40" type="text" name="admin" value="<?php echo $username; ?>">
<br><br><b>Include Signature?</b>&nbsp;<input type="checkbox" checked name="signature" value="yes" class="prefinput">
</TD></TR></TABLE></TD></TR></TABLE></TD></TR>
</TABLE><BR><!--</TD></TR></TABLE></TD></TR></TABLE>-->
</FORM><BR>
<b>Administration Options</b>
<P>Delete Ticket?&nbsp;&nbsp;
<Form action="<?php print $PHP_SELF; ?>?action=modify" method="post">
<select name="confirm" size="1" class="prefinput">
<option value="No" Selected>No</Option>
<option value="Yes">Yes</Option>
</select>
<input type="hidden" name="urlback" value="<?php print $PHP_SELF; ?>?<?php print $QUERY_STRING; ?>">
<input type="hidden" name="number" value="<?php print $num; ?>">
<input type="hidden" name="subject" value="<?php print $subj; ?>">
<INPUT TYPE="submit" Name="saction" Value="Delete" class="but">
</FORM>
</P></DIV>
<?php
//end ticket part
}
//---------------------------------------------------------------------
//close database
mysql_close($link);
//---------------------------------------------------------------------
?>
</TBODY></TABLE>
</TD></TR></TABLE>
<br><br>
</TD></TR></TABLE>
</TD></TR></TABLE>
<br>
<?php
if(((!isset($action))||($action==""))&&(!isset($ticket))&&(!isset($act))){
//only display on main page
?>
<table align="center" cellspacing="0" cellpadding="2" border="1" bordercolor="Black">
<tr>
    <td bgcolor="#eaeaea"><?php
		$fd=fopen("http://scripts.sheddtech.com/vtrack/index.php?product=1&version=$ssm_sys_version",'r');
		$contents=fread($fd,10000000);
		fclose($fd);
		print $contents;
	?>
</td></tr></table>
<br><br>
<?php
}
?>
<div align="center">
<FONT size=2><STRONG><i>powered by</i></STRONG></FONT><br>
<FONT size=2><a href="http://scripts.sheddtech.com">Support Services Manager</a><br>
&copy; 2001-2002 STI, All Rights Reserved
</FONT>
</div>
<?php
//---------------------------------------------------------------------
?>
</td>
</tr>
</table>
</BODY>
</HTML>
<?php
//---------------------------------------------------------------------
//END logged in check
}
//---------------------------------------------------------------------
//not logged in
else{
	header("index.php");
//END require log-in
}
//---------------------------------------------------------------------
//FUNCTIONS
//---------------------------------------------------------------------
/*
The screen series of functions are part of the Subject tool.  They create
the various screens which are displayed when the Subject tool is used.
*/
function screen_main($link,$subj_table){
	print '<Div align="center"><b>Present Subjects</b></Div><br><table width="250" align="center" cellspacing="4" cellpadding="4" border="1">';
		$query="SELECT NUMBER,VALUE FROM $subj_table ORDER BY NUMBER ASC;";
		if($result=mysql_query($query,$link)){
			while($row=mysql_fetch_array($result)){
				print "<tr><td>$row[NUMBER]</td>";
				print "<td>$row[VALUE]</td></tr>";
			}
		}
	print '</table>';
}
function screen_new($PHP_SELF){
//global $u,$p;
	print 'Enter the subject that you would like to add below:<br>';
	print "<form action=\"$PHP_SELF\" method=\"post\">";
	print '<input type="text" name="subj">&nbsp;&nbsp;';
	print "<input type=\"hidden\" name=\"action\" value=\"subject\">";
	print '<input type="submit" name="saction" value="Add" class="but"></form>';
}
function screen_edit($link,$subj_table,$PHP_SELF){
//global $u,$p;
$spiel="Editing a subject changes it in all associated Knowledge Base articles in addition to the subject itself.  Deleteing a subject does not remove associated articles - it only removes the subject itself.  Deleteing a subject will prevent the associated articles from being displayed, however they remain in the database.";
	print '<Div align="center"><b>Edit Subjects</b></Div><br>'.$spiel.'<br><br><table width="250" align="center" cellspacing="4" cellpadding="4" border="1">';
		$query="SELECT NUMBER,VALUE FROM $subj_table ORDER BY NUMBER ASC;";
		if($result=mysql_query($query,$link)){
			while($row=mysql_fetch_array($result)){
				print "<tr><td><!--<a href=\"$PHP_SELF?saction=edit&num=$row[NUMBER]\">-->$row[NUMBER]<!--</a>--></td>";
				print "<td><Form action=\"$PHP_SELF?action=subject&saction=Edit\"method =\"post\" name=\"num\">";
				print "<input name=\"val\" type=\"text\" value=\"$row[VALUE]\"><input type=\"hidden\" name=\"preval\" value=\"$row[VALUE]\"><input type=\"hidden\" name=\"num\" value=\"$row[NUMBER]\"></td>";
				print "<input type=\"hidden\" name=\"action\" value=\"subject\">";
				print "<td><a href=\"$PHP_SELF?action=subject&saction=Delete&num=$row[NUMBER]\">Delete</a></td>";
				print '<td><input type="submit" name="saction" value="Edit" class="but"></form></tr>';
			}
		}
	print '</table>';
}
/*
This function produces the Admin Area's KB footer.
*/
function kb_footer(){
	global $PHP_SELF;
	?>
	<table align="center" cellspacing="0" cellpadding="2" border="0">
	<tr>
	   <!-- <td>&nbsp;<a href="<?php print $PHP_SELF; ?>">Administration Home</a>&nbsp;</td>
		<td><b>|</b></td>-->
	    <td>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=kb">KB Home</a>&nbsp;</td>
		<td><b>|</b></td>
	    <td>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=kb&load=new">New Article</a>&nbsp;</td>
		<td><b>|</b></td>
	    <td>&nbsp;<a href="<?php print $PHP_SELF; ?>?action=kb&load=manage">Manage Existing Articles</a>&nbsp;</td>
	</tr>
	</table>
	<?php
}//end function
?>
