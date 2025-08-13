<?php 
/*
***************************************************************************
Parameters :

$Id
$action
***************************************************************************
*/
?>

<?php
$Date = date('Y-m-d');
$iHtml_w="534";
$iHtml_h="200";
$body_extra = "onLoad=\"Init()\"";
?>

<?php require("inc.layout.php"); ?>

<?php include("inc.header.php"); ?>

<?php echo $headersrc; ?>

<?php require("inc.auth.php"); ?>
<?php 
if ($userlev == "guest") {
	echo "<font color=red><b>You are not allowed to view this page</b></font>";
	die;
}
?>

<?php
//define button text, iframe src and headline text in case action is edit
if ($action =="edit" && in_array("$userlev", $admins) ){
	$src = "src=get.php?dbfield=Content&dbtable=News&Id=$Id";
	$button_text = "Edit html news entry $Id";
}else{
	$button_text = "Post your html news entry";
}
//only save headline as hidden form if not empty
if ($Headline){
echo "<input type=hidden name=Headline value=\"$Headline\">";
}

//do work in case the submit button was pressed
if ($Submit){
$Content = $newsPost;
if ($Headline && $Content && $Poster && $Date){
//if action is create go into create mode
	if ($action =="create" ){
        $Content = nl2br($Content);
        $query = "INSERT INTO News (Id, Poster, Headline, Content, Date) VALUES ('','$Poster','$Headline','$Content','$Date')";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	if ($result){ print "<b>News entry sucesfully posted</b><br>"; }else{ print "<font color=red>An error has occured</font>"; }
	}
//if actionn is edit go into edit mode
       	if ($action =="edit" && in_array("$userlev", $admins) ) {
	$Content = nl2br($Content);
        $query = "UPDATE News SET Poster='$Poster', Headline='$Headline', Content='$Content', Date='$Date' WHERE Id = '$Id' ";
	$result = mysql_query($query) or die ("died while inserting to table<br>Debug info: $query");
	if ($result){ print "<b>News entry $Id sucesfully edited</b><br>"; }else{ print "<font color=red>An error has occured</font>"; }
        }
}
}
?>

<?php include("inc.mail.php"); ?>

<script language="JavaScript" src="editor.js"></script>

<form onSubmit="return SendForm()" name="htmlform" action="news.editor.php" method="post"> 

<table class=td border="0"><tr><td>
<?php
$dbfield = "Headline";
$dbtable = "News";
?>
<input size=86% class=input type=text maxlength=50 name=Headline value="<?php if($action =="edit"){require("get.php");}else{echo $Headline;} ?>"><br>

<table id="tblCtrls" width="534" height="30px" border="0" cellspacing="0" cellpadding="0" ><tr><td class="td">
	<img alt="Bold" class="button" src="images/bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBold()" hspace="2">
	<img alt="Italic" class="button" src="images/italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doItalic()" hspace="1">
	<img alt="Underline" class="button" src="images/underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doUnderline()" hspace="2">
	<img alt="Left" class="button" src="images/left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLeft()" hspace="1">
	<img alt="Center" class="button" src="images/center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCenter()" hspace="2">
	<img alt="Right" class="button" src="images/right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRight()" hspace="1">
	<img alt="LI" class="button" src="images/ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOrdList()" hspace="2">
	<img alt="UL" class="button" src="images/bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBulList()" hspace="1">
	<img alt="COLOR" class="button" src="images/forecol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol()" hspace="2">
	<img alt="BG" class="button" src="images/bgcol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol()" hspace="1">
	<img alt="LINK" class="button" src="images/link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLink()" hspace="2">
	<img alt="HR" class="button" src="images/rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRule()" hspace="1">
	<img alt="Save" class="button" src="images/save.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doSave()" hspace="2">			
	<img alt="Open" class="button" src="images/open.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOpen()" hspace="1">
	<img alt="Print" class="button" src="images/print.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doPrint()" hspace="2">			
</td></tr></table>

<?php
echo "<iframe id=iHtml width=$iHtml_w height=$iHtml_h $src></iframe>";
?>

<table width="534" height="30px" border="0" cellspacing="0" cellpadding="0" ><tr><td class="td">
<center>

<select name="selFont" onChange="doFont(this.options[this.selectedIndex].value)">
<option value="">--Font Family--</option>
<option value="Arial">Arial</option>
<option value="Courier">Courier</option>
<option value="Sans Serif">Sans Serif</option>
<option value="Tahoma">Tahoma</option>
<option value="Verdana">Verdana</option>
<option value="Wingdings">Wingdings</option>
</select>

<select name="selSize" onChange="doSize(this.options[this.selectedIndex].value)">
<option value="">--Font Size--</option>
<option value="1">Very small</option>
<option value="2">Small</option>
<option value="3">Medium</option>
<option value="4">Large</option>
<option value="5">Larger</option>
<option value="6">Largest</option>
</select>

<select name="sel" onChange="doHead(this.options[this.selectedIndex].value)">
<option value="">--Heading--</option>
<option value="Heading 1">Heading 1</option>
<option value="Heading 2">Heading 2</option>
<option value="Heading 3">Heading 3</option>
<option value="Heading 4">Heading 4</option>
<option value="Heading 5">Heading 5</option>
<option value="Heading 6">Heading 6</option>
</select>
<br>

<input type=text size=6 maxlength=0 value="Poster">
<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Accounts";
$incfield = "Username";
$incshow = "Username";
$incvar = "Poster";
?>
<?php require("inc.select.php"); ?>

<input type="checkbox" name="send_mail" value="yes"> E-Mail 

<?php
$reqadm = "1";
$incsel = "rows";
$inctable = "Accounts";
$incfield = "Username";
$incshow = "Username";
$incvar = "Recipient";
?>
<?php require("inc.select.php"); ?>
<input type=text size=7 maxlength=0 value="Recipient">

</center>
</td>
</tr></table>
   
<center>
<input type="hidden" name="newsPost" value="">
<input type="hidden" name="Id" value="<?php echo $Id; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input class="submit" type="submit" name="Submit" value="<?php echo $button_text; ?>">
</center>

</td></tr></table>

</form>

<?php echo $footersrc; ?>

<?php include("inc.footer.php"); ?>
