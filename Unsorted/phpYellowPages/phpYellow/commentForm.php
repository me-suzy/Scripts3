<?php require("util.php");?>

<?php 
$subject = "Visitor comment from " . YOURPHPYELLOWNAME;
$submitButtonLabel = "";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<script language="Javascript" src="yellow.js"></script>
	<script language="Javascript">loadCSSFile();</script>
	<script language="Javascript">
<!--
// VALIDATE THE FORM
// Credit: This function by richardc@dreamriver.com
function stopJunk(form) {
	var badData = "false";
	var myForm = document.forms[0];
	for (var i=0; i < form.elements.length; i++) {
		if(form.elements[i].type == "text" && form.elements[i].value == "") { // do only if a text input type
			alert("You must fill out information for every box.");
			form.elements[i].focus();
			return false;
		}
	}
	// userEmail
	if ( myForm.userEmail.value < 6 ) {
		alert("You MUST enter your email address in order to receive a reply.");
		myForm.userEmail.focus();
		return false;	
	}
	// messageBody
	if ( myForm.messageBody.value < 5 ) {
		alert("You MUST enter a comment.");
		myForm.messageBody.focus();
		return false;	
	}
return true
}
//-->
</script>
	
<TITLE>Send a Comment - phpYellow Pages - another fine internet application from DreamRiver</TITLE>
<META NAME="Author" CONTENT="Richard Creech, Web: http://www.dreamriver.com Email: richardc@dreamriver.com">
<META NAME="GENERATOR" CONTENT="Blood, Sweat & Tears">
</HEAD>

<BODY onLoad="commentForm.userEmail.focus();">

<!-- CREDIT FOR this Email a Comment script goes to: Email: info@dreamriver.com    Web: http://www.dreamriver.com This message must be left in your code -->
<!-- START OF commentForm.php Main Content -->

<form name="commentForm" method="post" action="yellowgoal.php" onSubmit="return stopJunk(this)">
<input type="hidden" name="goal" value="<?php echo $goal;?>">
<input type="hidden" name="yps" value="<?php echo $yps;?>">


<table class="form" border=1><tr><td>
<table border=0>
<tr><th colspan=2><?php echo $goal;?></th></tr>

<tr>
	<td>Your Email *</td>
	<td><input class="input" type="text" name="userEmail" value="<?php echo $userEmail;?>" size=25></td>
</tr>
<tr bgcolor="silver">	
	<td>Your Name *</td>
	<td><input class="input" type="text" name="userFirstName" value="<?php echo $userFirstName;?>" size=25></td>
</tr>
<tr>
	<td>Subject *</td>
	<td><input class="input" type="text" name="subject" value="<?php echo $subject;?>" size=50></td>
</tr>
<tr bgcolor="silver">
	<td>Message *</td>
	<td>
		<textarea class="input" name="messageBody" rows=5 cols=50 wrap=soft><?php if($goal == "Send a Comment" ){$messageBody = "";}else{echo $messageBody;}?></textarea><br>
		* there MUST be information in each box.
		</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" class="input" name="submit" value="<?php if(empty($submitButtonLabel)){echo"Send Comment";}else{echo $submitButtonLabel;}?>"></td>
</tr></table>
</td></tr></table>
</form>
<!-- END OF commentForm.php -->

</div>


<!-- CREDIT FOR this script goes to: Email: richardc@dreamriver.com    Web: http://www.dreamriver.com This message must be left in your code -->




</td></tr></table>

<!-- END of main content -->
