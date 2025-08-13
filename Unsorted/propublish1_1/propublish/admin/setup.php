<?
$demom =0;
//$override = 1;
$req_level = 1;
include "inc_t.php";
?><p>
<table border="1" cellpadding="3" cellspacing="0" width="100%" class="articlebody">
<tr>
<td bgcolor="lightgrey">
&nbsp; <? echo $la_config; ?>
</td>
</tr>

<tr bgcolor="white">
<td width="100%">


<?
if (!$file_name)
{
	print "<b>$la_config</b><br>";
	print "$la_change_config";
	
}	
?>
<?

######################################################
## set_inc.php	                                    ##
######################################################
 if ($submit)
 {
 print "<p><b>$la_saved_conf</b>"; 
 $string = "<? \r
 \$set_email=\"$set_email_f\";\r
 \$set_htmleditor=\"$set_htmleditor_f\";\r
 \$set_normaleditor=\"$set_normaleditor_f\";\r
 \$fp_limit=\"$fp_limit_f\";\r
 \$lang=\"$lang_f\";\r
 \$set_url=\"$set_url_f\";\r
 \$set_inform_new=\"$set_inform_new_f\";\r
 \$set_autovalidate=\"$set_autovalidate_f\";\r
 \$text_w_plain=\"$text_w_plain_f\";\r
 \$text_h_plain=\"$text_h_plain_f\";\r
 \$text_w_html=\"$text_w_html_f\";\r
 \$text_h_html=\"$text_h_html_f\";\r
 
?>";
 
 
 
 }

 // DEFAULT CREATE STATEMENT
if ($submit AND !$stop AND $demom <> 1)
{
 if ($string)
 {
  $file_pointer = fopen("set_inc.php", "w");
  fwrite($file_pointer,$string);
  fclose($file_pointer);
  
 }
}
 
 if (file_exists("set_inc.php"))
 {
     include("set_inc.php");
 }
 
 
 
 ?>
 <form method="post" action"set.php">
 <table width="500" class="articlebody">
  <tr>
 <td><b><? echo $la_language ?></b><br>
 
   <select name="lang_f">
  	<?
	$dir = opendir("language/");

 
 	while ($file = readdir($dir))
  	{
          if ($file <> "." AND $file <> "..")
          print "<option value='$file'";
          if ($file == $language)
          {
          	print " selected"; 
          }	
          
          print ">$file</option>";
  	}
  	closedir($dir);
  	?>
  </select><p>
  
 
 </td>
 </tr>
 
 
 <tr>
 <td><b><? echo $la_email_admin ?></b><br><? echo $la_email_admin1 ?><br>
 <input type="text" size="30" maxlength="150" name="set_email_f" value="<?php echo $set_email ?>"><p>
 </td>
 </tr>
 
  <tr>
 <td><b><? echo $la_inform ?></b><br><? echo $la_inform1 ?><br>
 <input type="checkbox" name="set_inform_new_f" value="1" <? if ($set_inform_new) { print "checked"; } ?>>
  <p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_validate ?></b><br><? echo $la_validate1 ?><br>
 <input type="checkbox" name="set_autovalidate_f" value="1" <? if ($set_autovalidate) { print "checked"; } ?>>
  <p>
 </td>
 </tr>
 

 
 
 <tr>
 <td><b>URL</b><br><? echo $la_url1 ?><br>
 <input type="text" size="30" maxlength="150" name="set_url_f" value="<?php echo $set_url ?>"><p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_html_editor ?></b><br><? echo $la_html_editor1 ?><br>
 <input type="checkbox" name="set_htmleditor_f" value="1" <? if ($set_htmleditor) { print "checked"; } ?>>
  <p>
 </td>
 </tr>

 <tr>
 <td><b><? echo $la_norm_editor ?></b><br><? echo $la_norm_editor1 ?><br>
 <input type="checkbox" name="set_normaleditor_f" value="1" <? if ($set_normaleditor) { print "checked"; } ?>>
  <p>
 </td>
 </tr>
 
  <tr>
 <td><b><? echo $la_news_frontpage ?></b><br><? echo $la_news_frontpage1 ?><br>
 <input type="text" size="2" maxlength="2" name="fp_limit_f" value="<?php echo $fp_limit ?>"><p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_edit_sh ?></b><br>
 <input type="text" size="2" maxlength="3" name="text_h_plain_f" value="<?php echo $text_h_plain ?>"><p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_edit_sw ?></b><br>
 <input type="text" size="2" maxlength="3" name="text_w_plain_f" value="<?php echo $text_w_plain ?>"><p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_edit_wh ?></b><br>
 <input type="text" size="2" maxlength="3" name="text_h_html_f" value="<?php echo $text_h_html ?>"><p>
 </td>
 </tr>
 
 <tr>
 <td><b><? echo $la_edit_ww ?></b><br>
 <input type="text" size="2" maxlength="3" name="text_w_html_f" value="<?php echo $text_w_html ?>"><p>
 </td>
 </tr>
 
 
 
  </table>
 <input type="submit" name="submit" value="<? echo $la_save ?>">
 </form>
   
         </td>
</tr>
</table>

<?




include "inc_b.php"; ?>

