<?php echo"\n\n\n";?>
<!-- start of answerplus.php -->
<script language="Javascript">
<!--
/* 
The purpose of this code is to provide a method to enable enhanced web 
communications. Specifically, this code provides the startup functions 
for live keyboard chat and live phone call back on demand with Answerplus 
Communications. For details on how to setup this within phpYellow Pages 
consult the Developers Guide on the customization page. 
http://www.dreamriver.com/phpYellow/docs/DOCScustomize.html#answerplus
*/
  function OpCallBack() { // request an operator call back
    var Acct = OpCallBack.arguments[0];
    var CBStr = 'http://<?php echo CALLBACKWEBSTRING;?>/Infinity/ChatCall/CallBackLogin.asp?AcctID=' + Acct;
    var win = window.open(CBStr, "CallBackBox", "width=300, height=450,status=no, resizeable=no");
  }
  function ChatWithOp() { // chat with an operator
    var Acct = ChatWithOp.arguments[0];
    var ChatStr = 'http://<?php echo WEBCHATWEBSTRING;?>/Infinity/ChatCall/ChatLogin.asp?AcctID=' + Acct;
    var win = window.open(ChatStr, "ChatBox", "width=300, height=450,status=no, resizeable=no");
  }
//-->
</script>
<table><tr>
<?php if (USEANSWERPLUS == "yes" ):
	$callbackaccount = CALLBACKACCOUNT;
	if(!empty($callbackaccount)):?>
		<td width=200>
		<a href="http://www.<?php echo CALLBACKWEBSTRING;?>.com" onClick="OpCallBack('<?php echo CALLBACKACCOUNT;?>');">
		<img align="left" hspace=5 src="earthOp.gif" width="110" height="112" border=0 alt="Click here to have an operator return your call">
		Click here to have an operator return your call
		</a>
		</td>
	<?php endif;?>
	<?php 
	$webchataccount = WEBCHATACCOUNT;
	if(!empty($webchataccount)):?>
		<td width=200>
		<a href="http://www.<?php echo WEBCHATWEBSTRING;?>.com" onClick="ChatWithOp('<?php echo WEBCHATACCOUNT;?>');">
		<img align="left" hspace=5 src="answerplusGirl.jpg" width="110" height="112" border=0 alt="Click here to chat with an operator online">
		Click here to chat with an operator online
		</a>
		</td>
	<?php endif;?>
	<?php if((!(CALLBACKACCOUNT)) && (!(WEBCHATACCOUNT))):?>
		<td align="center">
		<a href="docs/DOCScustomize.html#answerplus" target="_blank">
		<img hspace=25 src="answerplusGirl.jpg" width="110" height="112" border=0 alt="Learn how AnswerPlus Communications can help complete this payment.">
		<div style="font-size: x-small;">Partner with AnswerPlus</div></a>
		</td>
	<?php endif;?>
<?php endif;?>
</tr></table>
<!-- end of answerplus.php -->
