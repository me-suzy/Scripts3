<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 
&nbsp; Dump to newsletter .txt file 
</td>
</tr>

<tr bgcolor="white">
<td width="100%">
 
<form action='email_dump.php' method='post'>
<p>Dump of email addresses to dump.txt. Then you can import these emailaddresses from the dump.txt file
created into your prefered newsletter program.</p>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="25%"> Delimiter (seperate each record in a line) </td>
    <td width="25%"></td>
    <td width="25%"><select size="1" name="delimiter">
        <option selected value="|">|</option>
        <option value=":">:</option>
        <option value="::">::</option>
      </select></td>
  </tr>

  <td width="25%"> Field to include </td>
    <td width="25%"></td>
    <td width="25%"><select size="1" name="fields">
        <option selected value="1">Username and Email</option>
        <option value="2">Email</option>
        <option value="3">Email and Username</option>
      </select></td>
  </tr>

  <tr>
    <td width="25%"><input type="submit" value="Dump" name="submit"></td>
    <td width="25%"></td>
    <td width="25%"></td>
  </tr>
</table>


</form>

<?
    if ($submit)
    {
     print " <b>Dump.txt is created!</b> ";
     $file_pointer = fopen("dump.txt", "w");
     fwrite($file_pointer,"");
     fwrite($file_pointer,$string);
     fclose($file_pointer);
     $sql_email = "select distinct name,email from $usr_tbl where emelding = 0 AND email <> ''";
     $result = mysql_query ($sql_email);
     $num_email =  mysql_num_rows($result);
     $file_pointer = fopen("dump.txt", "a");
     while ($row = mysql_fetch_array($result))
     {
             $name = $row["name"];
             $email = $row["email"];
             $count = $count +1;

             if ($fields == 1)
             {
                  fwrite($file_pointer,"$name$delimiter$email\n");
             }
             if ($fields == 2)
             {
                  fwrite($file_pointer,"$email\n");
             }

             if ($fields == 3)
             {
                  fwrite($file_pointer,"$email$delimiter$name\n");
             }



     }
     fwrite($file_pointer,$string);
     fclose($file_pointer);


    }
?>
 
</td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
