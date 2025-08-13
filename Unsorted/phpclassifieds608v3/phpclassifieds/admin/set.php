<?
$demom =0;
$override = 1;
require("admheader.php");

?>
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td bgcolor="lightgrey">
 &nbsp; Settings 
</td>
</tr>

<tr bgcolor="white">
<td width="100%">


<p> <a href="set.php?file_name=config/general.inc.php">General
setup</a> | <a href="set.php?file_name=config/options.inc.php">Options</a> |
<a href="set.php?file_name=config/globalad.inc.php">Global ad fields</a>
| <a href="set.php?file_name=config/globaluser.inc.php">Global user fields</a>
| <a href="set.php?file_name=config/mail.inc.php">Mail</a>
| <a href="set.php?file_name=config/headerfooter.inc.php">Header/footer</a>
| <a href="set.php?file_name=config/credits.inc.php">Credits</a>
| <a href="set.php?file_name=phpconfig">PHP Config</a>
 </p>

<?
if (!$file_name)
{
	print " <b>System settings</b><br />";
	print "From here, you can change how PHP Classifieds works in many aspects. ";	
	print "Just choose from the submenu above, and you are on your way. ";
}	



?>
<?
######################################################
## GENERAL.INC.PHP                                  ##
######################################################

if ($file_name == "config/general.inc.php")
{
 if ($submit)
 {

  if (!$language)
  {
   $language = "eng.php";
  }

  if (!$full_path_to_public_program)
  {
        print " Error <br />Full path must be set, file not saved !<p />";
        $stop = 1;
  }

  if (!$cat_tbl or !$ads_tbl or !$usr_tbl or !$pic_tbl)
  {
        print " Error <br />Tablenames must be filled out, file not saved !<p />";
        $stop = 1;
  }




  $string = "<? \r
 \$from_adress_mail = \"$from_adress_mail\";
 \$name_of_site=\"$name_of_site\";\r
 \$li=\"$li\";\r
 \$version=\"6.08\";\r
 \$language=\"$language\";\r
 \$url=\"$url\";\r
 \$full_path_to_public_program = \"$full_path_to_public_program\";\r
 \$from_adress=\"$from_adress_mail\";\r
 \$from_adress_mail=\"$from_adress_mail\";\r 
 \$cat_tbl=\"$cat_tbl\";\r
 \$ads_tbl=\"$ads_tbl\";\r
 \$usr_tbl=\"$usr_tbl\";\r
 \$pic_tbl=\"$pic_tbl\";\r
 \$ref1=\"$ref1\";\r
 \$ref2=\"$ref2\";\r
 require(\"$full_path_to_public_program/language/$language\");
?>";
 }

 if (file_exists($file_name))
 {
     include($file_name);
 }

 if (!$submit)
 {

   if (file_exists($file_name))
   {
     include($file_name);
   }
 ?>

  <!-- GENERAL.INC.PHP -->
  <p />

  <!-- DESCRIPTION -->
   <b>General Setup</b><br />
  From this panel, all setup that is needed to start with, and that are completely needed to start up.
   
  <!-- / DESCRIPTION -->

  <form method="post" action="set.php">
  <input type="hidden" name="setup" value="<?echo $setup ?>" />
   <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
  <table width="100%">
  <tr><td> <b>Language</b><br />Here you set your languageguage file. Default is eng for english, but
  many more languageguages is availble. This languageguage must exists as a languageguage.php file on your server in the /languageguage dir. <br />
  <?
  if ($setup)
  {
       $dir = opendir("language/");
  }
  else
  {
       $dir = opendir("../language/");
  }
  ?>
  <select name="language">
  <?

  //print "<option value='$language' selected>$language";
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
  </select><p />
  </td>
  </tr>

  <tr>
  <td> <b>Name of you site</b><br />This is the name that will be shown on all pages. Normally your sitetitle. <br />
  <input type="text" size="50" maxlength="140" name="name_of_site" value="<?php echo $name_of_site ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>URL</b><br />This is the adress to the classifieds main directory. Like: <i>www.a4.no/phpclassifieds/.</i> <br />
  <input type="text" size="50" maxlength="140" name="url" value="<?php echo $url ?>" /><p />
  </td>
  </tr>
  
  <tr>
  <td><b>Licence number</b><br />When you buy the licence to this program, you get a licence number. To check if this is valid,
  you can access the licence link in the admin area. Commercial/income or profit use requires paid licence. <br />
  <input type="text" size="50" maxlength="140" name="li" value="<?php echo $li ?>" /><p />
  </td>
  </tr>
  
    <tr>
  <td><b>Referer nuber 1</b><br />In order to avoid abuse, please type in the allowed referer here, like deltascripts.com in our case. 
  Only users accessing the Send mail page from your domain are then allowed. <br />
  <? 
  
  
  if ($ref1 == "" OR !$ref1) 
  {
		$temp = explode("/",getenv("HTTP_REFERER"));
		$ref1= $temp[2];
  		
		if (!eregi ("www", $ref1)) 
		{
  			$ref1 = "www." . $ref1;
		}
  } 
  ?>
  <input type="text" size="50" maxlength="140" name="ref1" value="<?php echo $ref1 ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>Referers number2</b><br />This is the second domain you allow use from, add www in front of your domain for instance. 
  Only users accessing the Send mail page from your domain are then allowed. <br />
  <? 
  if (!$ref2) 
  {
		$temp = explode("/",getenv("HTTP_REFERER"));
		$ref2= $temp[2];
  		
		
		$ref2 = ereg_replace("www.", "", $ref2);
  } 
  ?>
  <input type="text" size="50" maxlength="140" name="ref2" value="<?php echo $ref2 ?>" /><p />
  </td>
  </tr>

  
  
  

  <tr>
  <td><b>Full path</b><br />The program needs to know the full path to the phpclassifieds main dir. Below is the scripts suggestion, you might change the <i>classifieds</i> to something else.
  NOTE: This is one of the most important settings in the program. If this is set wrong, you will get errors over
  the hole program. Normally, the path is like /home/username/public_html/classifieds. The default path is set below, and <b>should</b> be the correct path !
  Give the path without trailing slash (/). <br />
  <input type="text" size="50" maxlength="140" name="full_path_to_public_program" value="<?php

  if (!$curdir)
  {
        $curdir = dirname("$SCRIPT_FILENAME");
  }

  if (!$full_path_to_public_program)
  {
        echo $curdir;
  }
  else
  {
        echo $full_path_to_public_program;
  }
  ?>">
  <p />
  </td>
  </tr>

  <tr>
  <td><b>Webmaster adress</b><br />The program often uses your adress as the sender when sending newsletter, but also. when notifying about new ads. <br />
  <input type="text" size="50" maxlength="50" name="from_adress_mail" value="<?php echo $from_adress_mail ?>" /><p />
  </td>
  </tr>



  <tr>
  <td><b>Category table</b><br />MySql field where PHP Classifieds will store its categories. <br />
  <input type="text" size="10" maxlength="10" name="cat_tbl" value="<?php echo $cat_tbl ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>Ads table</b><br />Ad table where all your ads is saved. <br />
  <input type="text" size="10" maxlength="10" name="ads_tbl" value="<?php echo $ads_tbl ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>User table</b><br />MySql field where PHP Classifieds will store its users. <br />
  <input type="text" size="10" maxlength="10" name="usr_tbl" value="<?php echo $usr_tbl ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>Picture table</b><br />MySql field where PHP Classifieds will store its images (if choosen). <br />
  <input type="text" size="10" maxlength="10" name="pic_tbl" value="<?php echo $pic_tbl ?>" /><p />
  </td>
  </tr>


  </table>
  <input type="submit" name="submit" value="Save">
  </form>
  </table>
 <?
}
}



######################################################
## GLOBALAD.INC.PHP                                 ##
######################################################
if ($file_name == "config/globalad.inc.php")
{
 if ($submit)
 {
  $string = "<? \r
 \$custom_field_1_text=\"$custom_field_1_text\";\r
 \$custom_field_2_text=\"$custom_field_2_text\";\r
 \$custom_field_3_text=\"$custom_field_3_text\";\r
 \$custom_field_4_text=\"$custom_field_4_text\";\r
 \$custom_field_5_text=\"$custom_field_5_text\";\r
 \$custom_field_6_text=\"$custom_field_6_text\";\r
 \$custom_field_7_text=\"$custom_field_7_text\";\r
 \$custom_field_8_text=\"$custom_field_8_text\";\r
?>";
 }

 if (file_exists($file_name))
 {
     include($file_name);
 }
 if (!$submit)
 {
 ?>
   <!-- DESCRIPTION -->
   <b>Custom ad fields</b><br />
  NOTE: This is the global ad fields only. If you want to generate "field template", unlimited different
  fields for different categories, you should visit the <a href="extra.php">template field</a> editor. 
  <p />These global fields will be shown on all ads, no matter what category the user posts to.
   
  <!-- / DESCRIPTION -->



 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
    <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
 <table width="100%">
 <tr>
 <td><b>Custom field #1</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_1_text" value="<?php echo $custom_field_1_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #2</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_2_text" value="<?php echo $custom_field_2_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #3</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_3_text" value="<?php echo $custom_field_3_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #4</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_4_text" value="<?php echo $custom_field_4_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #5</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_5_text" value="<?php echo $custom_field_5_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #6</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_6_text" value="<?php echo $custom_field_6_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #7</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_7_text" value="<?php echo $custom_field_7_text ?>" /><p />
 </td>
 </tr>

 <tr>
 <td><b>Custom field #8</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
 <input type="text" size="15" maxlength="15" name="custom_field_8_text" value="<?php echo $custom_field_8_text ?>" /><p />
 </td>
 </tr>

 </table>
 <input type="submit" name="submit" value="Save">
 </form>
 <?
}
}








######################################################
## GLOBALUSER.INC.PHP                               ##
######################################################
if ($file_name == "config/globaluser.inc.php")
{
 if ($submit)
 {
  $string = "<? \r
 \$usr_1_text=\"$usr_1_text\";\r
 \$usr_1_type=\"$usr_1_type\";\r
 \$usr_1_length=\"$usr_1_length\";\r  
 \$usr_1_filename=\"$usr_1_filename\";\r  
 \$usr_1_mandatory=\"$usr_1_mandatory\";\r    
 \$usr_1_link=\"$usr_1_link\";\r
  
 \$usr_2_text=\"$usr_2_text\";\r
 \$usr_2_type=\"$usr_2_type\";\r
 \$usr_2_length=\"$usr_2_length\";\r  
 \$usr_2_filename=\"$usr_2_filename\";\r  
 \$usr_2_mandatory=\"$usr_2_mandatory\";\r    
 \$usr_2_link=\"$usr_2_link\";\r
    
  \$usr_3_text=\"$usr_3_text\";\r
 \$usr_3_type=\"$usr_3_type\";\r
 \$usr_3_length=\"$usr_3_length\";\r  
 \$usr_3_filename=\"$usr_3_filename\";\r  
 \$usr_3_mandatory=\"$usr_3_mandatory\";\r    
  \$usr_3_link=\"$usr_3_link\";\r
   
  \$usr_4_text=\"$usr_4_text\";\r
 \$usr_4_type=\"$usr_4_type\";\r
 \$usr_4_length=\"$usr_4_length\";\r  
 \$usr_4_filename=\"$usr_4_filename\";\r  
 \$usr_4_mandatory=\"$usr_4_mandatory\";\r    
   \$usr_4_link=\"$usr_4_link\";\r
  
  \$usr_5_text=\"$usr_5_text\";\r
 \$usr_5_type=\"$usr_5_type\";\r
 \$usr_5_length=\"$usr_5_length\";\r  
 \$usr_5_filename=\"$usr_5_filename\";\r  
 \$usr_5_mandatory=\"$usr_5_mandatory\";\r     
  \$usr_5_link=\"$usr_5_link\";\r
?>";
 }

 if (file_exists($file_name))
 {
     include($file_name);
 }
 if (!$submit)
 {
 ?>

 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
    <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
<b>CUSTOM USERREGISTRATION FIELDS</b><br /><br />
Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php, and change.php (useradmin area) in addition, so that the user can change his personal information later.
<p />
<i>Question</i><br />
Will be shown on register.php, detail.php and change.php. Example could be a field called Price.<br /><br />
<i>Formtype</i><br />
Select what form element the user shall se. If you choose dropdown menu, checkbox or option boxes, then you will need
to tell the program where the .txt file with the options is put on the server. That is where the Filename come in.<br /><br />
<i>Length</i><br />
How long should the textfields and textarea boxes be ? 25 is a normal value for this.<br /><br />
<i>Filename</i><br />
Make a simple .txt file with the different countries, colors or whatever...Seperate with new lines. Place the .txt file
in the /admin/option directory (you will se some example .txt files).<br /><br />
<i>Mandatory</i><br />
New: It is now implemented!<br />

 
<p /> <table border=0 width="70%">
<tr>
	<td colspan="5"><b>User-reg custom field #1</b> </td>
</tr>
  
<tr>
	<td>Question </td><td>Formtype </td><td>Length </td><td>Filename </td><td>Mandatory </td><td>Field is URL?</td>
</tr>
<tr>
	<td><input type="text" size="15" maxlength="15" name="usr_1_text" value="<?php echo $usr_1_text ?>"></td>
  	<td><select name='usr_1_type'><option>Text</option><option <? if ($usr_1_type=='Checkbox') {print "selected";} ?>>Checkbox</option><option <? if ($usr_1_type=='Option') {print "selected";} ?>>Option</option><option <? if ($usr_1_type=='Dropdown') {print "selected";} ?>>Dropdown</option><option <? if ($usr_1_type=='Textarea') {print "selected";} ?>>Textarea</option></select></td>
  	<td><input type="text" size="2" maxlength="2" name="usr_1_length" value="<?php echo $usr_1_length ?>"></td>
  	<td><input type="text" size="15" maxlength="15" name="usr_1_filename" value="<?php echo $usr_1_filename ?>"></td>
  	<td><input type='checkbox' name='usr_1_mandatory' <? if ($usr_1_mandatory) {print "checked";} ?>></td>
  	<td><input type='checkbox' name='usr_1_link' <? if ($usr_1_link) {print "checked";} ?>></td>
</tr>


<tr>
	<td colspan="5"><b>User-reg custom field #2</b> </td>
</tr>
  
<tr>
	<td>Question </td><td>Formtype </td><td>Length </td><td>Filename </td><td>Mandatory </td>
</tr>
<tr>
	<td><input type="text" size="15" maxlength="15" name="usr_2_text" value="<?php echo $usr_2_text ?>"></td>
  	<td><select name='usr_2_type'><option>Text</option><option <? if ($usr_2_type=='Checkbox') {print "selected";} ?>>Checkbox</option><option <? if ($usr_2_type=='Option') {print "selected";} ?>>Option</option><option <? if ($usr_2_type=='Dropdown') {print "selected";} ?>>Dropdown</option><option <? if ($usr_2_type=='Textarea') {print "selected";} ?>>Textarea</option></select></td>
  	<td><input type="text" size="2" maxlength="2" name="usr_2_length" value="<?php echo $usr_2_length ?>"></td>
  	<td><input type="text" size="15" maxlength="15" name="usr_2_filename" value="<?php echo $usr_2_filename ?>"></td>
  	<td><input type='checkbox' name='usr_2_mandatory' <? if ($usr_2_mandatory) {print "checked";} ?>></td>
  	<td><input type='checkbox' name='usr_2_link' <? if ($usr_2_link) {print "checked";} ?>></td>
</tr>

<tr>
	<td colspan="5"><b>User-reg custom field #3</b> </td>
</tr>
  
<tr>
	<td>Question </td><td>Formtype </td><td>Length </td><td>Filename </td><td>Mandatory </td>
</tr>
<tr>
	<td><input type="text" size="15" maxlength="15" name="usr_3_text" value="<?php echo $usr_3_text ?>"></td>
  	<td><select name='usr_3_type'><option>Text</option><option <? if ($usr_3_type=='Checkbox') {print "selected";} ?>>Checkbox</option><option <? if ($usr_3_type=='Option') {print "selected";} ?>>Option</option><option <? if ($usr_3_type=='Dropdown') {print "selected";} ?>>Dropdown</option><option <? if ($usr_3_type=='Textarea') {print "selected";} ?>>Textarea</option></select></td>
  	<td><input type="text" size="2" maxlength="2" name="usr_3_length" value="<?php echo $usr_3_length ?>"></td>
  	<td><input type="text" size="15" maxlength="15" name="usr_3_filename" value="<?php echo $usr_3_filename ?>"></td>
  	<td><input type='checkbox' name='usr_3_mandatory' <? if ($usr_3_mandatory) {print "checked";} ?>></td>
  	<td><input type='checkbox' name='usr_3_link' <? if ($usr_3_link) {print "checked";} ?>></td>
</tr>

<tr>
	<td colspan="5"><b>User-reg custom field #4</b> </td>
</tr>
  
<tr>
	<td>Question </td><td>Formtype </td><td>Length </td><td>Filename </td><td>Mandatory </td>
</tr>
<tr>
	<td><input type="text" size="15" maxlength="15" name="usr_4_text" value="<?php echo $usr_4_text ?>"></td>
  	<td><select name='usr_4_type'><option>Text</option><option <? if ($usr_1_type=='Checkbox') {print "selected";} ?>>Checkbox</option><option <? if ($usr_4_type=='Option') {print "selected";} ?>>Option</option><option <? if ($usr_4_type=='Dropdown') {print "selected";} ?>>Dropdown</option><option <? if ($usr_4_type=='Textarea') {print "selected";} ?>>Textarea</option></select></td>
  	<td><input type="text" size="2" maxlength="2" name="usr_4_length" value="<?php echo $usr_4_length ?>"></td>
  	<td><input type="text" size="15" maxlength="15" name="usr_4_filename" value="<?php echo $usr_4_filename ?>"></td>
  	<td><input type='checkbox' name='usr_4_mandatory' <? if ($usr_4_mandatory) {print "checked";} ?>></td>
  	<td><input type='checkbox' name='usr_4_link' <? if ($usr_4_link) {print "checked";} ?>></td>
</tr>

<tr>
	<td colspan="5"><b>User-reg custom field #5</b> </td>
</tr>
  
<tr>
	<td>Question </td><td>Formtype </td><td>Length </td><td>Filename </td><td>Mandatory </td>
</tr>
<tr>
	<td><input type="text" size="15" maxlength="15" name="usr_5_text" value="<?php echo $usr_5_text ?>"></td>
  	<td><select name='usr_5_type'><option>Text</option><option <? if ($usr_5_type=='Checkbox') {print "selected";} ?>>Checkbox</option><option <? if ($usr_5_type=='Option') {print "selected";} ?>>Option</option><option <? if ($usr_5_type=='Dropdown') {print "selected";} ?>>Dropdown</option><option <? if ($usr_5_type=='Textarea') {print "selected";} ?>>Textarea</option></select></td>
  	<td><input type="text" size="2" maxlength="2" name="usr_5_length" value="<?php echo $usr_5_length ?>"></td>
  	<td><input type="text" size="15" maxlength="15" name="usr_5_filename" value="<?php echo $usr_5_filename ?>"></td>
  	<td><input type='checkbox' name='usr_5_mandatory' <? if ($usr_5_mandatory) {print "checked";} ?>></td>
  	<td><input type='checkbox' name='usr_5_link' <? if ($usr_5_link) {print "checked";} ?>></td>
</tr>




 </table>
 <input type="submit" name="submit" value="Save">
 </form>
 <?
}
}













######################################################
## HEADER.INC.PHP & FOOTER.INC.PHP                  ##
######################################################
if ($file_name == "config/headerfooter.inc.php")
{


 if ($submit)
 {
  $string1 = $header_file;
  $string2 = $footer_file;
 }


 if (!$submit)
 {

 ?>

 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
    <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
 <table width="100%">
  <tr>
  <td><b>Header</b><br />This header will apear on all pages. html is allowed. <br />
  <textarea rows="5" name="header_file" cols="70"><?
     $fcontents = file ("config/header.inc.php");
while (list ($line_num, $line) = each ($fcontents)) {
    echo htmlspecialchars ($line);
}
  ?>
  </textarea><p />
  </td>
  </tr>

  <tr>
  <td><b>Footer</b><br />This footer will apear on all pages. html is allowed.. <br />
  <textarea rows="5" name="footer_file" cols="70"><?
     $fcontents = file ("config/footer.inc.php");
while (list ($line_num, $line) = each ($fcontents)) {
    echo htmlspecialchars ($line);
}
  ?>
  </textarea><p />
  </td>

 </table>
 <input type="submit" name="submit" value="Save">
 </form>
 <?
}
}









######################################################
## OPTION.INC.PHP                                   ##
######################################################
if ($file_name == "config/options.inc.php")
{
 if ($submit)
 {

  if (!$categories_per_column)
  {
        print " Error <br />Categories per column not filled out, file not saved !<p />";
        $stop = 1;
  }

  if (!$delete_after_x_days OR $delete_after_x_days < 15)
  {
        print " Error <br />Delete after x days is equal to zero OR under 15 days. This will
        wipe out the entire database when set to 0, or at the 15 day set to 15. No settings was saved !<p />";
        $stop = 1;
  }


  // Default values

  if (!$number_of_ads_per_page)
  {
   $number_of_ads_per_page = 20;
  }

  if (!$categories_per_column)
  {
   $categories_per_column = 4;
  }

  if (!$xy)
  {
   $xy = "y";
  }

  if (!$xy)
  {
   $xy = "y";
  }

  $string = "<? \r
 \$advanced_delete_activated=\"$advanced_delete_activated\";\r
 \$auto=\"$auto\";\r
 \$delete_members_activated=\"$delete_members_activated\";\r
 \$categories_per_column=\"$categories_per_column\";\r
 \$delete_after_x_days=\"$delete_after_x_days\";\r
 \$expire_days_option=\"$expire_days_option\";\r 
 \$latest=\"$latest\";\r
 \$picture_upload_enable=\"$picture_upload_enable\";\r
 \$fileimg_upload=\"$fileimg_upload\";\r
 \$piclimit=\"$piclimit\";\r
 \$number_of_ads_per_page=\"$number_of_ads_per_page\";\r
 \$email_activated=\"$email_activated\";\r
 \$email_val=\"$email_val\";\r
 \$special_mode=\"$special_mode\";\r
 \$xy=\"$xy_c\";\r
 \$validation=\"$validation\";\r
 \$approve_mem=\"$approve_mem\";\r
 \$member_ab=\"$member_ab\";\r
 \$imagew=\"$imagew\";\r
 \$imageh=\"$imageh\";\r
 \$maxSize=\"$maxSize\";\r
 \$maxSize_gallery=\"$maxSize_gallery\";\r
 \$maxSize_large=\"$maxSize_large\";\r
 \$max_links=\"$max_links\";\r 
 \$list_img=\"$list_img\";\r 
 \$list_img_fp=\"$list_img_fp\";\r 
 \$nl2br=\"$nl2br\";\r
 \$credits_option=\"$credits_option\";\r
 \$admin_new_ad_subject=\"$admin_new_ad_subject\";\r
 \$admin_new_ad=\"$admin_new_ad\";\r  
 \$no_webmastermail=\"$no_webmastermail\";\r  
 \$opt_verify=\"$opt_verify\";\r
 \$bans = \"$bans\";\r
 \$maxpic = \"$maxpic\";\r
 \$bad_words = \"$bad_words\";\r
 \$magic= \"$magic\";\r 
 \$magic_path = \"$magic_path\";\r 
 \$magic_large_size = \"$magic_large_size\";\r 
 \$magic_large_q= \"$magic_large_q\";\r 
 \$magic_small_size = \"$magic_small_size\";\r 
 \$magic_small_q= \"$magic_small_q\";\r
  ?>";
 }
 
 if (file_exists($file_name))
 {
     include($file_name);
 }
 if (!$submit)
 {
 ?>

 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
 <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
 <table width="100%">
  <tr>
  <td>
  <b>Autoupdate</b><br />Autoupdate update the number of ads-counter on the main categories, and also delete ads and users. Note that if you have many ads/users, your should NOT activate this, and rather run the update script at regulary intervall. <br />
  <input type="checkbox" name="auto" value="1" <? if ($auto == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  <tr>
  <td><b>Advanced delete activated?</b><br />Will delete ads after a set time <br />
  <input type="checkbox" name="advanced_delete_activated" value="1" <? if ($advanced_delete_activated == 1) { print "checked"; } ?>><p />
  </td>
  </tr>


  <tr>
  <td><b>Break in x or y direction ?</b><br />Should I fill one column with categories before I make another column (x), or should I print categories vertical, and add a new row when the limit set below is set (y) ? <br />
  <select name="xy_c">
  <?
   print "<option value='x'"; 
   if ($xy=='x') { print " selected"; }
   print ">x</option>"; 
 
   print "<option value='y'"; 
   if ($xy=='y') { print " selected";}
   print ">y</option>"; 
    
   
   
  ?>
  </select><p />
  </td>
  </tr>


<tr>
  <td>
  <b>Validate emailaddress</b><br />By activating this, the user will have to confirm his/her email address
  by clicking a link in a special welcome email (editable from Mail menu) <br />
  <input type="checkbox" name="opt_verify" value="1" <? if ($opt_verify == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  <tr>
  <td>
  <b>Moderate users, validation ?</b><br />You must activate the user from the admin area. <br />
  <input type="checkbox" name="approve_mem" value="1" <? if ($approve_mem == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
    <tr>
  <td><b>User packages</b><br />Here you state what option new users have when they register.
  If you have activated Moderate users, a select box will be displayed. After x months, the user will be deactivated.
  A good idea for creditcard service. Seperate with , like 3,5,8 <br />
  <input type="text" size="20" maxlength="300" name="member_ab" value="<?php echo $member_ab ?>" /><p />
  </td>
  </tr>



<tr>
  <td>
  <b>Moderate ads, validation ?</b><br />You will need to approve all ads from the admin area. <br />
  <input type="checkbox" name="validation" value="1" <? if ($validation == 1) { print "checked"; } ?>><p />
  </td>
  </tr>


  <tr>
  <td><b>Numbers of categories per column (x) OR Number of columns</b><br />Here you set how many categories below eachother it should be in all pages. With many cats, it should be a high number, else it should be low. <br />
  <select name="categories_per_column">
  <?
  if (!$categories_per_column)
  {
       $categories_per_column = 4;
  }
  for ($i=0; $i<200; $i++)
  if ($categories_per_column == $i) { print("<option value=\"$i\" selected>$i"); }
  else { print("<option value=\"$i\">$i"); }        ?>
  </select><p />
  </td>
  </tr>

  <tr>
  <td><b>Numbers of ads per view in category</b><br />Display x numbers as default in each category. Next and previous buttons is displayed if not all ads is viewable here. <br />
  <select name="number_of_ads_per_page">
  <?
  if (!$number_of_ads_per_page)
  {
       $number_of_ads_per_page = 20;
  }
  for ($i=0; $i<200; $i++)
  if ($number_of_ads_per_page == $i) { print("<option value=\"$i\" selected>$i"); }
  else { print("<option value=\"$i\">$i"); }        ?>
  </select><p />
  </td>
  </tr>

  <tr>
  <td><b>Email newesletter activated ?</b><br />Shall newsletter function be activated, so that you can easy send email-news from admin area? <br />
  <input type="checkbox" name="email_activated" value="1" <? if ($email_activated == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  
  <tr>
  <td><b>Special mode</b><br />By activating this, all user menus as de-activated, and you must provide navigation youselves. Default is not checked. <br />
  <input type="checkbox" name="special_mode" value="1" <? if ($special_mode == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  <tr>
  <td><b>Expire ad after x days</b><br />After how many days shall we delete ads ? <font color="red">NOTE: This will delete all ads imidiately. The program only see when the ad is registered. If that date + the date you set below is reached, the ads will be deleted imidiately, without warning.  <br />
  <select name="delete_after_x_days">
  <? for ($i=20; $i<200; $i++)
  if ($delete_after_x_days == $i) { print("<option value=\"$i\" selected>$i"); }
  else { print("<option value=\"$i\">$i"); }        ?>
  </select><p />
  </td>
  </tr>

  <tr>
  <td><b>User-selected expire date?</b><br />Do you want a option on each ad where the user selects the number of days the ad should exsist, insted of the default expire afer x days as selected above ? <br />
  <input type="checkbox" name="expire_days_option" value="1" <? if ($expire_days_option == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
  
  <tr>
  <td><b>Latest ad on frontpage</b><br />Do you want to display the latest 10 ads on the frontpage, this item should be checked. <br />
  <input type="checkbox" name="latest" value="1" <? if ($latest == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  <tr>
  <td><b>Credits option?</b><br />If you have a payment system, you can choose to assign credits to each user based on paymenet. 1 credit is one ad.
  An option to fill acounts with credits will appear in the admin area. <br />
  <input type="checkbox" name="credits_option" value="1" <? if ($credits_option == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
    
  <tr>
  <td><b>Banned emailaddresses</b><br />Here you can block unwanted emailaddresses or emaildomains.
  For instance, <b>*@domain.com</b> will block all users with @domain.com in their emailaddress, while <b>tom@domain.com</b> only
  will block that particular address. NOTE: Seperate each banned email with the <b>,</b> character, like <b>*@domain.com,hank@anotherdomain.com</b>. <br />
  <input type="text" size="20" maxlength="300" name="bans" value="<?php echo $bans ?>" /><p />
  </td>
  </tr>

  <tr>
  <td><b>Bad words</b><br />Write all the bad word you would like to deny. If a user tries to post
  an ad containing these words, the user will not be allowed to do so.  <br />
  <input type="text" size="20" maxlength="300" name="bad_words" value="<?php echo $bad_words ?>" /><p />
  </td>
  </tr>
  
  
  
  <tr>
  <td>
  <b>Picture upload enable ?</b><br />Do you want to let users picture_upload_enable pictures to their ads ? <br />
  <input type="checkbox" name="picture_upload_enable" value="1"
  <?

  if ($picture_upload_enable == 1) { print "checked"; } ?>><p />
  </td>
  </tr>

  <tr>
  <td><b>Picture limit</b><br />Default size is 47000 (wich is 47 kb). <br />
  <input type="text" size="20" maxlength="20" name="piclimit" value="<?php echo $piclimit ?>" /><p />
  </td>
  </tr>
  
  <tr>
  <td><b>Picture maxsize (x and y direction on detailed.php)</b><br />If the image is larger that size
  set here, it will be scaled down to whatever max you set. <br />
  <input type="text" size="20" maxlength="20" name="maxSize" value="<?php echo $maxSize ?>" /><p />
  </td>
  </tr>
  
  
  
  <tr>
  <td><b>Picture maxsize gallery thumbnail (x and y direction on picturebrowse.php)</b><br />If the image is larger that size
  set here, it will be scaled down to whatever max you set. <br />
  <input type="text" size="20" maxlength="20" name="maxSize_gallery" value="<?php echo $maxSize_gallery ?>" /><p />
  </td>
  </tr>
  
  <tr>
  <td><b>Large picture max (x and y direction on large_picture.php)</b><br />If the image is larger that size
  set here, it will be scaled down to whatever max you set. <br />
  <input type="text" size="20" maxlength="20" name="maxSize_large" value="<?php echo $maxSize_large ?>" /><p />
  </td>
  </tr>
  
    <tr>
  <td>
  <b>List pictures</b><br />Display the users image on ad in list/search mode ? <br />
  <input type="checkbox" name="list_img" value="1"
  <?

  if ($list_img == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
  
    
  <tr>
  <td>
  <b>Do NOT list pictures on frontpage?</b><br />Disables small icon on frontpagelisting<br />
  <input type="checkbox" name="list_img_fp" value="1"
  <?

  if ($list_img_fp == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
  
  <tr>
  <td><b>Listing picture max (x and y direction on links.php)</b><br />If the image is larger that size
  set here, it will be scaled down to whatever max you set. <br />
  <input type="text" size="20" maxlength="20" name="max_links" value="<?php echo $max_links ?>" /><p />
  </td>
  </tr>
  
  
 <tr>
  <td><b>Max number of pictures per ad</b><br />When max is reached, a
  message will be displayed, and no pictures may be posted. <br />
  <input type="text" size="20" maxlength="20" name="maxpic" value="<?php echo $maxpic ?>" /><p />
  </td>
  </tr>
  


  <tr>
  <td>
  <b>Upload to files INSTEAD of MySql</b><br />When uploading ads, the program normally uploads images as binary numbers into MySql. If you want to (or your host doesnt allow it), activate it now. <br />
  <input type="checkbox" name="fileimg_upload" value="1" <? if ($fileimg_upload == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
   <tr>
  <td><b>Admin subject when new ad</b><br />Mail is sent to admin when posted. <br />
  <input type="text" size="20" maxlength="50" name="admin_new_ad_subject" value="<?php echo $admin_new_ad_subject ?>" /><p />
  </td>
  </tr>
  
  <tr>
  <td><b>Admin message when new ad</b><br />Mail is sent to admin when posted. <br />
  <input type="text" size="80" maxlength="200" name="admin_new_ad" value="<?php echo $admin_new_ad ?>" /><p />
  </td>
  </tr>
  
  <tr>
  <td><b>No webmaster mail</b><br />If you dont want an email sent to webmaster each time an ad is added. <br />
  <input type="checkbox" name="no_webmastermail" value="1" <? if ($no_webmastermail == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
  
  <tr>
  <td>
  <b>nl2br function on detail.php</b><br />Should ads be displayed like those were added. So
  that a enter push in the large textarea should be displayed as break in detail.php ? The php name for this is Newline To Break. <br />
  <input type="checkbox" name="nl2br" value="1" <? if ($nl2br == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  

    <tr>
  <td>
 <p class="red">FOR THOSE WHO HAVE IMAGEMAGIC ON SERVER ONLY!<br />Large benefit to use this instead of normal operation.</p>
  
  <b>ImageMagic activate?</b><br />This will rezise and reduce images on the fly. The benefit
  is less filesize, and thereby faster display/load of images. 50%< reduction in filespace. Also Note that it only work
  when uploaded files are FILES, and not database.
   <br />
  <input type="checkbox" name="magic" value="1" <? if ($magic == 1) { print "checked"; } ?>><p />
  </td>
  </tr>
  
  
  
    <tr>
  <td>
	  
  <b>ImageMagic: executeable convert path</b><br />Windows example: <small>C:\Programfiler\ImageMagick-5.4.9-Q16\convert.exe</small><br />
  Linux example: <small>/usr/bin/convert</small> <br />
  <input type="text" size="20" maxlength="300" name="magic_path" value="<?php echo $magic_path ?>" /><p />
  </td>
  </tr>
  
  
  <tr>
  <td><b>ImageMagic: Large picture size</b><br />Type it like 200x150, this
  will make sure that largest dimension is 200 and 150 width and hight.. Large picture is
  used on detail.php. <br />
  <input type="text" size="7" maxlength="20" name="magic_large_size" value="<?php echo $magic_large_size ?>" /><p />
  </td>
  </tr>
  
  
  
 <tr>
  <td><b>ImageMagic: Large picture quality</b><br />Number between 20 and 80. 80 is best, but is larger in filesize. <br />
  <input type="text" size="2" maxlength="20" name="magic_large_q" value="<?php echo $magic_large_q ?>" /><p />
  </td>
  </tr>
  
    
  <tr>
  <td><b>ImageMagic: Small picture size</b><br />Type it like 50x50. Small picture is
  used on listings (if activated) and in picture gallery. <br />
  <input type="text" size="7" maxlength="20" name="magic_small_size" value="<?php echo $magic_small_size ?>" /><p />
  </td>
  </tr>
   
  <tr>
   <td><b>ImageMagic: Small picture quality</b><br />Number between 20 and 80. 80 is best, but is larger in filesize. <br />
  <input type="text" size="2" maxlength="20" name="magic_small_q" value="<?php echo $magic_small_q ?>" /><p />
  </td>
  </tr>
  
  

 </table>
 <input type="submit" name="submit" value="Save">
 </form>
 <?
}
}




######################################################
## CREDITS.INC.PHP                                  ##
######################################################
if ($file_name == "config/credits.inc.php")
{
 if ($submit)
 {
  $cred = nl2br($cred);
  $cred=str_replace("<br />", "", $cred);
  $cred=str_replace("<br />", "", $cred);  
  $string = "\n$cred";
 }


 if (!$submit)
 {
 ?>
   <!-- DESCRIPTION -->
   <b>Creditpackages</b><br />
  Create unlimited creditpackages in single or bulk packages. These
  is chooseable in the User screen.
   
  <!-- / DESCRIPTION -->



 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
 <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
 <table width="100%">
 <tr>
 <td><b>Credits</b><br />Syntax is <b>credits:name</b>, example: 500:Superbulk. One per line.	 <br />
 <textarea rows="5" name="cred" cols="70"><? include "config/credits.inc.php"; ?></textarea><p />
 </td>
 </tr>

 

 </table>
 <input type="submit" name="submit" value="Save">
 </form>



<?
 }
}
######################################################
## MAIL.INC.PHP                                     ##
######################################################
if ($file_name == "config/mail.inc.php")
{
 if ($submit)
 {
  //$welcome_newu_msg = preg_replace("/(\015\012)|(\015)|(\012)/","\r",$welcome_newu_msg);
  //$val_msg = preg_replace("/(\015\012)|(\015)|(\012)/","\r",$val_msg);
$string = "<?\$welcome_newu_msg=\"$welcome_newu_msg\";\r
  \$welcome_newu_ttl=\"$welcome_newu_ttl\";\r
  \$val_msg=\"$val_msg\";\r
  \$val_ttl=\"$val_ttl\";\r 
  \$val_user_title=\"$val_user_title\";\r 
  \$val_user_msg=\"$val_user_msg\";\r 
  \$warn_ttl=\"$warn_ttl\";\r 
  \$warn_msg=\"$warn_msg\";\r
  \$approve_user_title=\"$approve_user_title\";\r
  \$approve_user_msg=\"$approve_user_msg\";\r
  \$sub_title=\"$sub_title\";\r
  \$sub_msg=\"$sub_msg\";\r

?>";

 }

 if (file_exists("config/mail.inc.php"))
 {
      include("config/mail.inc.php");
 }

 if (!$submit)
 {


 ?>

 <form method="post" action="set.php">
 <input type="hidden" name="setup" value="<?echo $setup ?>">
    <input type="hidden" name="file_name" value="<?echo $file_name ?>" />
 <table width="100%">

  <tr>
  <td><b>Welcome mail title</b><br />The title of the email sent when new user is registered. NOTE:
  It is a good idea to also tell the user that his/her member status will be activated later, if you have that 
  setting enabled. <br />
  <input type="text" size="20" maxlength="20" name="welcome_newu_ttl" value="<?php echo $welcome_newu_ttl ?>">
  </td>
  </tr>

  <tr>
  <td><b>Welcome mail message</b><br />The fields availble: {NAME}, {EMAIL}, {PASSWD}, {URL}, {SITENAME} <br />
<textarea rows="5" name="welcome_newu_msg" cols="70"><?echo $welcome_newu_msg;?></textarea><p />
  </td>
  </tr>

  <tr><td>&nbsp;&nbsp;</td></tr>


  <tr>
  <td><b>Validation mail title</b><br />This will be the subject field <br />
  <input type="text" size="20" maxlength="20" name="val_ttl" value="<?php echo $val_ttl ?>">
  </td>
  </tr>


  <tr>
  <td><b>Validation mail</b><br />Mail to be sent vhen ad is validated. Fields avaible: {NAME}
{AD_ID}, {EMAIL}, {TITLE}, {CATNAME}, {URL}, {SITENAME} <br />
<textarea rows="5" name="val_msg" cols="70"><?echo $val_msg;?></textarea><p />
  </td>
  
</tr>  
  <tr><td>&nbsp;&nbsp;</td></tr>


  <tr>
  <td><b>Verify user</b><br />This will be the subject field <br />
  <input type="text" size="20" maxlength="20" name="val_user_title" value="<?php echo $val_user_title ?>">
  </td>
  </tr>


  <tr>
  <td><b>Verify mail</b><br />Mail to be sent vhen ad is validated. Fields avaible: {NAME}
{AD_ID}, {EMAIL}, {TITLE}, {CATNAME}, {URL}, {SITENAME},{VERIFY} <br />
<textarea rows="5" name="val_user_msg" cols="70"><?echo $val_user_msg;?></textarea><p />
  </td>
  </tr>
  
  
    <tr><td>&nbsp;&nbsp;</td></tr>


  <tr>
  <td><b>Warning mail subject</b><br />This will be the subject field when notification to user about an ad that is 
  about to expire. <br />
  <input type="text" size="20" maxlength="20" name="warn_ttl" value="<?php echo $warn_ttl ?>">
  </td>
  </tr>


  <tr>
  <td><b>Warning mail msg</b><br />Mail to be sent vhen ad is about to expire: 
  {AD_ID}, {EMAIL}, {TITLE}, {URL}, {SITENAME} <br />
<textarea rows="5" name="warn_msg" cols="70"><?echo $warn_msg;?></textarea><p />
  </td>
  </tr>
  
  
  
  <tr>
  <td><b>Approve user title</b><br />Tell a user that he/she is approved! <br />
  <input type="text" size="20" maxlength="200" name="approve_user_title" value="<?php echo $approve_user_title ?>">
  </td>
  </tr>


  <tr>
  <td><b>Approve user msg</b><br />Tell a user that he/she is approved! <br />
  <input type="text" size="20" maxlength="255" name="approve_user_msg" value="<?php echo $approve_user_msg ?>">
  </td>
  </tr>
  
  
  <tr>
  <td><b>Approved user from admin area, and extended their subscription</b><br />
  So that the user knows he can log in.<br />
  <input type="text" size="20" maxlength="200" name="sub_title" value="<?php echo $sub_title ?>">
  </td>
  </tr>


  <tr>
  <td><b>Approved user from admin area, and extended their subscription</b><br />
  So that the user knows he can log in.<br />
  <input type="text" size="20" maxlength="255" name="sub_msg" value="<?php echo $sub_msg ?>">
  </td>
  </tr>
  

  
  
  

 </table>
 <input type="submit" name="submit" value="Save">
 </form>
 <?
}
}







if ($file_name=="phpconfig" AND $demom <> 1)
{
 phpinfo();
}
elseif ($demom==1)
{
 print "Function disabled in demo";
}




// DEFAULT CREATE STATEMENT
if ($submit AND !$stop AND $demom <> 1)
{
 if ($string)
 {
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,$string);
  fclose($file_pointer);
  print "<p /><b>$file_name created sucessfully!</b>";
 }
 else
 {
  $file_pointer = fopen("config/header.inc.php", "w");
  $string1 = str_replace('\"', '"', $string1);
  $string1 = str_replace("\'", "'", $string1);

  $string1 = preg_replace("/(\015\012)|(\015)|(\012)/","\r",$string1);
  fwrite($file_pointer,"$string1");
  fclose($file_pointer);
  print "<br /><b>header.inc.php created sucessfully!</b>";

  $file_pointer2 = fopen("config/footer.inc.php", "w");
  $string2 = str_replace('\"', '"', $string2);
  $string2 = str_replace("\'", "'", $string2);

  $string2 = preg_replace("/(\015\012)|(\015)|(\012)/","\r",$string2);
  fwrite($file_pointer2,"$string2");
  fclose($file_pointer2);
  print "<br /><b>footer.inc.php created sucessfully!</b>";

 }
}


// END OF DEFAULT CREATE



?>
    <p />
         </td>
</tr>
</table>

<?
require("admfooter.php");
?>
