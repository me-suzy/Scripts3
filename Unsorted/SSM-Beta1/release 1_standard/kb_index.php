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
/*
The Knowledge Base Interface Design is based on that of Stellar Docs.
Therefore, the SSM copyright does not apply to the Interface Design.
All Design & Interface code is original STI code, though.
All Backend Code for the Knowledge Base is original to STI's SSM and
	is released under the SSM copyright.
*/
//---------------------------------------------------------------------
require("global.php");
//---------------------------------------------------------------------
if(!isset($load)){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php print $title; ?> Knowledge Base :: <?php print $user_user; ?></title>
<link rel="stylesheet" href="kb_style.css" type="text/css">
<link rel="STYLESHEET" type="text/css" href="kbbars.css">
</head>
<!--FRAMES-->
<frameset frameborder="no" border="0" rows="60,*">
    <frame name="top" src="<?php print $PHP_SELF; ?>?load=top" marginwidth="0" marginheight="0" scrolling="no" frameborder="no">
    <frameset frameborder="no" border="0" cols="21%,*">
        <frame name="side" src="<?php print $PHP_SELF; ?>?load=side" marginwidth="0" marginheight="0" scrolling="yes" frameborder="no">
        <frameset frameborder="no" border="0" rows="*">
            <frame name="mainbody" src="<?php print $PHP_SELF; ?>?load=main" marginwidth="0" marginheight="0" scrolling="yes" frameborder="no">
        </frameset>
    </frameset>
</frameset>
<!--END FRAMES-->
<noframes>
<body bgcolor="#FFFFFF" text="#000000">
A frame capable browser is required to view the Knowledge Base.
</body>
</noframes>
</html>
<?php
}//end no load command
//---------------------------------------------------------------------
elseif($load=="top"){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" href="kb_style.css" type="text/css">
<link rel="STYLESHEET" type="text/css" href="kbbars.css">
</head>
<body bgcolor="#a6a6a6">
<center>
<table align="top" width="100%" height="100%" cellspacing="2" cellpadding="2" border="0">
<tr>
    <td valign="middle"><FONT 
color=#ffffff face =Verdana size=4 ><FONT 
      style="BACKGROUND-COLOR: #a6a6a6"><STRONG><?php print $title; ?></STRONG> <FONT 
      size=3>Knowledge Base</FONT> </FONT> 
</FONT></td>
    <td>&nbsp;</td>
    <td valign="middle"><form method="post" name="menu">
<?php
print '<SELECT NAME="url" class="prefinput" NAME="url" onChange="if (document.menu.url.options[document.menu.url.selectedIndex].value) { parent.mainbody.location=(document.menu.url.options[document.menu.url.selectedIndex].value) }">
<OPTION VALUE="notvalid">Select a Category&nbsp;:</OPTION>';
$query="SELECT VALUE FROM $subj_table ORDER BY NUMBER ASC;";
if($result=mysql_query($query,$link)){
	while($row=mysql_fetch_array($result)){
		print "<OPTION VALUE=\"$PHP_SELF?load=display&cat=$row[VALUE]\">&#149;&nbsp;$row[VALUE]</OPTION>";
	}
}
print '</SELECT>';
?>
</form></td>
</tr>
</table>
</center>
</body>
</html>
<?php
}//end top
//---------------------------------------------------------------------
elseif(($load=="main")||($load=="display")){
/*
ADD:
	Solution Finder - trouble shooter
	Glossary
	Contents
	
	Print, Email, Rate, Related Articles, Bookmark, Attachements
*/
main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<?php
if($load=="display"){
	//display the requested item
	if(isset($cat)){
		//category display
?>
	<div class="header">Category: <?php print $cat; ?></div>
	<div>&nbsp;</div>
	<div class="body">
	The following articles are associated with this category:<br><br>
<table align="center" width="100%" height="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
    <td valign="top"><!--SECTION-->
<table align="left" cellspacing="0" cellpadding="2" border="0">
<?php
//LIST ARTICLES FOR THE CATEGORY
$sql="SELECT id,title FROM $kbart_table WHERE category='$cat';";
$result1=mysql_query($sql);
	while($row1=mysql_fetch_array($result1)){//while
?>
<!--DOCUMENT-->
<tr>
    <td width="25"><FONT face=Verdana>&nbsp;</FONT></td>
    <td><FONT face=Verdana><a class="art" href="<?php print $PHP_SELF; ?>?load=display&art=<?php print $row1['id']; ?>&c=<?php print $cat; ?>" target="mainbody"><?php print $row1['title']; ?></a></FONT></td>
</tr>
<!--END DOCUMENT-->
<?php
}//end while
?>
</table>
<!--END SECTION-->
	</td>
</tr>
</table>
</div>	
<?php
	}//end cat display
	elseif(isset($art)){
		//article display
		$sql="SELECT * FROM $kbart_table WHERE id='$art';";
		$result=mysql_query($sql);
		$stuff=mysql_fetch_array($result);
?>
<div class="header"><a href="<?php print $PHP_SELF; ?>?load=display&cat=<?php print $c; ?>"><?php print $c; ?></a>&nbsp;&raquo;&nbsp;<?php print $stuff['title']; ?></div>
	<div class="body"><?php print $stuff['author']; ?></div>
	<div>&nbsp;</div>
	<div class="body">
		<hr width="80%" size="1" color="Black">
		<br><?php print $stuff['content']; ?><br>
		<hr width="80%" size="1" color="Black"><br>
	</div>
<?php
	}
	else{
		print "Invalid Material";
	}
}//end display
elseif($load=="main"){
?>
	<div class="header">The <?php print $title; ?> Knowledge Base</div>
	<div>&nbsp;</div>
	<div class="body">
	<b>Welcome To The <?php print $title; ?> Knowledge Base.<br><br>
	Please Select A Catagory On The Left <b>OR</b> Use The Menu Above.</b>
	</div>
<?php
}//end  main display
?>
</td></tr>
</table>
</body>
</html>
<?php
}//end main
//---------------------------------------------------------------------
elseif($load=="help"){
	main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<div class="header">Help</div>
	<div>&nbsp;</div>
	<div class="body">
	<?php include("kb_help.htm"); ?>
	</div>
</td></tr>
</table>
</body>
</html>
<?php
}
//---------------------------------------------------------------------
elseif($load=="search"){
	main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<div class="header">Search</div>
	<div>&nbsp;</div>
	<div class="body">
	The search tool has not been included yet.
	</div>
</td></tr>
</table>
</body>
</html>
<?php
}
//---------------------------------------------------------------------
elseif($load=="contribute"){
	main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<div class="header">Contribute</div>
	<div>&nbsp;</div>
	<div class="body">
	Contribute allows users to participate in making the Knowledge Base a significant resource for all users. At this time, submit a Support Request to contribute an article to the Knowledge Base.
	</div>
</td></tr>
</table>
</body>
</html>
<?php
}
//---------------------------------------------------------------------
elseif($load=="faqs"){
	main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<div class="header">FAQs</div>
	<div>&nbsp;</div>
	<div class="body">
	FAQs are Frequently Asked Questions, which are specially chosen by the systems administrator to be highlighted to users. They are not presently available in this version. 
	</div>
</td></tr>
</table>
</body>
</html>
<?php
}
//---------------------------------------------------------------------
elseif($load=="ras"){
	main_top();//load main page header
?>
<table width="100%" cellpadding="2">
<tr>
  <td width="100%" valign="top">
<div class="header">Recent Articles</div>
	<div>&nbsp;</div>
	<div class="body">
	Recent Articles allows you to view new articles, which may be of interested to frequent users. They are not presently available in this version. 
	</div>
</td></tr>
</table>
</body>
</html>
<?php
}
//---------------------------------------------------------------------
elseif($load=="side"){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" href="kb_style.css" type="text/css">
<link rel="STYLESHEET" type="text/css" href="kbbars.css">
</head>
<body bottommargin="0" leftmargin="0" rightmargin="0" topmargin="0">
<table align="center" width="100%" height="100%" cellspacing="0" cellpadding="2" border="0">
<?php
//LIST CATEGORIES & ARTICLES
$query="SELECT NUMBER,VALUE FROM $subj_table ORDER BY NUMBER ASC;";
$result=mysql_query($query);
while($row=mysql_fetch_array($result)){
?>
<!--SECTION-->
<tr>
    <td valign="top">
		<!--CATEGORY-->
		<table align="left" cellspacing="0" cellpadding="2" border="0">
		<tr>
		    <td colspan="2" style="size: 10;"><STRONG><FONT face=Verdana><a class="cat" href="<?php print $PHP_SELF; ?>?load=display&cat=<?php print $row['VALUE']; ?>" target="mainbody"><?php print "$row[VALUE]"; ?></a></FONT></STRONG></td>
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
		    <td width="10"></td>
		    <td style="size: 10;"><FONT face=Verdana><a class="art" href="<?php print $PHP_SELF; ?>?load=display&art=<?php print $row1['id']; ?>&c=<?php print $row['VALUE']; ?>" target="mainbody"><?php print $row1['title']; ?></a></FONT></td>
		</tr>
		<!--END DOCUMENT-->
	</td>
</tr>
<!--END SECTION-->
<?php
		}//end while2
	//END ARTICLE LIST
	print "</table>";
}//end while
?>
</table>
</body>
</html>
<?php
}//end side
//---------------------------------------------------------------------
else{
	main_top();//load main page header
	print '<div align="center"><br><b>Invalid Choice!</b>';
}//end else
//---------------------------------------------------------------------
mysql_close($link);
//---------------------------------------------------------------------
function main_top(){
	global $load;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" href="kb_style.css" type="text/css">
<link rel="STYLESHEET" type="text/css" href="kbbars.css">
<script language="JavaScript" src="kb.js"></script>
</head>
<body bgcolor="#ffffff" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if($load=="main"){ $at=1; }
elseif($load=="search"){ $at=2; }
elseif($load=="faqs"){ $at=3; }
elseif($load=="ras"){ $at=4; }
elseif($load=="help"){ $at=5; }
elseif($load=="contribute"){ $at=6; }
else{ $at=0; }
?>
<TABLE Align="Center" BORDER="0" CELLPADDING="4" CELLSPACING="0" WIDTH="100%">
<TBODY>
<tr>
<TD bgColor="#a6a6a6" width="100%">
<TABLE Align="Center" cellSpacing="0" cellPadding="0" border="0" width="100%">
<TR>
<td><img src="pixel.gif" width="20" height="1" alt="" border="0"></td>
<TD onMouseUp="up(this);" <?php if($at=="1"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="1"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=main" style="color: ffffff; text-decoration: none;">Home</a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="2"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="2"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=search" style="color: ffffff; text-decoration: none;">Search</a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="3"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="3"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=faqs" style="color: ffffff; text-decoration: none;">FAQs</a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="4"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="4"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=ras" style="color: ffffff; text-decoration: none;"><center>Recent Articles</center></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="5"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="5"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=help" style="color: ffffff; text-decoration: none;">Help</a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="6"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="6"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php print $PHP_SELF; ?>?load=contribute" style="color: ffffff; text-decoration: none;">Contribute</a></TD>
<td><img src="pixel.gif" width="45" height="1" alt="" border="0"></td>
</TR>
</TABLE>
</td>
</tr>
</tbody>
</table>
<?php
}
?>
