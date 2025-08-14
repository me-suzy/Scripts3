<?

// *******************************************************************
// WGS-Rotate Script
// Done by Webguy Studios © 2003
// *******************************************************************

// *******************************************************************
// script part starts there, normally nothing should be changed below!
// *******************************************************************

include_once("config.inc.php");

// get user account type information and user id .. :)
$username = $_GET["u"];
$query = "SELECT id,account_type FROM rotate_usr WHERE login = '$username'";
$result = mysql_query($query);
$result = mysql_fetch_row($result);
$user_id = $result[0];
$mode = $result[1];

$query = "select id from rotate where user_id='".$user_id."';";
$res = mysql_query($query);
if (mysql_num_rows($res)!=0)
 {
  // update total stats for user:
  $query = "UPDATE rotate_usr SET total_hits = total_hits + 1 WHERE id='".$user_id."';";
  mysql_query($query);
  
  // Get total number of hits
  $query = "SELECT sum(hits) FROM rotate WHERE user_id='".$user_id."';";
  $total_hits = mysql_fetch_row(mysql_query($query));
  if ($total_hits[0]==0){$total_hits[0]=1;}

  // Get total weight of all links (otherwise you would have to deal with %
  $query = "SELECT sum(weight) FROM rotate WHERE user_id='".$user_id."';";
  $total_weight = mysql_fetch_row(mysql_query($query));


  // Select all links which have not exceeded their weight
  $query = "SELECT * FROM rotate WHERE (user_id='".$user_id."') AND (weight >= hits/".abs($total_hits[0])."*".$total_weight[0]." OR weight='-1');";
  $max_rnd = mysql_num_rows(mysql_query($query));
  $result = mysql_query($query);

  // Selecting random link from the ones that are shown less than their weight
  $rndval = rand (0, $max_rnd - 1);
     for ($i = 0; $i <= $max_rnd; $i++) {
        $site_result = mysql_fetch_row($result);
         if ($i==$rndval){$site = $site_result;}
     }

  // Add one hit to a link that was chosen
  if ($site[3] == -1){
     $query = "UPDATE rotate SET hits=hits+2 WHERE id='".$site[0]."' AND user_id='".$user_id."';";
     mysql_query($query);
     $query = "UPDATE rotate SET total_hits=total_hits + 1 WHERE id='".$site[0]."' AND user_id='".$user_id."';";
     mysql_query($query);
  }else{
     $query = "UPDATE rotate SET hits=hits+1 WHERE id='".$site[0]."' AND user_id='".$user_id."';";
     mysql_query($query);
     $query = "UPDATE rotate SET total_hits=total_hits + 1 WHERE id='".$site[0]."' AND user_id='".$user_id."';";
     mysql_query($query);
  }
  
  $query = "SELECT id FROM rotate_clicks WHERE url_id='".$site[0]."' AND date='".$datetoday."';";
  $reste = mysql_query($query);
  if (mysql_num_rows($reste)!=0)
  {
    $query = "UPDATE rotate_clicks SET count = count + 1 WHERE url_id='".$site[0]."' AND date='".$datetoday."';";
  }
  else
  {
    $query = "INSERT INTO rotate_clicks (user_id,url_id,date, count) VALUES ('".$user_id."','".$site[0]."','".$datetoday."','1');";
  }
  mysql_query($query);

// automatically clean up statistics, so that only last two days are on the stats.
  $query = "DELETE FROM rotate_clicks WHERE date < '".$yesterday."';";
  mysql_query($query);

// check what type of account member has .. and perform specific actions
  switch($mode)
  {
  case "Standard":
    print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
    <html>
    <head>
      <title>Your WGS-Rotate</title>
    </head>
      <frameset rows="32,*" framespacing="0" border="0" frameborder="0">
        <frame name="top" src="topad.htm" scrolling="no" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize">
        <frame name="bottom" src="'.$site[1].'" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize">
        <noframes>
          <p>Sorry, your browser does not support frames, please choose another browser to view this page!</p>
        </noframes>
      </frameset>
    </html>';
  break;

  case "Premium":
    header("Location: $site[1]");
  break;
  }
}
else
{
 print $no_urls_to_rotate;
}


?>
