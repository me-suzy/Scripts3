<?
     if ($action == delete)
     {
         $fp = fopen("export.txt", "w+");
         fwrite($fp, "");
         fclose($fp);
?>
 <table><tr><td class=small>Your export file has been deleted.</td></td></table>

<?
         exit;
     }
     if ($action == send)
     {
           $date_month = date(m);
    $date_year = date(Y);
    $date_day = date(d);
    $time_hour = date(H);
    $time_min = date(i);
    // Date
    $Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";
    $headers = "From: $admin_mail\n"; // From address
    $headers .= "Reply-To: $admin_mail\n"; // Reply-to address
    $headers .= "Organization: webDate\n"; // Organisation
    $headers .= "Content-Type: text/plain; charset=iso-8859-1\n"; // Type
    $export_text = "";
         if ($status == 2)
         {
            $rMembers = q("SELECT id, login, email FROM dt_members");
            while ($fMembers = f($rMembers))
            {
            $fUser = f(q("SELECT id FROM dt_profile where member_id='$fMembers[id]'"));
            if ($fUser[id] == "")
            {
                if ($export)
                {
                    $export_text .= $fMembers["login"]."\t".$fMembers["email"]."\n";
                }
                else
                {
                    @mail($fMembers["email"], $messagetitle, $messagebody, $headers);
                }
            }
            }
         }
         else
         {
         if ($status == 1)
         {
            $rUsers = q("SELECT member_id, email FROM dt_profile WHERE (status='$status')");
         }
         else if ($status == 3)
         {
            $rUsers = q("SELECT member_id, email FROM dt_profile WHERE (status='$status')");
         }
         else
         {
            $rUsers = q("SELECT member_id, email FROM dt_profile");
         }
         while($fUsers = f($rUsers))
         {
                if ($export)
                {
                    $fMembers = f(q("SELECT login, email FROM dt_members where id=$fUsers[member_id]"));
                    $export_text .= $fMembers["login"]."\t".$fMembers["email"]."\n";
                }
                else
                {
                    @mail($fUsers["email"], $messagetitle, $messagebody, $headers);
                }
         }
         }
         if ($export)
         {
             $fp = fopen("export.txt", "w+");
             fwrite($fp, $export_text);
             fclose($fp);
         ?>
         <table><tr><td class=small>Export successful!  Please <a href="export.txt" target="_blank">download it here</a>.</td></td></table>
         <?
         }
         else
         {
         ?>
         <table><tr><td class=small>Your message has been sent!</td></td></table>
         <?
         }
         exit;
      }
?>
<form action="main.php?service=newsletter.php&action=send" method=POST>
<table width="500" cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="500"><img src="images/newsletter.gif" width="229" height="22" alt="" border="0"></td>
</tr>
<tr>
<td><img src="images/dot.gif" width="1" height="1" alt="" border="0"></td>
</tr>
<tr>
<td style="border:1px solid #999999;padding:15px;">
<table width="100%" border="0" cellspacing="2" cellpadding="2">

  <tr>
    <td class="small"><font color="red">To ensure that the PHP script does not time out, please contact your system adminstrator about editing the "PHP timeout limit".  PLEASE BE PATIENT WHEN MAILING MANY MEMBERS.  SENDING NEWSLETTERS TO TOO MANY MEMBERS MAY CAUSE A LARGER STRESS ON THE SERVER, AND IT IS HIGHLY RECOMMENDED THAT THE EXPORT FUNCTION IS USED INSTEAD AND IMPORTED IN A MORE SUITABLE MAILING PROGRAM.</font></td>
  </tr>
  <tr><td>
            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" class="small"><b>SEND MAIL TO MEMBERS</b></td>
              </tr>
              <tr>
                <td width="50%">Member type:</td>
                <td width="50%">
                  <select name="status">
                    <option value="" selected>All members with a profile</option>
                    <option value="1">Approved members only</option>
                    <option value="2">Members without a profile</option>
                    <option value="3">Members awaiting profile approval</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="50%">Message title:</td>
                <td width="50%">
                  <input type=text name="messagetitle" size=30>
                </td>
              </tr>
              <tr>
                <td width="50%">Message body:</td>
                <td width="50%">
                  <textarea cols=45 rows=10 name="messagebody"></textarea>
                </td>
              </tr>
              <tr>
                <td width="50%">Export to tab delimited file?</td>
                <td width="50%">
                  <input type=checkbox name=export>
                </td>
              </tr>
              <tr>
                <td colspan=2 align=right><input type=submit value="Send Mail/Export"></td>
              </tr>
            </table>
    </td>
  </tr>


</table>		</td>
  </tr>
</table></form>
<form action="main.php?service=newsletter.php&action=delete" method=POST>
<table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" class="small"><input type=submit value="Delete Exported File"></td>
              </tr>
            </table>
            </form>
