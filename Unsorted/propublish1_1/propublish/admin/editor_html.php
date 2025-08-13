<? 
$req_level = 2;
include "inc_t.php";
$artid = $id;

?>
<!-- HTML editor-->
<form onSubmit="return SendForm()" name="htmlform" action="editor_html.php" method="post"> 

<input type="hidden" name="newsPost" value=""> 



<script language="JavaScript">
  var viewMode = 1; // WYSIWYG

  function Init()
  {
    iView.document.designMode = 'On';
  
  }
  
  function selOn(ctrl)
  {
	ctrl.style.borderColor = '#000000';
	ctrl.style.backgroundColor = '#B5BED6';
	ctrl.style.cursor = 'hand';	
  }
  
  function selOff(ctrl)
  {
	ctrl.style.borderColor = '#D6D3CE';  
	ctrl.style.backgroundColor = '#D6D3CE';
  }
  
  function selDown(ctrl)
  {
	ctrl.style.backgroundColor = '#8492B5';
  }
  
  function selUp(ctrl)
  {
    ctrl.style.backgroundColor = '#B5BED6';
  }
    
  function doBold()
  {
	iView.document.execCommand('bold', false, null);
  }

  function doItalic()
  {
	iView.document.execCommand('italic', false, null);
  }

  function doUnderline()
  {
	iView.document.execCommand('underline', false, null);
  }
  
  function doLeft()
  {
    iView.document.execCommand('justifyleft', false, null);
  }

  function doCenter()
  {
    iView.document.execCommand('justifycenter', false, null);
  }

  function doRight()
  {
    iView.document.execCommand('justifyright', false, null);
  }

  function doOrdList()
  {
    iView.document.execCommand('insertorderedlist', false, null);
  }

  function doBulList()
  {
    iView.document.execCommand('insertunorderedlist', false, null);
  }
  
   function doSave()
  {
    iView.document.execCommand('SaveAs', false, null);
  }
  
  function doForeCol()
  {
    var fCol = prompt('Skriv inn fargekoden for forgrunnen', '');
    
    if(fCol != null)
      iView.document.execCommand('forecolor', false, fCol);
  }

  function doBackCol()
  {
    var bCol = prompt('Skriv inn fargekoden for bakgrunnen', '');
    
    if(bCol != null)
      iView.document.execCommand('backcolor', false, bCol);
  }

  function doLink()
  {
    iView.document.execCommand('createlink');
  }
  
 
  function doRule()
  {
    iView.document.execCommand('inserthorizontalrule', false, null);
  }
  
  function doFont(fName)
  {
    if(fName != '')
      iView.document.execCommand('fontname', false, fName);
  }
  
  function doSize(fSize)
  {
    if(fSize != '')
      iView.document.execCommand('fontsize', false, fSize);
  }
  
  function doHead(hType)
  {
    if(hType != '')
    {
      iView.document.execCommand('formatblock', false, hType);  
      doFont(selFont.options[selFont.selectedIndex].value);
    }
  }
  
  
  
function SendForm() 
{ 
	var htmlCode = iView.document.body.innerHTML; 
	document.htmlform.newsPost.value = htmlCode; 
	return true; 
} 
</script>
<style>

  .butClass
  {    
    border: 1px solid;
    border-color: #D6D3CE;
  }
  
  .tdClass
  {
    padding-left: 3px;
    padding-top:3px;
  }

</style>


<body onLoad="Init()">
<?
print "<h3>$la_newseditor</h3><a href='list.php'>$la_back_news</a><p>";
?>
<table border="0" cellpadding="2" width="400" bgcolor="#E5E5E5" class='articlebody'>
  <tr>
    <td valign="top">
    <?
     if ($submit)
     {
		
		  $body_f = $newsPost;
		

          if (!$catid_f OR !$title_f OR !$intro_f)
          {
           $error = 1;
           print "<font color=red>$la_mand_error1</font>";
          }


          if ($error <> 1)
          {

              

              
              if (!$id)
              {
           
               $datestamp = date("Ymd"); 
               $body_f = nl2br($body_f);
               $sql = "INSERT INTO article_news (catid, title,authorid,intro,body,date,status,show_html,validated) values (
               '$catid_f',
               '$title_f',
               '$authorid_f',
               '$intro_f',
               '$body_f',
               '$datestamp',
               '$status_f',
               '$show_html',
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
               status = '$status_f',
               show_html = '$show_html'
               
               WHERE id = '$id'
               ";

              }
	

              $result = mysql_query($sql);
	      	
	      if (!$id)
	      {
	      	
	      	$id = mysql_insert_id();
	      
	      	$artid = $id;
	      	$aid = $id;
	      	
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
              if (!$aid)
              {
              	$aid = $myrow["id"];
              }
              $catid = $myrow["catid"];
              $title = $myrow["title"];
              $authorid = $myrow["authorid"];
              $intro = $myrow["intro"];
              $body = $myrow["body"];
              $date = $myrow["date"];
              $status = $myrow["status"];
              $show_html = $myrow["show_html"];
              $newsPost = $body;
        }

     print "<b>$la_edit_now $title</b><br>";
    

     }

     

    ?></td>
  </tr>
</table>

<br>

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
			print "<font face='Arial' size='2'>$name</font>";
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
    //$body = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"", $body); 
    ?>
    
    <!-- HTML editor start -->
    <table id="tblCtrls" width="534" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE" class='articlebody'>	
	<tr>
		<td class="tdClass">
			<img alt="Bold" class="butClass" src="images/bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBold()">
			<img alt="Italic" class="butClass" src="images/italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doItalic()">
			<img alt="Underline" class="butClass" src="images/underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doUnderline()">
			
			<img alt="Left" class="butClass" src="images/left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLeft()">
			<img alt="Center" class="butClass" src="images/center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCenter()">
			<img alt="Right" class="butClass" src="images/right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRight()">
						
			<img alt="LI" class="butClass" src="images/ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOrdList()">
			<img alt="UL" class="butClass" src="images/bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBulList()">
			
			<img alt="COLOR" class="butClass" src="images/forecol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol()">
			<img alt="BG" class="butClass" src="images/bgcol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol()">
			
			<img alt="LINK" class="butClass" src="images/link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLink()">
			
			<img alt="HR" class="butClass" src="images/rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRule()">
			<!-- <img alt="Lag lokal backup" class="butClass" src="images/backup.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doSave()"> -->			
		</td>
	</tr>
	</table>
	<iframe id="iView" style="width: <? echo $text_w_html ?>; height:<? echo $text_h_html ?>px" <? if ($aid) { print "src='load.php?id=$aid'"; } ?>></iframe>

    <table width="534" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE" class='articlebody'>	
    <tr>
		<td class="tdClass" colspan="1" width="80%">
		  
		<select name="selSize" onChange="doSize(this.options[this.selectedIndex].value)">
		    <option value="">-- <? echo $la_size; ?> --</option>
		    <option value="1"><? echo $la_size1 ?></option>
		    <option value="2"><? echo $la_size2 ?></option>
		    <option value="3"><? echo $la_size3 ?></option>
		    <option value="4"><? echo $la_size4 ?></option>
		    <option value="5"><? echo $la_size5 ?></option>
		    <option value="6"><? echo $la_size6 ?></option>
		  </select>
		
		
		<select name="selFont" onChange="doFont(this.options[this.selectedIndex].value)">
		    <option value="">-- Font --</option>
		    <option value="Arial">Arial</option>
		    <option value="Courier">Courier</option>
		    <option value="Sans Serif">Sans Serif</option>
		    <option value="Tahoma">Tahoma</option>
		    <option value="Verdana">Verdana</option>
		    <option value="Wingdings">Wingdings</option>
		  </select>
		  

		</td>
		<td class="tdClass" colspan="1" width="20%" align="right">
		  
		</td>
    </tr>
    </table>
    <!-- html editor end -->
    
    
    </td>
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
    
       require("db.php");
      
     if ($artid)
     {
       
      $query = "select * from newspicture_news where artikkelid = $artid";
      $result = MYSQL_QUERY($query);
     
      
      $num_res = mysql_num_rows($result);
  	
            print "$la_num_pic $num_res";
     
      ?>
      	 <!-- <a href="javascript:Start('hjelp.php#stilling')";></a> -->
   
      
      
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
<? 


include "inc_b.php"; ?>
