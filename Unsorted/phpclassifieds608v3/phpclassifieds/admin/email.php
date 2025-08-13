<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 
&nbsp; Email all members 
</td>
</tr>

<tr bgcolor="white">
<td width="100%">
 
<?
if ($submit AND $email_activated)
{
     $sql_email = "select distinct name,email from $usr_tbl where emelding = 0 AND email <> ''";
     $result = mysql_query ($sql_email);
     $num_email =  mysql_num_rows($result);

     while ($row = mysql_fetch_array($result))
     {
             $name = $row["name"];
             $email = $row["email"];
             $sendto = "$email";
             $from = "$from_adress";
             $subject = "$title_field";
             $message = "$beskjed";
             $headers = "From: $from\r\n";
             // send e-mail
             mail($sendto, $subject, $message, $headers);
             $count = $count +1;
             print (" $count: <b>$name</b> - <b>$email</b> <br />");
     }
?>
 We have now sent to <b>$count</b> users. 
<?
} 
else
{
?>
  <form method="post" action="<?php echo $PHP_SELF?>">
  <table border="0" cellspacing="1" width="80%">
  <tr>
  <td width="100%">
  <?
  $sql_email = "select distinct name,email from $usr_tbl where emelding = 0 AND email <> ''";
  $result = mysql_query ($sql_email);
  $num_email =  mysql_num_rows($result);
  print("<select size=1 name=members>");
  print("<option selected>See all members</option>");
  while ($row = mysql_fetch_array($result))
  {
          $name = $row["name"];
          $email = $row["email"];
          print("<option>$name [$email]</option>");
  }
  print("</select>");

  if (!$email_activated)
  {
   print("<br />   Email-send is not acivated in config. Email will not be sent. ");
  }
  ?>
  <p>
 
<? print("$num_email users have accepted to recieve email from you.
         From here you can email all these people. <i>Please notice that if you have many people on your list,
         this can be a problem tha not all will get their email due to timeout in php. This often occur if script in browser uses
         more than 30 sek to complete. A thumb rule is that 1200 users take 5-6 sec.</i>"); ?><p>
You can also do a <a href="email_dump.php">email dump</a> to a textfile, if you got a better suited program
for your newsletter sendouts.

 <p><p>


<table border="0" cellspacing="1" width="100%">
<tr>
 <td width="50%" valign="top"> Subject </td>
 <td width="50%" valign="top"> <input type="text" name="title_field" size="42" style="font-size: 8pt; font-family: Verdana"> </td>
</tr>
<tr>
 <td width="50%" valign="top"> Email </td>
 <td width="50%" valign="top"> <? echo $from_adress ?> </td>
</tr>
<tr>
 <td width="50%" valign="top"> Message </td>
 <td width="50%" valign="top"><textarea rows="8" name="beskjed" cols="42" style="font-size: 8pt; font-family: Verdana"></textarea></td>
</tr>
</table>
<input type="submit" value="Submit" name="submit" style="font-size: 8pt; font-family: Verdana"></td>
</tr>
</table>
</form>
<?
}
?>
 
</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
