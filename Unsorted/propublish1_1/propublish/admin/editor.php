<? 
$req_level = 2;
include "inc_t.php";
$artid = $id;


print "<h3>$la_newseditor</h3><a href='list.php'>$la_back_news</a><p>";
?>
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5" class='articlebody'>
  <tr>
    <td valign="top">
    <?
     if ($submit)
     {



          if (!$catid_f OR !$title_f OR !$intro_f)
          {
           $error = 1;
           print "<font color=red>$la_mand_error1</font>";
          }


          if ($error <> 1)
          {
              $title_f = htmlspecialchars($title_f);
              
          	  if (!$id)
              {
               $datestamp = date("Ymd"); 
               $body_f = nl2br($body_f);
               $sql = "INSERT INTO article_news (catid, title,authorid,intro,body,date,status,validated) values (
               '$catid_f',
               '$title_f',
               '$authorid_f',
               '$intro_f',
               '$body_f',
               '$datestamp',
               '$status_f',
               '$set_autovalidate'
               )";
               
               if ($set_inform_new)
               {
					
					$sendto = "$set_email";
					$from = "$authorid_f";
					$subject = "$la_new_art_validate";
					$message = "$la_new_art_validate2 $title_f.";
					$headers = "From: $from\r\n"; 
					
					
					mail($sendto, $subject, $message, $headers);

               }
               
               
               
               
                          
               
               
               
               
              }
              else
              {
              $dchange = date("Ymd"); 
              $body_f = nl2br($body_f);
               $sql = "UPDATE article_news SET
               catid = '$catid_f',
               title = '$title_f',
               authorid = '$authorid_f',
               date_changed = '$dchange',
               intro = '$intro_f',
               body = '$body_f',
               status = '$status_f'
               WHERE id = '$id'
               ";

              }
	
              //print $sql;
              $result = mysql_query($sql);
	      
	      if (!$id)
	      {
	      	$id = mysql_insert_id();
	      	$artid = $id;
	      }



               if ($result)
               {
                print "<b>$la_saved</b><br>";
               }
          }
     }

     if ($id)
     {
        $sql = "SELECT * FROM article_news WHERE id = $id";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $catid = $myrow["catid"];
              $title = $myrow["title"];
              $authorid = $myrow["authorid"];
              $intro = $myrow["intro"];
              $body = $myrow["body"];
              $date = $myrow["date"];
              $status = $myrow["status"];
        }

     print "<b>$la_edit_now $title</b><br>";
     }

     

    ?></td>
  </tr>
</table>

<br>
<form method="post" action="editor.php">
<input type="hidden" name="userid" value="<? echo $userid; ?>">
<input type="hidden" name="id" value="<? echo $id; ?>">
<input type="hidden" name="artid" value="<? echo $id; ?>">
<input type="hidden" name="date" value="<? echo $date; ?>">


<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5" class='articlebody'>
  <tr>
	    <td valign="top" bgcolor="#C0C0C0" colspan="2"><b><? echo $la_newedit ?></b> <font size="1">[<a href="javascript:Start1('aprovedtags.php')";><b><? echo $la_tagsapproved ?></b></a>]</td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_cat ?></td>
    <td align="right" valign="top">
      <p align="left"><select style="BACKGROUND-COLOR: #e6e6e6" size="1" name="catid_f">

     <?
        $sql = "SELECT * FROM cat_news";
        $result = mysql_query($sql);
        $num_res = mysql_num_rows($result);

        for ($i=0; $i<$num_res; $i++)
        {
              $myrow = mysql_fetch_array($result);
              $catname = $myrow["catname"];
              $id = $myrow["id"];

              if ($catid == $id)
              {
               print "<option value=\"$id\" selected>$catname</option>";
              }
              else
              {
               print "<option value=\"$id\">$catname</option>";
              }

        }

        ?>
      </select></p>
    </td>
       
    
    
  </tr>
    <tr>
    <td valign="top"><? echo $la_author ?></td>
    <td align="left" valign="top">

      <?
		if ($level == 1)
		{
	        // If superadmin, let me select the writer
			print "<select style='BACKGROUND-COLOR: #e6e6e6' size='1' name='authorid_f'>";
	      	print "<option selected value=0>0</option>";
	     
	        $sql = "SELECT * FROM author_news";
	        $result = mysql_query($sql);
	        $num_res = mysql_num_rows($result);
	
	        for ($i=0; $i<$num_res; $i++)
	        {
	              $myrow = mysql_fetch_array($result);
	              $userid = $myrow["userid"];
	              $fname = $myrow["fname"];
	              $lname = $myrow["lname"];
	
	              if ($userid == $authorid)
	              {
	               print "<option value=\"$userid\" selected>$fname $lname</option>";
	              }
	              else
	              {
	               print "<option value=\"$userid\">$fname $lname</option>";
	              }
	        }
	        print "</select>";
		}
		else
		{
			print "<input type=hidden name=authorid_f value='$userid'>";
			print "<font face='Arial' size='2'>$name";
		}

        ?>
    </td>
  </tr>

  <tr>
    <td valign="top"><? echo $la_title ?></td>
    <td align="right" valign="top">
      <p align="left"><input type="text" name="title_f" size="47" value="<? echo $title ?>"></p>
    </td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_intro ?></td>
    <td align="right" valign="top"><textarea name="intro_f" cols="64" rows="2"><? echo $intro ?></textarea></td>
  </tr>
  <tr>
    <td valign="top"><? echo $la_content ?></td>
    
    <td align="right" valign="top">
    <?
    $body = nl2br($body);
    $body = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"", $body); 
    ?>
    <textarea name="body_f" rows="<? echo $text_h_plain ?>" cols="<? echo $text_w_plain ?>" wrap="virtual"><?print htmlspecialchars ($body);?></textarea></td>
  </tr>

  <tr>
    <td valign="top"><? echo $la_status; ?></td>
    <td valign="top">
      <p align="left"><select size="1" name="status_f">
        <?
         if ($status == 1)
         {
           print "<option selected value=\"1\" selected>$la_activated</option>";
           print "<option value=\"2\">$la_deactivated</option>";
         }
         elseif ($status == 2)
         {
          print "<option value=\"2\" selected>$la_deactivated</option>";
          print "<option value=\"1\">$la_activated</option>";
         }
         else
         {
          print "<option value=\"1\">$la_activated</option>";
          print "<option value=\"2\">$la_deactivated</option>";
         }
        ?>

      </select>
         
      &nbsp;&nbsp;&nbsp;
      <? echo $la_show_html ?></font>
      <input type="checkbox" name="show_html" value="1" <? if ($show_html == 1) { print "checked"; } ?>>
      
    </td>
  </tr>

  <tr>
    <td valign="top"><hr><? echo $la_pic ?><hr></td>
    <td align="left" valign="top">
      <p align="left">
      
  	<hr>    
    <?php
    
      
     if ($artid)
     {
       
      $query = "select * from newspicture_news where artikkelid = $artid";
      $result = MYSQL_QUERY($query);
     
      
      $num_res = mysql_num_rows($result);
  	
      print "$la_num_pic $num_res";
     
      ?>
      	    
      
      
      <a href="javascript:Start2('img.php?artid=<? echo $artid ?>&navn=<? echo $title_f ?>')"><b><? echo $la_pic_up ?></b></a>
      
     <?
     }
     else
     {
     	print "$la_rem_save";
     }
     ?>
     <hr>
      </td>	
  </tr>

  <tr>
    <td valign="top">&nbsp;</td>
    <td align="right" valign="top"><input type="submit" value="<? echo $la_save ?>" name="submit"></td>
  </tr>
</table>
</form>
<p>&nbsp;</p> 

</body>

</html>
<? include "inc_b.php"; ?>
