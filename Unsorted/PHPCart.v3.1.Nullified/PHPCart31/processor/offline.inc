
<div align="center">
  <center>
  <table border="0" cellspacing="1" width="100%">
    <tr>
      <td width="100%" bgcolor="#000000">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" class="main_bold" align="center" bgcolor="#FFFFFF">
<font size="2" face="Verdana"><font color="#000000">Thank you for submitting your order.
<br><BR><BR><B>Total Amount Due: <? echo "$currency"; ?><? echo "$total"; ?></B><br>
Please send payment made payable to '<B><?php echo $offlinename?></B>' to our address below:
<br>
<br>
</font>
<table border="0" width="160" cellspacing="0" cellpadding="0">
<tr>
<td width="160" class="main">
<b>
<font color="#000000" font size="2" face="Verdana">
<?php echo $offlineaddress?>
<BR>
</font>
</b>
</td>
</tr>
</table>
<font color="#000000">
<br>
An order confirmation email has been sent to <?php echo $email ?>.</font>
</td>
</tr>
</table>
</td>
    </tr>
  </table>
  </center>
</div>
