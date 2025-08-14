<?


include_once("config.inc.php");

session_start();
$sids = session_id();
// Log-out part
if (isset($_GET['logout'])){
       unset($_SESSION['pwd']);
       header("Location: admin.php");
}

// Log-In
$pass= $_POST['pass'];

if (isset($pass)){
  if ($pass == $admin_pass)
  {
       $_SESSION['pwd'] = $pass;
  }
  header("Location: admin.php");
}


// print out the design part of the admin page .. :)
print $admin_page_title;


$pwd = $_SESSION['pwd'];

if (isset($pwd)&& $pwd==$admin_pass){

// handle member deletion
if (isset($_GET["delete_member"]))
{
  $query = "DELETE FROM rotate_usr WHERE id='".$_GET["delete_member"]."';";
  mysql_query($query);
  print '<h4>Member #'.$_GET["delete_member"].' was deleted successfully!</h4>';
}

// handle member info editing .. :) .. the actual information saving .. :)
if (isset($_POST["save_member_info"]))
{
  $query = "UPDATE rotate_usr SET account_type='".$_POST["account_type"]."' WHERE id='".$_POST["member_id"]."';";
  mysql_query($query);
  print '<h4>Member info saved!</h4>';
}

// handle mass e-mail
if (isset($_POST["mass_mail"]))
{
  // set script execution time to unlimited, as we don't know how long will it take to mail all members.
//  set_time_limit(0);
  $query = "SELECT email,login FROM rotate_usr;";
  $result = mysql_query($query);
  $subject = $_POST["subj"];
  $message = $_POST["body"];
  $headers  = "MIME-Version: 1.0\r\n";
  $headers .= "From: $from_mail_name <$from_mail>\r\n";

  while ($member = mysql_fetch_row($result))
  {
    $to  = $member[1]." <".$member[0].">" ;
    mail($to, $subject, $message, $headers);
  }
  print '<h4>Mass e-mail to all members has been sent!</h4>';
}

// handle url viewing .. :)
if (isset($_GET["view_urls"]))
  {
    $query = "SELECT login,email FROM rotate_usr WHERE id='".$_GET["view_urls"]."';";
    $member_name = mysql_query($query);
    $member_name = mysql_fetch_row($member_name);
    $query = "SELECT * FROM rotate WHERE user_id='".$_GET["view_urls"]."';";
    $result = mysql_query($query);
    print '
      <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
        <tr bgcolor="'.$tcolor.'">
          <th>Statistics of <a href="mailto:'.$member_name["1"].'">'.$member_name[0].'</a></th>
          <th>Hits</th>
        </tr>
        ';
    while ($row = mysql_fetch_assoc($result))
    {  $kl += 1;
       if ($kl % 2 == 0)
         {
           print '<tr bgcolor="'.$lrcolor.'">';
         }
         else
         {
           print '<tr bgcolor="'.$drcolor.'">';
         }
       print '
         <td><a href="'.$row["url"].'" target="_blank">'.$row["url"].'</a></td>
         <td>'.$row["total_hits"].'</td>
       </tr>
       ';
    }
    $query = "SELECT SUM(count) FROM rotate_clicks WHERE date='".$datetoday."' AND user_id='".$_GET["view_urls"]."';";
    $hits_today = mysql_fetch_row(mysql_query($query));
    $query = "SELECT total_hits FROM rotate_usr WHERE id='".$_GET["view_urls"]."';";
    $hits_total = mysql_fetch_row(mysql_query($query));
    print '<tr><td><p align="right"><font size="1">Hits Today</font></p></td><td><p align="right"><font size="1">&nbsp;<strong>'.($hits_today[0]*1).'</strong></font></p></td></tr>
           <tr><td><p align="right"><font size="1">Total Hits generated:</font></p></td><td><p align="right"><font size="1">&nbsp;<strong>'.($hits_total[0]*1).'</strong></p></font></td></tr>';
    print '</table><br />';

}

// handle member info editing - print out the info box
if (isset($_GET["edit_member"]))
{
  $query = "SELECT * FROM rotate_usr WHERE id='".$_GET["edit_member"]."';";
  $result = mysql_query($query);
  $member = mysql_fetch_assoc($result);
  print '
    <form action="admin.php" method="POST">
    <input type="hidden" name="member_id" value="'.$member["id"].'">
    <table cellspacing="0" cellpadding="0" width="80%" border="1" bordercolor="'.$bcolor.'">
    <tr bgcolor="'.$tcolor.'"><th colspan="2">Member information editing</th></tr>
    <tr><td>Login:</td><td>'.$member["login"].'</td></tr>
    <tr><td>Password:</td><td>'.$member["password"].'</td></tr>
    <tr><td>Email:</td><td><a href="mailto:'.$member["email"].'">'.$member["email"].'</a></td></tr>
    <tr><td>Account Type:</td><td><select name="account_type" size="1">';
   print '<option value="'.$member["account_type"].'" selected="selected">'.$member["account_type"].'</option>';

   // show up the selected account type option and print the second option as a non-selected one .. ;)
   switch($member["account_type"])
   {
     case "Premium":
       print '<option value="Standard">Standard</option>';
     break;
     case "Standard":
       print '<option value="Premium">Premium</option>';
     break;
   }
  print'</select></td></tr>
    <tr><td>Sign-Up Date:</td><td>'.$member["sign_up_date"].'</td></tr>
    <tr><td colspan="2"><center><input type="submit" name="save_member_info" value="Save" /></center></td></tr>
    </table></form><br /><br />

  ';
}



  $start_from = $_GET["start_from"];
    if (!isset($start_from))
     {
       $start_from=0;
     }

  // get the count of members .. :)
  $query = "SELECT COUNT(*) FROM rotate_usr;";
  $count = mysql_query($query);
  $count = mysql_fetch_row($count);
  $count = $count[0];
  
  $query = "SELECT * FROM rotate_usr LIMIT ".$start_from.",".$members_per_page.";";
  $result = mysql_query($query);
  print '
    <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
      <tr bgcolor="'.$tcolor.'">
        <th>Member Login</th>
        <th>Member e-mail</th>
        <th>Sign-up Date</th>
        <th>Account Type</th>
        <th>Options</th>
      </tr>
      ';
  while ($member = mysql_fetch_assoc($result))
  {  $kl += 1;
     if ($kl % 2 == 0)
       {
         print '<tr bgcolor="'.$lrcolor.'">';
       }
       else
       {
         print '<tr bgcolor="'.$drcolor.'">';
       }
     print '
       <td>'.$member["login"].'</td>
       <td><a href="mailto:'.$member["email"].'">'.$member["email"].'</a></td>
       <td>'.$member["sign_up_date"].'</td>
       <td><center>'.$member["account_type"].'</center></td>
       <td><center><a href="admin.php?edit_member='.$member["id"].'">Edit</a>&nbsp;|&nbsp;<a href="admin.php?delete_member='.$member["id"].'">Delete</a>&nbsp;|&nbsp;<a href="admin.php?view_urls='.$member["id"].'">View stats</a></center></td>
     </tr>
     ';
  }
  if ($count > $members_per_page)
  {
    print '<tr><td colspan="5">Page: ';
    for ($k = 1; $k <= ceil($count/$members_per_page);$k++)
    {
      print '<a href="admin.php?start_from='.(($k-1)*$members_per_page).'">'.$k.'</a>&nbsp;';
    }
    print '</td></tr>';
  }
  print '</table>';

  if (isset($_POST["member_search"]))
  {
    switch($_POST["s_by"])
    {
      case "url":
        $query = "SELECT rotate.url, rotate_usr.email, rotate_usr.login, rotate.id, rotate.user_id, rotate.total_hits FROM rotate, rotate_usr WHERE rotate.user_id = rotate_usr.id AND rotate.url LIKE '%".$_POST["search_string"]."%'";
        $result = mysql_query($query);
        print '
          <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
           <tr bgcolor="'.$tcolor.'">
            <th>Member Login</th>
            <th>Member e-mail</th>
            <th>URL</th>
            <th>Hits</th>
           </tr>
          ';
        while ($member = mysql_fetch_assoc($result))
          {  $kl += 1;
             if ($kl % 2 == 0)
               {
                 print '<tr bgcolor="'.$lrcolor.'">';
               }
               else
               {
                 print '<tr bgcolor="'.$drcolor.'">';
               }
             print '
              <td>'.$member["login"].'</td>
              <td><a href="mailto:'.$member["email"].'">'.$member["email"].'</a></td>
              <td><a href="'.$member["url"].'" target="_blank">'.$member["url"].'</a></td>
              <td><center>'.$member["total_hits"].'</center></td>
             </tr>
            ';
         }
         print '</table>';
      break;
      case "usr":
        $query = "SELECT * FROM rotate_usr WHERE login LIKE '%".trim($_POST["search_string"])."%' ;";
        $result = mysql_query($query);
        print '
        <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
          <tr bgcolor="'.$tcolor.'">
            <th>Member Login</th>
            <th>Member e-mail</th>
            <th>Sign-up Date</th>
            <th>Account Type</th>
            <th>Options</th>
          </tr>
         ';
        while ($member = mysql_fetch_assoc($result))
         {  $kl += 1;
           if ($kl % 2 == 0)
             {
               print '<tr bgcolor="'.$lrcolor.'">';
             }
             else
             {
               print '<tr bgcolor="'.$drcolor.'">';
             }
           print '
            <td>'.$member["login"].'</td>
            <td><a href="mailto:'.$member["email"].'">'.$member["email"].'</a></td>
            <td>'.$member["sign_up_date"].'</td>
            <td><center>'.$member["account_type"].'</center></td>
            <td><center><a href="admin.php?edit_member='.$member["id"].'">Edit</a>&nbsp;|&nbsp;<a href="admin.php?delete_member='.$member["id"].'">Delete</a>&nbsp;|&nbsp;<a href="admin.php?view_urls='.$member["id"].'">View stats</a></center></td>
         </tr>';
        }
        print '</table>';
      break;
      case "eml":
        $query = "SELECT * FROM rotate_usr WHERE email LIKE '%".trim($_POST["search_string"])."%' ;";
        $result = mysql_query($query);
        print '
        <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
          <tr bgcolor="'.$tcolor.'">
            <th>Member Login</th>
            <th>Member e-mail</th>
            <th>Sign-up Date</th>
            <th>Account Type</th>
            <th>Options</th>
          </tr>
         ';
        while ($member = mysql_fetch_assoc($result))
         {  $kl += 1;
           if ($kl % 2 == 0)
             {
               print '<tr bgcolor="'.$lrcolor.'">';
             }
             else
             {
               print '<tr bgcolor="'.$drcolor.'">';
             }
           print '
            <td>'.$member["login"].'</td>
            <td><a href="mailto:'.$member["email"].'">'.$member["email"].'</a></td>
            <td>'.$member["sign_up_date"].'</td>
            <td><center>'.$member["account_type"].'</center></td>
            <td><center><a href="admin.php?edit_member='.$member["id"].'">Edit</a>&nbsp;|&nbsp;<a href="admin.php?delete_member='.$member["id"].'">Delete</a>&nbsp;|&nbsp;<a href="admin.php?view_urls='.$member["id"].'">View URLs</a></center></td>
         </tr>';
        }
        print '</table>';
      break;
    }
  }
      print '
      <form action="admin.php" method="post">
        <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
          <tr bgcolor="'.$tcolor.'">
            <th>Search</th>
          </tr>
          <tr>
            <td>
              Search by: <br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="s_by" value="url" />URL<br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="s_by" value="usr" />Username<br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="s_by" value="eml" />e-mail<br /><br />
              Search String: <input name="search_string" type="text" size="30" />
            </td>
          </tr>
          <tr>
            <td>
              <center>
                <input type="submit" name="member_search" value="Search" />
              </center>
            </td>
          </tr>
        </table>
  </form>
  ';

  
  print '
  <form action="admin.php" method="post">
    <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
     <tr bgcolor="'.$tcolor.'"><th>Mass e-mail all members</th></tr>
     <tr><td>Subject: <input name="subj" size="50" /></td></tr>
     <tr><td>Message Body:<br />
     <textarea name="body" rows="10" cols="70"></textarea></td></tr>
     <tr><td><center><input type="submit" name="mass_mail" value="Send" /></center></td></tr>
    </table>
    </form>
    ';
    
    
// STATS start!!!!
$query = "SELECT SUM(total_hits) FROM rotate_usr GROUP BY account_type ORDER BY account_type ASC;";
$result = mysql_query($query);
$standard = mysql_fetch_row($result);
$premium = mysql_fetch_row($result);
$query = "select  sum(count), account_type from rotate_clicks, rotate_usr where rotate_clicks.user_id = rotate_usr.id AND date='".$datetoday."' GROUP BY account_type ORDER BY account_type ASC;";
$result = mysql_query($query);
$today_standard = mysql_fetch_row($result);
$today_premium = mysql_fetch_row($result);
print '    <table cellspacing="0" cellspacing="2" width="80%" border="1" bordercolor="'.$bcolor.'">
      <tr bgcolor="'.$tcolor.'">
      <th>Service Statistics</th>
      </tr>
      <tr>
        <td>
          Total hits for Standard account holders: '.$standard[0].'&nbsp;(Today: '.$today_standard[0].')<br />
          Total hits for Premium account holders: '.$premium[0].'&nbsp;(Today: '.$today_premium[0].')<br />
          Total hits:'.($standard[0]*1+$premium[0]*1).'&nbsp;(Today: '.($today_standard[0]*1+$today_premium[0]*1).')<br />
        </td>
      </tr>
      </table><br />';
// stats end!!!

}else{
print "<form action='admin.php' method='post'>";
print "In Order to access member area, please provide valid password:<br>";
print 'Password:<input name="pass" type="password"><input type="submit" value="Log-in">';
print "</form>";
}
    print $admin_page_bottom;
?>
