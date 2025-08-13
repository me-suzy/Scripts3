<? 
$req_level = 2;
include "inc_t.php";
?>




<p><b><? echo $la_users; ?></b>
<? if ($level == 1) { print "<p><a href='user.php?new=1'>$la_new_user</a>";  } ?>
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5">
  <tr>
    <td valign="top">
    <?
     if ($submit)
     {

          if (!$fname_f OR !$lname_f OR !$email_db OR !$password_f)
          {
           $error = 1;
           print "<font color=red>$la_mand_error1</font>";
          }


          if ($error <> 1)
          {

              if (!$usern)
              {
               $sql = "INSERT INTO author_news (fname, lname,email,picture,password,about,level) values (
               '$fname_f',
               '$lname_f',
               '$email_db',
               '$picture_f',
               '$password_f',
               '$about_f',
               '$level_f'
               )";
              }
              else
              {
               $sql = "UPDATE author_news SET
               fname = '$fname_f',
               lname = '$lname_f',
               email = '$email_db',
               picture = '$picture_f',
               password = '$password_f',
               about = '$about_f',
               level = '$level_f'
               WHERE userid = '$usern'
               ";

              }

              //print $sql;
              $result = mysql_query($sql);




               if ($result)
               {
                print "<b>$la_user_saved</b><br>";
               }
          }
     }
	
     if ($userid AND $level <> 1)
     {
     	
     	$usern = $userid;	
     }
     
     if ($usern) 
     {
        $sql = "SELECT * FROM author_news WHERE userid = $usern";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $fname = $myrow["fname"];
              $lname = $myrow["lname"];
              $email_db = $myrow["email"];
              $picture = $myrow["picture"];
              $password = $myrow["password"];
              $about = $myrow["about"];
              $level_db = $myrow["level"];
        }
	  print "$la_now_editing $fname $lname";
     }

     if ($delete AND $level==1)
     {
		$sql = "SELECT count(id) FROM article_news where authorid=$delete";
		$result = mysql_query($sql);
		$myrow = mysql_fetch_array($result);
		$num_art = $myrow["count(id)"];
     	
		if (!$answer)
		{
				print "$la_this_author $num_art $la_this_author2";
				print " <b><a href='?delete=$delete&answer=yes'>$la_y</a></b><br>";	
		}
		
		if ($answer=='yes')
		{
     		$sql = "DELETE FROM author_news WHERE userid = $delete";
        	$result = mysql_query($sql);
        	print "$la_deleted_user<br>";
		}

     }
     elseif ($delete AND $level <> 1)
     {
     	  print "$la_notall<br>";
     }

    ?>
    </td>
  </tr>
</table>

<br>

<?
if ($usern OR $new)
{
?>	
<form method="post" action="user.php">
<input type="hidden" name="usern" value="<? echo $usern; ?>">
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5" class="articleBody">
  <tr>
    <td valign="top" bgcolor="#C0C0C0" colspan="2"><b><? echo $la_user_title ?></b></td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_firstname ?></td>
    <td align="right" valign="top"><input type="text" name="fname_f" size="25" value="<? echo $fname; ?>"></td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_lastname ?></td>
    <td align="right" valign="top"><input type="text" name="lname_f" size="25" value="<? echo $lname; ?>"></td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_email ?></td>
    <td align="right" valign="top"><input type="text" name="email_db" size="25" value="<? echo $email_db; ?>"></td>
  </tr>

  <tr>
    <td valign="top"><? echo $la_about ?></td>
    <td align="right" valign="top"><textarea rows="7" name="about_f" cols="19"><? echo $about; ?></textarea></td>
  </tr>

  <tr>
    <td valign="top"><? echo $la_accesslevel ?> [<a href="level.php"><? echo $la_levels ?></a>]</td>
    <td align="right" valign="top">
      
      <?
      if ($level == 1)
      {
      	print "";      	
      	print "<select size='1' name='level_f'>";
        print "<option selected>--- $la_accesslevel ---</option>";
        
        $sql = "SELECT * FROM level_news";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $levelname = $myrow["levelname"];
              $level_n = $myrow["level"];
              print "<option value=\"$level_n\"";
              if ($level_n == $level_db) { print "selected"; }
              print ">$levelname</option>";
        }
        print "</select>";
      }
      else
      {
      	$sql = "SELECT levelname FROM level_news where level = $level";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $levelname = $myrow["levelname"];
              print "<input type=hidden name=level_n value='$level'>$levelname";
        }
      }
	 ?>
	
	</td>
  </tr>

  <tr>
    <td valign="top"><? echo $la_passwd; ?></td>
    <td align="right" valign="top"><input type="text" name="password_f" size="25" value="<? echo $password; ?>"></td>
  </tr>


  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right" valign="top"><input type="submit" value="<? echo $la_save ?>" name="submit"></td>
  </tr>
</table>
</form>
<?
}
?>

<!-- -->

<?
if ($detail)
{
	$sql = "SELECT count(id) FROM article_news where authorid=$detail";
	$result = mysql_query($sql);
	$myrow = mysql_fetch_array($result);
	$num_art = $myrow["count(id)"];
              
    $sql = "SELECT sum(count) FROM article_news where authorid=$detail";
	$result = mysql_query($sql);
	$myrow = mysql_fetch_array($result);
	$num_count = $myrow["sum(count)"];
	
	$sql_2 = "SELECT sum(votes) FROM article_news where authorid=$detail";
	$result_2 = mysql_query($sql_2);
	$myrow = mysql_fetch_array($result_2);
	$sum_votes = $myrow["sum(votes)"];
	
	
	$sql = "SELECT sum(grade) FROM article_news where authorid=$detail";
	$result = mysql_query($sql);
	$myrow = mysql_fetch_array($result);
	$sum_grade = $myrow["sum(grade)"];
	
	if ($sum_votes)
	{
		$total_grade = round($sum_grade / $sum_votes,1);
	}
    print "<table border=1 cellpadding=2 width=400 bgcolor=#E5E5E5 bordercolorlight=#FFFFFF bordercolordark=#E5E5E5 class=articlebody>";
  	print "<tr>";
    print "<td valign=top colspan=2 bgcolor=#C0C0C0><b><font face=Arial size=2>$la_stat_for $na</font></b></td>";
  	print "</tr>";
  	print "<tr><td>$la_ant_art:</td><td>$num_art</td></tr>";
  	print "<tr><td>$la_read:</td><td>$num_count</td></tr>";
  	print "<tr><td>$la_grade</td><td>$total_grade (# $sum_votes)</td></tr>";
  	print "</table>";
}
?>


<!-- -->
<p>&nbsp;</p>
<table border="1" cellpadding="2" width="400" bgcolor="#E5E5E5" bordercolorlight="#FFFFFF" bordercolordark="#E5E5E5">
  <tr>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_name ?></b></td>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_accesslevel ?></b></td>
    <td valign="top" bgcolor="#C0C0C0"><b><? echo $la_change ?></b></td>
  </tr>
  <?
  	if ($level == 1)
  	{
	    $sql = "SELECT * FROM level_news, author_news where author_news.level = level_news.level";
  	}
  	else
  	{
  		$sql = "SELECT * FROM level_news, author_news where author_news.level = level_news.level AND author_news.userid=$userid";
  	}
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $levelname = $myrow["levelname"];
              $level_n = $myrow["level"];
              $fname = $myrow["fname"];
              $lname = $myrow["lname"];
              $usern= $myrow["userid"];
			  $na = "$fname $lname";
			  
              print "<tr>";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\"><a href=\"user.php?usern=$usern\">$fname $lname</a></font></td>";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\">$levelname ($level_n)</font></td> ";
              print "<td valign=\"top\"><font face=\"Arial\" size=\"2\"><a href=\"user.php?delete=$usern\">$la_del</a> | ";
              print "<a href=\"user.php?usern=$usern\">$la_change</a>";
              print "| <a href=\"?detail=$usern&na=$na\">$la_details</a></font></td>";

              print "</tr>";
        }

        ?>

</table>
<p>&nbsp;</p>

</body>

</html>
<? include "inc_b.php"; ?>