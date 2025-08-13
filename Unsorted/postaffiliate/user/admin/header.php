<HTML><HEAD><TITLE>POST Affiliate</TITLE>
<META content="affiliate system, php, postaffiliate, post affiliate" name=description>
<META content="affiliate system, php, postaffiliate, post affiliate" name=KeyWords>
<STYLE type=text/css>
.peheader 
{
  font-family: Verdana, Tahoma, Arial;
  font-size: 20px; 
  color: #ffffff;
  TEXT-ALIGN: center;
	FONT-WEIGHT: bold
}
.ok {
	color: #009900;
}
.error {
	color: #ff0000;
}
TD {
  font-family: Verdana, Tahoma, Arial;
  font-size: 13px;
}
TH {
	TEXT-DECORATION: bold;  
  font-family: Verdana, Tahoma, Arial;
  font-size: 13px;
}
A {
	TEXT-DECORATION: none;
  font-family: Arial, Verdana, Tahoma;
  font-size: 13px;
}
A.headLink:hover {
	COLOR: #ffffff; 
}
A.headLink:visited {
	COLOR: #ffffff; 
}
A.headLink:link {
	COLOR: #ffffff; 
}
</STYLE>
<BODY leftMargin=0 topMargin=0>
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"> 
  <tr valign="top" height="62"> 
    <td height="62" valign="top"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="2"> 
    <tr> 
      <td colspan=2 bgcolor="#006699" height="34">&nbsp;&nbsp;<span class=peheader><i>POST Affiliate 1.0</i> - Admin</span></td> 
    </tr> 
    <tr> 
       <td bgcolor="#6699CC">
<? if(aff_admin_check_security()) { ?>
       &nbsp;
   	   <a class="headLink" href=payout.php><?=AFF_M_CHANGEAMOUNT?></a>
       &nbsp;|&nbsp;      
    	 <a class="headLink" href=clicks.php><?=AFF_M_CLICKTHROUGHS?></a>
       &nbsp;|&nbsp;
    	 <a class="headLink" href=sales.php><?=AFF_M_SALES?></a>
       &nbsp;|&nbsp;
    	 <a class="headLink" href=banners.php><?=AFF_M_BANNER?></a>
       &nbsp;|&nbsp;      
    	 <a class="headLink" href=affiliates.php><?=AFF_M_AFFILIATES?></a>
    	 &nbsp;|&nbsp;
<?  } ?>
      </td> 
      <td bgcolor="#6699CC" align=right>
<? if(aff_admin_check_security()) { ?>
      <a class="headLink" href=logout.php><?=AFF_M_LOGOUT?></a>
      &nbsp;
<? } ?>
      </td>
    </tr> 
    </table> 
    </td> 
  </tr> 
  <tr> 
    <td valign=top align=left>
    <table width=100% height=100% border=0 cellpadding=8 cellspacing=0>
    <tr>
      <td valign=top align=left>
      <center>
      


