<?

// *******************************************************************
// script part starts there, normally nothing should be changed below!
// *******************************************************************

include_once("config.inc.php");

// Log-out part
if (isset($_GET['logout'])){
       $pass = "";
       setcookie ("pwd","",time()-3600);
       $pwd = "";
       setcookie("name","",time()-3600);
       $name="";
       setcookie("type","",time()-3600);
       $type="";
       header("Location: member.php");
}

// Log-In
$pass= $_POST['pass'];

if (isset($pass)){
  $query = "SELECT * FROM rotate_usr WHERE login='".$_POST['login']."' AND password='".$_POST['pass']."';";
  $res = mysql_query($query);
  if (mysql_num_rows($res)==1)
  {
       $result = mysql_fetch_assoc($res);
       setcookie ("pwd",$pass,time()+3600);
       $pwd = $pass;
       setcookie ("name",$result['login'],time()+3600);
       $name = $result['login'];
       setcookie ("type",$result['account_type'],time()+3600);
       $type = $result['account_type'];
       setcookie ("id",$result['id'],time()+3600);
  }
  header("Location: member.php");
}

print $design_top;
//       print "<html><head><title>WGS-Rotate Script member Area</title><style>BODY{font-family:verdana;font-size:10pt}TD{font-size:10pt;}</style></head><body><center>";




$pwd = $_COOKIE['pwd'];
$name = $_COOKIE['name'];
$type = $_COOKIE['type'];
$user_id = $_COOKIE['id'];
$query = "select * from rotate_usr where login='".$name."' and password='".$pwd."';";
$res = mysql_query($query);
if (isset($pwd)&& mysql_num_rows($res)==1){
       print '<h4>Welcome back, <strong>'.$name.'</strong>&nbsp;&nbsp;[Account type: '.$type.']</h4>';
       print 'After making the changes to the urls, you can send all your traffic to:<br />
              <a href="rotator.php?u='.$name.'">rotator.php?u='.$name.'</a>';
       // ****************************************
       // URL MANAGER Part 1
       // ****************************************

       if ($_GET['add']==1){
       // HANDLE URL ADDITION
         $query = "INSERT INTO rotate (url,weight,total_hits,user_id)values('".$_POST['url']."','".$_POST['weight']."','0','".$user_id."');";
         mysql_query($query);
         $query = "UPDATE rotate SET hits='-1' WHERE user_id='".$user_id."';";
         mysql_query($query);
         
         // get member login .. :)
         $query = "SELECT login FROM rotate_usr WHERE id='".$user_id."';";
         $member_name = mysql_query($query);
         $member_name = mysql_fetch_row($member_name);
         $member_name = $member_name[0];
         
         $_id = mysql_fetch_row(mysql_query("SELECT MAX(id) FROM rotate where user_id='".$user_id."';"));
         mail($admin_email, "New URL added", "Hi, \nNew url has been added to your service!\n\nLogin: $member_name\nURL: ".$_POST["url"]."\n\nFreeUrlRotator Script","From: freeurl@yourdomain.com");
         print "<h5>Status: URL successfully added, <font color='#FF0000'>don't refresh this page!</font></h5>";
       }
       if (isset($_GET['del'])){
       // HANDLE URL DELETION
         $query = "DELETE FROM rotate WHERE id='".$_GET['del']."' AND user_id='".$user_id."';";
         mysql_query($query);
         $query = "DELETE FROM rotate_clicks WHERE url_id='".$_GET['del']."';";
         mysql_query($query);
         print "<h5>Status: URL successfully deleted!</h5>";
       }
       // HANDLE URL EDITING
       if ($_GET['edit']==1){
         $query = "UPDATE rotate SET url='".$_POST['url']."' WHERE id='".$_GET['editid']."' AND user_id='".$user_id."';";
         mysql_query($query);
         $query = "UPDATE rotate SET weight='".$_POST['weight']."' WHERE id='".$_GET['editid']."' AND user_id='".$user_id."';";
         mysql_query($query);
         print "<h5>Status: URL successfully edited!</h5>";
       }

        // ***********************************
        // Print out STATS
        // ***********************************


       print "<table cellspacing=0 cellpadding=0 width=80% border='1' bordercolor='".$bcolor."'>";
       print "<tr bgcolor='".$tcolor."'><td colspan=3><b><big>Statistics</big></b></td>";
       print "<tr bgcolor='".$tcolor."'><td><center><strong>URL</strong></center></td><td><center><strong>Date</strong></center></td><td><center><strong>Hits</strong></center></td></tr>";
       $query = "SELECT rotate.id, rotate.url, rotate.total_hits, rotate_clicks.date, rotate_clicks.count FROM rotate LEFT JOIN rotate_clicks ON rotate.id = rotate_clicks.url_id WHERE rotate.user_id='".$user_id."' ORDER BY rotate.id DESC;";
       $result = mysql_query($query);
       $row_id=-1;
       while($row = mysql_fetch_array($result)) {
                if ($row_id == -1)
                  {
                    $prev_url_id = $row["id"];
                  }
                if ($prev_url_id != $row['id'])
                {
                    print '<tr><td colspan="2"><p align="right"><font size="1">Total hits to this link since addition:&nbsp;&nbsp;</font></p></td><td><p align="right"><b><font size="1">'.$prev_total_hits.'</font></b></p></td></tr>';
                }
                $prev_url_id = $row["id"];
                $prev_total_hits = $row["total_hits"];

                // HANDLE ROW COLOR CHANGE
                      $row_id = $row_id + 1;
                if ($row_id % 2 == 0)
                   {
                      print "<tr bgcolor='".$lrcolor."'>";
                   }else{
                      print "<tr bgcolor='".$drcolor."'>";
                   }
                // PRINT OUT EXISTING URLs
                  print '<td><a href="'.$row['url'].'">'.$row['url'].'</a></td><td>'.$row["date"].'&nbsp;</td><td><p align="right">&nbsp;'.$row["count"].'</p></td></tr>';
         }
         print '<tr><td colspan="2"><p align="right"><font size="1">Total hits to this link since addition:&nbsp;&nbsp;</font></p></td><td><p align="right"><b><font size="1">'.$prev_total_hits.'</font></b></p></td></tr>';
         print "</table><br>";

       // ****************************************
       // URL MANAGER Part2
       // ****************************************

       print "<table cellspacing=0 cellpadding=0 width=80% border='1' bordercolor='".$bcolor."'>";
       print "<tr bgcolor='".$tcolor."'><td colspan=3><b><big>URL Manager</big></b></td>";
       print "<tr bgcolor='".$tcolor."'><td>URL</td><td>Weight</td><td>Actions</td></tr>";
       $result = mysql_query("select * from rotate WHERE user_id='".$user_id."';");
       $row_id=0;
       while($row = mysql_fetch_array($result)) {
                // HANDLE ROW COLOR CHANGE
                $row_id = $row_id + 1;
                if ($row_id % 2 == 0)
                   {
                      print "<tr bgcolor='".$lrcolor."'>";
                   }else{
                      print "<tr bgcolor='".$drcolor."'>";
                   }
                // PRINT OUT EXISTING URLs
                  print "<td><a href=".$row['url'].">".$row['url']."</a></td><td><b>".$row['weight']."</b></td><td><a href=member.php?del=".$row['id'].">Delete</a>&nbsp;|&nbsp;<a href=member.php?editid=".$row['id'].">Edit</a></td></tr>";
         }
        // ADDING URLs
        if (!isset($edit)){$edit=0;}
        if (isset($_GET['editid'])&&$edit!=1)
        {
        $query = "SELECT * FROM rotate WHERE id='".$_GET['editid']."' AND user_id='".$user_id."';";
        $result = mysql_fetch_row(mysql_query($query));
       print '<tr><td colspan=3>
        <b>Edit URL:</b><br>
        <table>
        <form action="member.php?editid='.$_GET['editid'].'&edit=1" method="post">
        <tr><td>URL: </td><td><input name="url" type="text" value="'.$result[1].'" size="70"><small><b>(max. 255 chars)</b></small></td></tr>
        <tr><td>Weight: </td><td><input name="weight" type="text"  value="'.$result[2].'"><small><b>(positive Integer value)</b></small></td></tr>
        <tr><td colspan=2><center><input type="submit" value="Edit">&nbsp;<input type="reset" value="Reset"></center></td></tr>
        </form>
        </table>
        ';
        }else{
        print '<tr><td colspan=3>
        <b>Add New URL:</b><br>
        <table>
        <form action="member.php?add=1" method="post">
        <tr><td>URL: </td><td><input name="url" type="text" value="'.$result[1].'" size="70"><small><b>(max. 255 chars)</b></small></td></tr>
        <tr><td>Weight: </td><td><input name="weight" type="text"  value="'.$result[2].'"><small><b>(positive Integer value)</b></small></td></tr>
        <tr><td colspan=2><center><input type="submit" value="Add">&nbsp;<input type="reset" value="Reset"></center></td></tr>
        </form>
        </table>';
        }

        print "</td></tr></table><a href='member.php?logout=1'>Log Out</a>";
        print $design_bottom;
}else{
print '<h3>Login</h3>';
print $member_login_box;
print $design_bottom;
}

?>
