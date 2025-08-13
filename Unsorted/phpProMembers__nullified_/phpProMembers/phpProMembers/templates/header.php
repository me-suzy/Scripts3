<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Style-Type" content="text/css">
<META name="keywords" content="new media website design and developement company hosting php javascript html cgi perl programming custom content www flash publishing search engines support">
<META name="description" content="GACyberTech is a new media website design and developement company which can provide you with the best web developement solution for the best price.">

<title>MyDomain :: New Media Web Developement Company</title>
<style type="text/css">
<!--
/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com

  NOTE: These CSS definitions are stored within the main page body so that you can use the phpBB2
  theme administration centre. When you have finalised your style you could cut the final CSS code
  and place it in an external file, deleting this section to save bandwidth.
*/

/* General page style. The scroll bar colours only visible in IE5.5+ */
body {
	background-color: #808080;
	scrollbar-face-color: #DEE3E7;
	scrollbar-highlight-color: #FFFFFF;
	scrollbar-shadow-color: #DEE3E7;
	scrollbar-3dlight-color: #D1D7DC;
	scrollbar-arrow-color:  #006699;
	scrollbar-track-color: #EFEFEF;
	scrollbar-darkshadow-color: #98AAB1;
}

/* General font families for common tags */
font,th,td,p { font-family: Verdana, Arial, Helvetica, sans-serif }
a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900; }
hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

/* This is the border line & background colour round the entire page */
.bodyline	{ background-color: #FFFFFF; border: 1px #98AAB1 solid; }

/* This is the outline round the main forum tables */
.forumline	{ background-color: #FFFFFF; border: 2px #006699 solid; }

/* Main table cell colours and backgrounds */
td.row1	{ background-color: #EFEFEF; }
td.row2	{ background-color: #DEE3E7; }
td.row3	{ background-color: #D1D7DC; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
		background-color: #FFFFFF;
		background-image: url(templates/subSilver/images/cellpic2.jpg);
		background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th	{
	color: #FFA34F; font-size: 11px; font-weight : bold;
	background-color: #006699; height: 25px;
	background-image: url(templates/subSilver/images/cellpic3.gif);
}

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
			background-image: url(templates/subSilver/images/cellpic1.gif);
			background-color:#D1D7DC; border: #FFFFFF; border-style: solid; height: 28px;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catBottom {
	height: 29px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: #FFFFFF; border-style: solid; height: 28px;
}
td.row3Right,td.spaceRow {
	background-color: #D1D7DC; border: #FFFFFF; border-style: solid;
}

th.thHead,td.catHead { font-size: 12px; border-width: 1px 1px 0px 1px; }
th.thSides,td.catSides,td.spaceRow	 { border-width: 0px 1px 0px 1px; }
th.thRight,td.catRight,td.row3Right	 { border-width: 0px 1px 0px 0px; }
th.thLeft,td.catLeft	  { border-width: 0px 0px 0px 1px; }
th.thBottom,td.catBottom  { border-width: 0px 1px 1px 1px; }
th.thTop	 { border-width: 1px 0px 0px 0px; }
th.thCornerL { border-width: 1px 0px 0px 1px; }
th.thCornerR { border-width: 1px 1px 0px 0px; }

/* The largest text used in the index page title and toptic title etc. */
.maintitle	{
	font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif;
	text-decoration: none; line-height : 120%; color : #000000;
}

/* General text */
.gen { font-size : 12px; }
.genmed { font-size : 11px; }
.gensmall { font-size : 10px; }
.gen,.genmed,.gensmall { color : #000000; }
a.gen,a.genmed,a.gensmall { color: #006699; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover	{ color: #DD6900; text-decoration: underline; }

/* The register, login, search etc links at the top of the page */
.mainmenu		{ font-size : 11px; color : #000000 }
a.mainmenu		{ text-decoration: none; color : #006699;  }
a.mainmenu:hover{ text-decoration: underline; color : #DD6900; }

/* Forum category titles */
.cattitle		{ font-weight: bold; font-size: 12px ; letter-spacing: 1px; color : #006699}
a.cattitle		{ text-decoration: none; color : #006699; }
a.cattitle:hover{ text-decoration: underline; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink		{ font-weight: bold; font-size: 12px; color : #006699; }
a.forumlink 	{ text-decoration: none; color : #006699; }
a.forumlink:hover{ text-decoration: underline; color : #DD6900; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav			{ font-weight: bold; font-size: 11px; color : #000000;}
a.nav			{ text-decoration: none; color : #006699; }
a.nav:hover		{ text-decoration: underline; }

/* titles for the topics: could specify viewed link colour too */
.topictitle,h1,h2	{ font-weight: bold; font-size: 11px; color : #000000; }
a.topictitle:link   { text-decoration: none; color : #006699; }
a.topictitle:visited { text-decoration: none; color : #5493B4; }
a.topictitle:hover	{ text-decoration: underline; color : #DD6900; }

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name			{ font-size : 11px; color : #000000;}

/* Location, number of posts, post date etc */
.postdetails		{ font-size : 10px; color : #000000; }

/* The content of the posts (body of text) */
.postbody { font-size : 12px; line-height: 18px}
a.postlink:link	{ text-decoration: none; color : #006699 }
a.postlink:visited { text-decoration: none; color : #5493B4; }
a.postlink:hover { text-decoration: underline; color : #DD6900}

/* Quote & Code blocks */
.code {
	font-family: Courier, 'Courier New', sans-serif; font-size: 11px; color: #006600;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

.quote {
	font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #444444; line-height: 125%;
	background-color: #FAFAFA; border: #D1D7DC; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}

/* Copyright and bottom info */
.copyright		{ font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #444444; letter-spacing: -1px;}
a.copyright		{ color: #444444; text-decoration: none;}
a.copyright:hover { color: #000000; text-decoration: underline;}

/* Form elements */
input,textarea, select {
	color : #000000;
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	border-color : #000000;
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color : #FFFFFF;
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color : #EFEFEF;
	color : #000000;
	font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif;
}

/* The main submit button option */
input.mainoption {
	background-color : #FAFAFA;
	font-weight : bold;
}

/* None-bold submit button */
input.liteoption {
	background-color : #FAFAFA;
	font-weight : normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: #DEE3E7; border-style: none; }

/* Import the fancy styles for IE only (NS4.x doesn't use the @import function) */
-->
</style>
<script language="Javascript" type="text/javascript">
<!--
	if ( 0 )
	{
		window.open('privmsg.php?mode=newpm', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
	}
//-->
</script>
<style type="text/css">
<!--
td {  font-size: 9pt}
-->
</style>
<style>
BODY {SCROLLBAR-FACE-COLOR: #3366FF; SCROLLBAR-HIGHLIGHT-COLOR: #3366FF; SCROLLBAR-SHADOW-COLOR: #003399; SCROLLBAR-3DLIGHT-COLOR: #FFFFFF; SCROLLBAR-ARROW-COLOR:  #000000; SCROLLBAR-TRACK-COLOR: #3399FF; SCROLLBAR-DARKSHADOW-COLOR: #003366; }
</style>
<script language="javascript1.2">
<!--
function hidesubmenu(sid)
{
	eval("submenu" + sid + ".style.display=\"none\";");
}
function showsubmenu(sid)
{
submenu1.style.display="none";
submenu2.style.display="none";
submenu3.style.display="none";
eval("submenu" + sid + ".style.display=\"block\";");
}
//-->
</script>
</head>

<body bgcolor="#808080" text="#000000" bgcolor="#c0c0c0">
<center>
<table width="776" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#000000">
      <div align="right"><font color="#FFFFFF" face="Arial, Helvetica, sans-serif"><a href="#" style="color:white">HOME</a> :: <a href="#" style="color:white">BOARD</a></font><font color="#FFFFFF"> 
        </font></div>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="283"  bgcolor="#99CC33">
          </td>
          <td width="468" bgcolor="#99CC33">
            <div align="center">&nbsp;</div>
          </td>
          <td width="25"  bgcolor="#99CC33">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><img src="images/no.gif" width="776" height="16"></td>
  </tr>
</table>
<table width="776" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="31"  valign="top" bgcolor="#CCCCCC"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <div align="center"><a href="#"><img src="images/btn_1.gif" width="31" height="20" border="0"><br>
              <img src="images/no.gif" width="18" height="69" border="0"></a></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="center"><a href="#"><img src="images/btn_1.gif" width="31" height="20" border="0"><br>
              <img src="images/no.gif" width="18" height="69" border="0"></a></div>
          </td>
        </tr>
        <tr>
          <td>
            <div align="center"><a href="#"><img src="images/btn_1.gif" width="31" height="20" border="0"><br>
              <img src="images/no.gif" width="18" height="69" border="0"></a></div>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </td>
    <td width="144" bgcolor="#EEEEEE" valign="top"> 
      <table width="118" border="0" cellspacing="0" cellpadding="0" align="center" id=submenu1 style="DISPLAY: yes" >
        <tr> 
          <td valign="middle" nowrap class="word"> <br>
            <table width="90" border="1" cellspacing="0" cellpadding="2" align="center" bordercolorlight="#666666" bordercolordark="#cccccc">
              <tr align="center" > 
                <td height="13"><a href="">home</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">Menu 1</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">Menu 2</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">Menu 3</a></td>
              </tr>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">Menu 4</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">Menu 5</a></td>
              </tr>
			   <tr align="center" > 
                <td height="13"><a href="#">scripts</a></td>
              </tr>
			  <tr align="center" > 
                <td height="13"><a href="#">domain registration</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">forum</a></td>
              </tr>
              <tr align="center" > 
                <td height="13"><a href="#">contact</a></td>
              </tr>
            </table>
            
			<br>
			<br>
	       </td>
        </tr>
      </table>
      
      <table width="112" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td height="60"> 
            &nbsp;
          </td>
        </tr>
        <tr> 
          <td height="60"> 
            &nbsp;
          </td>
        </tr>
        <tr> 
          <td height="60"> 
            &nbsp;
          </td>
        </tr>
      </table>
    </td>
    <td width="603" bgcolor="#ffffff" valign="top"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td> 
            <table width="576" border="0" cellspacing="0" cellpadding="0" height="119" align="right">
              <tr> 
                <td height="18"> 
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="22%" height="24">&nbsp;</td>
                      <td width="78%" height="24" align="center"><font face="Arial, Helvetica, sans-serif"><b>Taking Your Business In To Tomorrow!</b></font></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td height="22"> 
                  <table border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td width="60" height="18">&nbsp;</td>
                      <td width="60" height="18"> 
                        &nbsp;
                      </td>
                      <td width="58"> 
                        &nbsp;
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td> 
                  <p>
				  	<font face="Arial, Helvetica, sans-serif">
					<br>
						<br>
							  
				  	</font>
				 </p>
                  </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr> 
          <td> 
            <table width="576" border="0" cellspacing="0" cellpadding="0" align="right">
              <tr> 
                <td> 
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td width="50%"> 
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr> 
                            <td align="left">
							  
					
    								<FONT color="#000000" size="2" face="Arial, Helvetica, sans-serif">
									<p>