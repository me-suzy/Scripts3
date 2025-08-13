
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <form action="register.php" name="Register" method="post">
    <tr> 
      <td width="160">&nbsp;</td>
      <td><font face="Arial, Helvetica, sans-serif" size="2">Please enter in your 
        Member ID (4-10 characters) and Password (4-10 characters). </font></td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">Member ID:</font></b></td>
      <td> 
        <input type="text" name="MemberID" class="inputbox" size="10" maxlength="10" <? print("value=\"$MemberID\"") ?> >
      </td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">Password:</font></b></td>
      <td> 
        <input type="password" name="Password" class="inputbox" size="10" maxlength="10" <? print("value=\"$Password\"") ?> >
      </td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">Password (again):</font></b></td>
      <td> 
        <input type="password" name="Password2" class="inputbox" size="10" maxlength="10" <? print("value=\"$Password2\"") ?> >
      </td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">E-mail:</font></b></td>
      <td> 
        <input type="text" name="Email" class="inputbox" size="20" maxlength="50" <? print("value=\"$Email\"") ?> >
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">Name:</font></b></td>
      <td> 
        <table width="50%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">First</font></td>
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">Middle</font></td>
            <td width="33%"><font face="Arial, Helvetica, sans-serif" size="2">Last</font></td>
          </tr>
          <tr> 
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="First" class="inputbox" maxlength="20" size="10" value=<? print("$First"); ?> >
              </font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="Middle" class="inputbox" maxlength="20" size="10" value=<? print("$Middle"); ?> >
              </font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="Last" class="inputbox" maxlength="20" size="10" value=<? print("$Last"); ?> >
              </font></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><font face="Arial, Helvetica, sans-serif" size="2">City and State information 
        is used to display the region from where you are located when you post 
        an ad.</font></td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">City:</font></b></td>
      <td> 
        <input type="text" name="City" class="inputbox" size="25" maxlength="50" <? print("value=\"$City\"") ?> >
      </td>
    </tr>
    <tr> 
      <td><b><font face="Arial, Helvetica, sans-serif">State:</font></b></td>
      <td> 
        <input type="text" name="State" class="inputbox" size="2" maxlength="2" <? print("value=\"$State\"") ?> >
      </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><font face="Arial, Helvetica, sans-serif" size="2">By continuing, you 
        agree to the following terms and conditions as outlined in the <a href="legals.php" target="_blank">User 
        Agreement and Disclaimer</a>. </font> </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp; </td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>
        <input type="checkbox" name="SavePwd" value="save">
        <font face="Arial, Helvetica, sans-serif" size="2"> Save password on my 
        computer. </font></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><a href="javascript:document.Register.submit()"><font face="Arial, Helvetica, sans-serif"><b><font size="2">Sign 
        up!</font></b></font></a></td>
    </tr>
  </form>
</table>

