<?php
session_start();
include('config.php');
?>
<HTML>
<HEAD>
<TITLE>Create a free hosting account</TITLE>
</HEAD>
<BODY LINK="lightgrey" ALINK="lightgrey" VLINK="lightgrey">
<FORM ACTION="create.php" METHOD="post">
<CENTER>
<H2><FONT FACE='verdana'>Create a free hosting account:</FONT></H2><BR>
<TABLE>
<TR>
<TD><FONT FACE='verdana'><B>Subdomain/Domain:</B><BR>
<I>No spaces or special characters and no www.</I></FONT></TD>
<TD><INPUT TYPE="text" NAME="domain2">&nbsp;<B>.</B>&nbsp;<SELECT NAME="domain"><?php
$count = count($domains);
for($i = 0; $i < $count; $i++) {
$count2 = $count - 1;
echo "<OPTION VALUE='$domains[$count2]'>$domains[$count2]</OPTION>";
}
?>
<OPTION VALUE="1">com</OPTION>
<OPTION VALUE="2">net</OPTION>
<OPTION VALUE="3">org</OPTION>
<OPTION VALUE="4">info</OPTION>
<OPTION VALUE="5">us</OPTION>
<OPTION VALUE="6">cc</OPTION>
<OPTION VALUE="7">biz</OPTION>
</SELECT><BR>&nbsp;</TD>
</TR>
<TR>
<TD><FONT FACE='verdana'><B>Username:</B><BR>
<I>Your preferred cPanel username</I></FONT></TD>
<TD><INPUT TYPE="text" NAME="user"><BR>&nbsp;</TD>
</TR>
<TR>
<TD><FONT FACE='verdana'><B>Email:</B><BR>
<I>Your email address</I></FONT></TD>
<TD><INPUT TYPE="text" NAME="email"><BR>&nbsp;</TD>
</TR>
<?php
if($skeys == "yes") {
?>
<TR>
<TD><FONT FACE='verdana'><B>Signup Key:</B><BR>
<I>Please enter the signup key that was given to you</I></FONT></TD>
<TD><INPUT TYPE='text' NAME='c2'><BR>&nbsp;</TD>
</TR>
<TR>
<?php
}
?>
<?php
if($image == "yes") {
?>
<TR>
<TD COLSPAN=2><FONT FACE='verdana'><B>Image Verification:</B><BR>
<I>Please enter the number you see in the picture</I></FONT></TD></TR>
<TR><TD ALIGN=right><IMG SRC='image.php'></TD><TD><INPUT TYPE='text' NAME='image2'></TD>
</TR>
<TR>
<?php
}
?>
</TABLE>
<FONT FACE='verdana'>
<INPUT TYPE="submit" VALUE="Create Account!"><BR>
</FONT>
<FONT SIZE="-1"><A HREF="http://www.amfrservices.com">AMFR Account Creator version 1.0 rc1</A></FONT>
</CENTER>
</FORM>
</BODY>
</HTML>