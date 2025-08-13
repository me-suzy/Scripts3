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
$query="SELECT custom,usergroup FROM $user_table WHERE username = '$username'";
$result=mysql_query($query,$link);
$a_row=mysql_fetch_array($result);
$account_title=$username;
$usergroup=$a_row['usergroup'];
global $account_title;
?>
<HTML>
<HEAD>
<TITLE><?php echo $page_title; ?> :: <?php echo $username; ?></TITLE>
<link rel="STYLESHEET" type="text/css" href="style.css">
<script language="JavaScript" src="main.js"></script>
<meta http-equiv='PRAGMA' content='NO-CACHE'>
</HEAD>
<BODY BGCOLOR="<?php echo $background; ?>" LEFTMARGIN="8" TOPMARGIN="8" MARGINHEIGHT="8" MARGINWIDTH="8">
<TABLE BGCOLOR="#000000" BORDER="0" CELLPADDING="1" CELLSPACING="0" WIDTH="100%">
<TBODY>
<TR>
<TD>
<TABLE BORDER="0" CELLPADDING="4" CELLSPACING="1" WIDTH="100%">
<TBODY>
<TR><TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1"><?php echo $page_title; ?></FONT></TD></TR>
<TR><TD CLASS="headrow" COLSPAN="1" nowrap><B>Account:</B> <?php echo $username; if($usergroup=="3"){ print "&nbsp;[administrator]";}?>
<?php
	$query="SELECT * FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0;";
	$result=mysql_query($query,$link);
	$total=mysql_numrows($result);
	$query="SELECT * FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0 AND STATUS='Open';";
	$result=mysql_query($query,$link);
	$open=mysql_numrows($result);
	$query="SELECT * FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0 AND STATUS='Closed';";
	$result=mysql_query($query,$link);
	$closed=mysql_numrows($result);
?><img src="pixel.gif" width="220" height="1" alt="" border="0">
REQUESTS&nbsp;[Open<b>:</b>&nbsp;<?php print $open; ?>
&nbsp;<b>|</b>&nbsp;Closed<b>:</b>&nbsp;<?php print $closed; ?>
&nbsp;<b>|</b>&nbsp;Total<b>:</b>&nbsp;<?php print $total; ?>]
</TD></TR>
<?php
include("header.php");
//---------------------------------------------------------------------
	//NO ACTION
if(((!isset($action))||($action==""))&&(!isset($ticket))){
	//show recent tickets
	
	//ORDER BY DATE-take 10 most recent
	$query="SELECT NUMBER,SUBJECT,DATE,TIME,URGENCY,STATUS FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0 ORDER BY NUMBER DESC,DATE DESC,TIME DESC;";
	$result=mysql_query($query,$link);
	$rows=mysql_numrows($result);
?>
<TR CLASS="back">
<TD>
<TABLE WIDTH="100%" BORDER="1" BORDERCOLOR="000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE BORDERCOLOR="000000" BORDER="0" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1">&nbsp;Recent Tickets: <?php echo $rows; ?></FONT></TD>
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
$total=$rows+1;
$at=0;
if($rows>0){
	//set stop point
	if($rows<10){
		$until=$rows-1;
	}
	else{
		$until=10;
	}
//while($at<=$total){
while($at<=$until){
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
    print 'No Recent Tickets For Your Account';
    print '</TD></TR>';
}
?>
<!--END TICKETS-->
</TBODY></TABLE>
</TD></TR></TABLE>
<br>
<table align="center" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td><?php new_button(); ?></td>
    <td><?php open_button(); ?></td>
    <td><?php closed_button(); ?></td>
</tr>
</table>
<br>
</TD></TR></TABLE>
</TD></TR></TABLE>	

<?php
//END RECENT TICKETS PART
	}
//---------------------------------------------------------------------
	elseif($action=="Open"){
		//display open ticket list
		?>
<TR CLASS="back">
<TD COLSPAN="1" ALIGN="CENTER"><BR>
<TABLE WIDTH="100%" BORDER="1" BORDERCOLOR="000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE BORDER="0" WIDTH="100%" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<?php
//get tickets from db
//$rows is the total
	$query="SELECT NUMBER,SUBJECT,DATE,TIME,STATUS FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0 AND STATUS='Open' ORDER BY NUMBER;";
	$result=mysql_query($query,$link);
	$rows=mysql_numrows($result);
?>
<TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1">&nbsp;Open Tickets: <?php echo $rows; ?></FONT></TD>
</TR>
</TABLE>
<TABLE WIDTH="100%" BORDER="0" CELLPADDING="3" CELLSPACING="2" ALIGN="center">
<TBODY>
<!--HEADERS-->
<TR>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Close?</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Ticket #</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Subject</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Date</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Time</TD>
	<TD ALIGN="CENTER" CLASS="headrow">
	<B>Status</TD>
</TR>
<!--END HEADERS-->
<FORM method="post" name="closerequest" onsubmit="return doVerify(this);" action="<?php echo $PHP_SELF; ?>">
<!--Tickets-->
<?php
//list tickets
if($rows>0){
$at=0;
while($at<$rows){
	$indiv=1;
	$a_row=mysql_fetch_row($result);
	foreach($a_row as $field){
		if($indiv==1){
			print"<TR>\n";
			print "<TD ALIGN='CENTER' CLASS='indiv'>";
			print "<input type='checkbox' name='toc[$field]' value='$field'></td>";
			print "\n<TD ALIGN='CENTER' CLASS='indiv'><a href=\"$PHP_SELF?ticket=";
			//link
			print $field;
			print "\">$field</a></TD>";
			$idnum=$field;
		}
		elseif($indiv==2){
			print "\n<TD ALIGN='CENTER' CLASS='indiv'>";
			//subject
        	print $field;
			print '</TD>';
		}
		elseif($indiv==3){
			print "\n<TD ALIGN='CENTER' CLASS='indiv'>";
			//date
       		print $field;
			print '</TD>';
		}
		elseif($indiv==4){
			print "\n<TD ALIGN='CENTER' CLASS='indiv'>";
			//time
        	print $field;
			print '</TD>';
		}
		elseif($indiv==5){
			print '<TD ALIGN="CENTER" CLASS="indiv">';
			//status
        	print $field;
			print "</TD>\n</TR>";
		}
		$indiv++;
	}
	$at++;
	?>
<TR><TD COLSPAN="6" ALIGN="CENTER" bgcolor="#bcbcbc"></TD></TR>
	<?php
//end while
}
?>
<script language="JavaScript" type="text/javascript">
<!--Hide Script from Old Browsers
//Check to make sure that the user really wants to close request
function doVerify(closerequest){
	var txt="Are you sure that you want to close this request?\nClosing this request will prevent administrators from reading it!\nClick OK to Close or CANCEL to keep the request open."
	if(!confirm(txt)){
		alert("Request was left open!");
		return false;
	}
	else{
		return true;
	}
}
//End Hide-->
</script>
<TR>
<TD COLSPAN="1" ALIGN="CENTER" CLASS="indiv">
<input type="hidden" name="action" value="CloseRequestB">
<input type="hidden" name="rnum" value="CloseTix">
<INPUT TYPE=SUBMIT name="verify1" VALUE="Close" class="but" title="Close Selected Tickets">
</TD>
<TD COLSPAN="5" ALIGN="CENTER" CLASS="indiv">&nbsp;</TD>
</TR>
<?php
//end if
}
else{
    print '<TR><TD COLSPAN="6" ALIGN="CENTER" CLASS="indiv">';
    print 'No Open Tickets For Your Account';
    print '</TD></TR>';
}
?></FORM>
<!--END TICKETS-->
</TBODY></TABLE>
</TD></TR></TABLE>
<br>
<table align="center" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td><?php recent_button(); ?></td>
    <td><?php closed_button(); ?></td>
    <td><?php new_button(); ?></td>
</tr>
</table>
<br>
</TD>
</TR>
</TBODY>
</TABLE>
</TD>
</TR>
</TBODY>
</TABLE>
</BODY>
</HTML>		
<?php
//END OPEN TICKETS PART
	}
//---------------------------------------------------------------------
elseif($action=="Closed"){
		//show list of closed tickets
		?>
<TR CLASS="back">
<TD COLSPAN="1" ALIGN="CENTER"><BR>
<TABLE BORDER="1" WIDTH="100%" BORDERCOLOR="000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE BORDER="0" WIDTH="100%" CELLPADDING="0" CELLSPACING="0" WIDTH="100%">
<TR>
<?php
//get tickets from db
//$rows is the total
	$query="SELECT NUMBER,SUBJECT,DATE,TIME,STATUS FROM $ticket_table WHERE ACCOUNT='$account_title' AND CHILD=0 AND STATUS='Closed' ORDER BY NUMBER;";
	$result=mysql_query($query,$link);
	$rows=mysql_numrows($result);
?>
<TD CLASS="toprow" COLSPAN="1"><FONT SIZE="1">&nbsp;Closed Tickets: <?php echo $rows; ?></FONT></TD>
</TR>
</TABLE>
<TABLE BORDER="0" WIDTH="100%" CELLPADDING="3" CELLSPACING="2" ALIGN="center">
<TBODY>
<!--HEADERS-->
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
	<B>Status</TD>
</TR>
<!--END HEADERS-->
<!--Tickets-->
<?php
//list tickets
if($rows>0){
$at=0;
while($at<$rows){
	$indiv=1;
	$a_row=mysql_fetch_row($result);
	foreach($a_row as $field){
		if($indiv==1){
			print"<TR>\n";
			print "<TD ALIGN=\"CENTER\" CLASS=\"indiv\"><a href=\"$PHP_SELF?ticket=";
			//link
			print $field;
			print "\">$field</a>";
			print '</TD>';
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
			//status
        	print $field;
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
    print '<TR><TD COLSPAN="5" ALIGN="CENTER" CLASS="indiv">';
    print 'No Closed Tickets For Your Account';
    print '</TD></TR>';
}
?>
<!--END TICKETS-->
</TBODY></TABLE>
</TD></TR></TABLE>
<br>
<table align="center" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td><?php recent_button(); ?></td>
    <td><?php open_button(); ?></td>
    <td><?php new_button(); ?></td>
</tr>
</table>
<br>
</TD>
</TR>
</TBODY>
</TABLE>
</TD>
</TR>
</TBODY>
</TABLE>
</BODY>
</HTML>	
<?php
	}
//---------------------------------------------------------------------
elseif($action=="New"){
		//display new ticket form
		$query="SELECT email FROM $user_table WHERE username = '$username' AND password = '$password';";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_array($result);
		$addr=$a_row[email];
		?>
<FORM NAME="Ticket System" ACTION="<?php echo $PHP_SELF; ?>" METHOD=post>
<TR CLASS="back">
<TD COLSPAN="1" ALIGN="CENTER"><BR>
<TABLE BORDER="1" BORDERCOLOR="000000" CELLPADDING="0" CELLSPACING="0"><TR><TD>
<TABLE CELLPADDING="3" CELLSPACING="0" WIDTH="100%">
<TR>
<TD CLASS="headrow" COLSPAN="3"><B>New Trouble Ticket</TD>
</TR>
</TABLE>
<TABLE BORDER="0" WIDTH="508"> 
<TR>
<TD WIDTH="100%">
<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0"><TR><TD>
Account: <?php echo $username; ?><BR><BR>
Name: <INPUT TYPE=Text NAME="name"><BR><BR>
Email Address: <INPUT TYPE=TEXT NAME="email" VALUE="<?php echo $addr; ?>"><BR><BR>
Subject: <?php dropdown($link); ?><BR><BR>
Urgency: 
<SELECT NAME="urgency" class="prefinput">
<OPTION VALUE="High">High</OPTION>
<OPTION VALUE="Medium">Medium</OPTION>
<OPTION VALUE="Low" SELECTED>Low</OPTION>
</SELECT><br><br>
Question:
<TEXTAREA class="prefinput" NAME="question" COLS="50" ROWS="12"></TEXTAREA>
</TD>
<TD VALIGN="TOP" ALIGN="CENTER">
<INPUT TYPE="SUBMIT" Value="Submit" class="but">&nbsp;<input type="Reset" class="but">
<br><br><b>Include Signature?</b>&nbsp;<input type="checkbox" name="signature" value="true">
</TD></TR></TABLE></TD></TR>
</TABLE></TD></TR></TABLE>
<BR></TD></TR></TABLE></TD></TR>
</TABLE>
<input type="hidden" name="action" value="Add">
</FORM>
</BODY>
</HTML>
<?php
	}
//---------------------------------------------------------------------
elseif($action=="Add"){
		//add ticket
		
		//get next number
		$query="SELECT NUMBER FROM $ticket_table ORDER BY NUMBER DESC;";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_row($result);
		if(mysql_num_rows($result)>0){
			foreach($a_row as $field){
				while($field>$last_num){
					$last_num=$field;
				}//end while
			}//end foreach
		}//end if
		else{
			$last_num=0;
		}
		$last_num++;
		$num=$last_num;
		
		//$num=LAST NUMBER++
		//$account is account title
		$account=$username;
		//$name is name
		//$email is email
		//$subject is subject
		//$urgency is urgency
		//$question is question
		
		//set unneeded vars to blank
		$admin="None";
		$child="0";
		//generate date in format MONTH-DAY-YEAR
		$date=date(m).'-'.date(d).'-'.date(Y);
		//generate time in format HOUR(24)-MIN-SEC
		$time=date(H).':'.date(i).':'.date(s);
		//set status to open
		$status="Open";
		
		//get signature & append
		$query="SELECT sig FROM $user_table WHERE USERNAME='$username';";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_row($result);
		foreach($a_row as $field){
			$sig=$field;
		}
		if($signature==true){
			$question.="\n\n";
			//include the OpenBB Codeparsing Routine
			//include($openbb_path_libdir."codeparse.php");
			//$sig=codeparse($sig,1,0,$username);
			$question.=$sig;
		}
		
		$question=htmlentities($question);
		$question=nl2br($question);
		$question=stripslashes($question);
		
		//formulate query to insert ticket
		$query="INSERT INTO $ticket_table (NUMBER, ACCOUNT, SUBJECT, DATE, TIME, STATUS, NAME, EMAIL, URGENCY, ADMIN, CHILD, QUESTION) VALUES ('$num', '$account', '$subject', '$date', '$time', '$status', '$name', '$email', '$urgency', '$admin', '$child', '$question');";
		$result=mysql_query($query,$link);
		if(!$result){
			//Error!
			print '<tr><td colspan=\"5\" bgcolor="white"><b>Error!</b>';
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
			//show new button
			new_button();
			print "<br>";
			//show recent button
			recent_button();
			echo "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
			
			//send alert email to admin
			//alert_mail($admin_email,$ae_subj,$ae_mssg);
		}
		else{
			//send alert email to admin
			alert_mail($admin_email,$ae_subj,$ae_mssg);
			
			//Success!
			print '<tr><td colspan=\"5\" bgcolor="white"><b>Success!</b>';
			echo "<br>";
			print "Ticket # $num was successfully added to the queue.";
			echo "<br>";
			//show recent button
			recent_button();
			print "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
			//update user ticket number
			$query="SELECT TICKETS FROM $user_table WHERE username = '$username' AND password = '$password';";
			$result=mysql_query($query,$link);
			$a_row=mysql_fetch_row($result);
			foreach($a_row as $field){
				while($field>$last_num){
					$user_num=$field;
				}
			}
			$user_num++;
			$query="UPDATE $user_table SET TICKETS = '$user_num' WHERE username = '$username' AND password = '$password';";
			$result=mysql_query($query,$link);
			if(!$result){
				print "Error in updating user ticket count!";
			}
		}
//end add
}
//---------------------------------------------------------------------
elseif($action=="CloseRequest"){
	
	echo '<tr><td colspan=\"5\" bgcolor="white">';
	//close request
	if(isset($rnum)){
		$status="Closed";
		$query="UPDATE $ticket_table SET STATUS = '$status' WHERE NUMBER=$rnum AND ACCOUNT='$account_title';";
		$result=mysql_query($query,$link);
		if(!$result){
				print "Error in closing request!";
				print mysql_error();
		}
		else{
			print "<br><br>Your Request was Closed!<br><br>";
		}
	}
	else{
		print "<br><br>You must specify a request to close!<br><br>";
	}
	recent_button();
	echo "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
}
//---------------------------------------------------------------------
elseif($action=="CloseRequestB"){

	echo '<tr><td colspan=\"5\" bgcolor="white">';
	
	//close requested tickets
	if(isset($rnum)){
		foreach($toc as $num){
		$status="Closed";
		$query="UPDATE $ticket_table SET STATUS = '$status' WHERE NUMBER=$num AND ACCOUNT='$account_title';";
		$result=mysql_query($query,$link);
		if(!$result){
				print "Error in closing request!";
				print mysql_error();
		}
		else{
			print "<br>Your Request (Ticket $num) was Closed!<br>";
		}
		
		}//end foreach
	}
	else{
		print "<br><br>You must specify a request to close!<br><br>";
	}
	recent_button();
	echo "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
}
//---------------------------------------------------------------------
elseif($action=="kb"){
	?>
<tr><td colspan="5" bgcolor="white">
<!--BEGIN KB CONTENT-->
<FONT FACE="Verdana">
<P ALIGN="CENTER"><b><font size="3"><?php print $title; ?> Knowledge Base</font></b></P>
<P>The Knowledge Base is a powerful resource that allows users to search 
the array of articles, Frequently Asked Questions (FAQs), and other materials 
that system administrators have made available for your use.  It is good 
practice for users to search the Knowledge Base <b>before</b> creating a Support 
Request.  Doing so will allow you to either solve your issue completely or gain a 
more complete understanding of your issue and what has been discussed in the past 
regarding it.</P>
</FONT>
<script language="JavaScript">
function launchKB(){
	window.open('kb_index.php', 'newwindow', config='height=500, width=700, toolbar=yes, menubar=yes, scrollbars=yes, resizable=yes, location=yes, directories=no, status=yes')
}//end function
</script>
<center>
<INPUT TYPE=Button onclick="launchKB();" VALUE="Launch Knowledge Base" class="but">
</center><br>
<!--END KB CONTENT-->
</td></tr></td></tr></TD></TR></TABLE></TD></TR></TABLE>
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
			//acoount
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
		//admin to none
		$admin="Client";
		//set child to true
		$child="1";
		//generate date in format MONTH-DAY-YEAR
		$date=date(m).'-'.date(d).'-'.date(Y);
		//generate time in format HOUR(24)-MIN-SEC
		$time=date(H).':'.date(i).':'.date(s);
		//set status to open
		$status="Open";
		
		//get signature & append
		$query="SELECT sig FROM $user_table WHERE USERNAME='$username';";
		$result=mysql_query($query,$link);
		$a_row=mysql_fetch_row($result);
		foreach($a_row as $field){
			$sig=$field;
		}
		if($signature==true){
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
			print '<tr><td colspan=\"5\"><b>Error!</b>';
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
			echo '<br><br>MySQL Error Code: ';
			print mysql_error();
			print '<br>';
			//show new button
			new_button();
			print "<br>";
			//show recent button
			recent_button();
			echo "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
		}
		else{
			//Success!
			print '<tr><td colspan=\"5\" bgcolor=white><b>Success!</b>';
			echo "<br>";
			print "Your response to Ticket # $num was successfully added.";
			echo "<br>";
			//show recent button
			recent_button();
			print "</td></tr></TD></TR></TABLE></TD></TR></TABLE>";
		}
//end add
}
//---------------------------------------------------------------------
elseif($action=="user"){
	//show/edit user preferences for the present user
	echo '<tr><td colspan=\"5\" bgcolor="white">';
	print '<FONT FACE="Verdana"><P ALIGN="CENTER"><b>';
	if(!isset($edit)){//check to see if edit var is set
	/*
	print out a form field for each below bit
	have the user submit the form with editted fields
	password blank for no change - click on link to change = popup
	for each field, check against DB values - if change, edit
	*/
	$result=mysql_query("SELECT * FROM $user_table WHERE username = '$username';");
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
	<font size="3">Profile for <u><?php print $username; ?></u></font></b></p>
	<form onSubmit="return submitIt(this)" action="<?php print $PHP_SELF; ?>" method="post" name="prefs">
	<input type="hidden" name="edit" value="true">
	<input type="hidden" name="action" value="user">
	<table width="70%" align="center" cellspacing="0" cellpadding="2" border="0">
	<tr>
	    <td colspan="2">Use the below form to edit your profile.</td>
	</tr>
	<tr>
	    <td colspan="2" bgcolor="#b7b7b7"><b>Your Profile</b></td>
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
	
	$sqlcode=array("UPDATE $user_table SET email='$email', homepage='$homepage', homepagedesc='$homepagedesc', icq='$icq', aim='$aim', yahoo='$yahoo', msn='$msn', note='$note', occupation='$occupation', location='$location', sig='$sig' WHERE username = '$username';");
	
	foreach($sqlcode as $sql){
		$result="";
		if(!$result=mysql_query($sql)){
			print "<p>Error in updating data!<br>";
			print mysql_error();
			print '<br><a href="';
			print $PHP_SELF;
			print '?action=user">Click Here to try again</a><br><br>';
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
		print '?action=user">Click Here to Continue</a></div><br><br>';
	}//end no error
	else{
		print "Error in updating information.  Please try again.";
	}
	
	}//end
	print "</font></td></tr></TD></TR></TABLE></TD></TR></TABLE>";
}//END USER
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
	
	$query="SELECT NUMBER,ACCOUNT,SUBJECT,DATE,TIME,STATUS,NAME,EMAIL,URGENCY,ADMIN,CHILD,QUESTION FROM $ticket_table WHERE NUMBER=$ticket AND ACCOUNT='$account_title' ORDER BY DATE,TIME";
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
<TR CLASS="back">
<TD COLSPAN="1" ALIGN="CENTER"><BR>
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
<TR><TD><TEXTAREA name="response" rows=12 cols=50></TEXTAREA></TD>
<TD VALIGN="top" ALIGN="middle">
<INPUT TYPE="hidden" NAME="num" VALUE="<?php echo $num; ?>">
<INPUT TYPE="submit" Name="action" Value="Respond" class="but">
<br><br><b>Include Signature?</b>&nbsp;<input type="checkbox" name="signature" value="true">
</FORM>
</TD></TR></TABLE></TD></TR></TABLE></TD></TR>
</TABLE>
<script language="JavaScript" type="text/javascript">
<!--Hide Script from Old Browsers
//Check to make sure that the user really wants to close request
function doVerify(closerequest){
	var txt="Are you sure that you want to close this request?\nClosing this request will prevent administrators from reading it!\nClick OK to Close or CANCEL to keep the request open."
	if(!confirm(txt)){
		alert("Request was left open!");
		return false;
	}
	else{
		return true;
	}
}
//End Hide-->
</script>
<FORM name="closerequest" onsubmit="return doVerify(this);" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="action" value="CloseRequest">
<input type="hidden" name="rnum" value="<?php echo $num; ?>">
<input type="Submit" class="but" value="Close Ticket" name="verify1">
</Form>
<BR></TD></TR></TABLE>
</TD></TR></TABLE>
</BODY>
</HTML>
<?php
//end ticket part
}
//---------------------------------------------------------------------
//END logged in check
}
//---------------------------------------------------------------------
//not logged in
else{
	//require log-in
	header("index.php?action=login");
//END require log-in
}
//---------------------------------------------------------------------
//close database
mysql_close($link);
//---------------------------------------------------------------------
include($end_file);
//---------------------------------------------------------------------
?>
