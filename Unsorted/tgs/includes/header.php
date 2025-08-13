<?php
if(!session_is_registered("sessionUser"))
{
	session_register("sessionUser");
}
if(!session_is_registered("sessionSiteId"))
{
	session_register("sessionSiteId");
}
include("includes/config.php");
include("includes/db_inc.php");
?>
<html>
<head>
<title><?php print $sitename; ?> - dig up the stats</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<LINK rel="stylesheet" href="includes/general.css" type="text/css">
</head>

<body class="text" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action=""></form>
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td height="26" background="images/top_redbg.gif"><table width="700" border="0" cellpadding="0" cellspacing="0" class="text">
          <tr> 
            <td width="10"><img src="images/spacer.gif" width="20" height="26"></td>
            <td width="690"><a href="index.php"><strong><font color="#FFFFFF">home</a> &nbsp;&nbsp;l&nbsp;&nbsp;&nbsp;
            <a href="contactUs.php"><font color="#FFFFFF">contact 
              us</a></font></font></strong></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="120" background="images/top_yellowbg.gif"><table width="770" border="0" cellspacing="0" cellpadding="0">
          <tr class="text"> 
            <td width="151"><img src="images/top01.gif" width="151" height="93"></td>
            <td width="316" rowspan="2" align="left"><img src="images/top02.gif" width="316" height="120"></td>
            <td width="303" rowspan="2"><img src="<?php print $siteLogo; ?>" width="303" height="120"></td>
          </tr>
          <tr class="text"> 
            <td height="27" align="center" bgcolor="044E95"><font color="#FFFFFF"> 
              <script language="JavaScript1.2">

<!-- Begin
var months=new Array(13);
months[1]="January";
months[2]="February";
months[3]="March";
months[4]="April";
months[5]="May";
months[6]="June";
months[7]="July";
months[8]="August";
months[9]="September";
months[10]="October";
months[11]="November";
months[12]="December";
var time=new Date();
var lmonth=months[time.getMonth() + 1];
var date=time.getDate();
var year=time.getYear();
if (year < 2000)    // Y2K Fix, Isaac Powell
year = year + 1900; // http://onyx.idbsu.edu/~ipowell
document.write("<right>" + lmonth + " ");
document.write(date + ", " + year + "</right>");
// End -->
</script>
              </font></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="35" background="images/but_bg.gif"><table width="770" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="260"><img src="images/product_search.gif" width="260" height="35"></td>
            <td align="center"><a href="index.php"><img src="images/but01.gif" border="0" width="122" height="35"></a><img src="images/but_space.gif" width="19" height="35"><a href="login.php"><img src="images/but02.gif" border="0" width="70" height="35"></a><img src="images/but_space.gif" width="19" height="35"><a href="register.php"><img src="images/but03.gif" border="0" width="119" height="35"></a><img src="images/but_space.gif" width="19" height="35"><a href="logout.php"><img src="images/but04.gif" border="0" width="93" height="35"></a></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td align="left" valign="top" bgcolor="FFF8D0"><table width="770" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="254" valign="top" bgcolor="#FFFFFF"><table width="254" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td height="11" valign="top"><img src="images/product_search2.gif" width="254" height="11"></td>
                </tr>
                <tr> 
                  <td align="center" bgcolor="FFEE7A"><table width="207" border="0" cellpadding="2" cellspacing="0" class="text">
                      <tr align="left" class="text"> 
                        <td height="6" colspan="2"><img src="images/spacer.gif" width="20" height="6"></td>
                      </tr>
                      <tr align="left" class="text"> 
                        <td colspan="2"><strong><font color="#000000"><center><?php print $sitename; ?></font>
                        <br><font color="#0000FF">The Ultimate Statisic Monitoring Service!</center></font></strong>
                       </td>
                      </tr>
                      <tr align="center" class="text"> 
                        <td width="153" align="left"></td>
                        <td width="46"></td>
                      </tr>
                      <tr align="center" class="text"> 
                        <td height="6" colspan="2" align="left"><img src="images/spacer.gif" width="20" height="6"></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="45" valign="top"><img src="images/thelatest.gif" width="254" height="45"></td>
                </tr>
                <tr> 
                  <td align="center" bgcolor="EFEFEF"><table width="240" border="0" cellpadding="0" cellspacing="0" class="text">
                      <tr valign="top" class="text"> 
                        <td height="10" colspan="2"><img src="images/spacer.gif" width="20" height="10"></td>
                      </tr>
                      <tr class="text"><?php
                	include("includes/menu.php");
                                               ?></tr>
                      </tr>
                      <tr align="left" class="text"> 
                        <td height="10" colspan="2"><img src="images/spacer.gif" width="20" height="10"></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="1" align="center" bgcolor="#000000"><img src="images/spacer.gif" width="100" height="1"></td>
                </tr>
                <tr> 
                  <td align="center">&nbsp;</td>
                </tr>
              </table></td>
            <td width="6" align="left" background="images/side_bg.gif"><img src="images/spacer.gif" width="6" height="30"></td>
            <td align="center" valign="top"><br>
              <table width="490" border="0" cellspacing="0" cellpadding="0">
            <tr class="text">
