<form method="POST" action="install.php">
<input type="hidden" name="level" value="2">
<b>TABLE CREATION</b><br />
<? require("$dir/admin/db.php"); ?>
<small>Be sure to fill out ALL FIELDS below, else the installer will fail.</small>
<p>



<?
if (!$submit)
{
global $cat_tbl;
global $ads_tbl;
global $usr_tbl;
global $pic_tbl;
?>
<table>
<tr>
 <td> <b>Category table</b><br /><small>Category table where PHP Classifieds will store its categories. <br />
 <input type="text" size="10" maxlength="10" name="cat_tbl" value="<?
 if (!$cat_tbl)
 {
       print "category";
 }
 ?>"></small><p></td>
</tr>

<tr>
 <td> <b>Ads table</b><br /><small>Ad table where all your ads is saved. <br />
 <input type="text" size="10" maxlength="10" name="ads_tbl" value="<?
 if (!$ads_tbl)
 {
       print "ad";
 }
 ?>"></small><p></td>
</tr>

<tr>
 <td> <b>User table</b><br /><small>User table where PHP Classifieds will store its users. <br />
 <input type="text" size="10" maxlength="10" name="usr_tbl" value="<?
 if (!$usr_tbl)
 {
       print "user";
 }
 ?>"></small><p></td>
</tr>

<tr>
 <td> <b>Picture table</b><br /><small>MySql table where PHP Classifieds will store its images. <br />
 <input type="text" size="10" maxlength="10" name="pic_tbl" value="<?
 if (!$pic_tbl)
 {
       print "picture";
 }
 ?>"></small><p></td>
</tr>
</table>
<input type="submit" value="Create tables" name="submit"></form>

<?
}


if ($submit AND $level==2)
{
	
	print "Checking for existing tables, in order to avoid conflicts...<p>";
	
	
	$result = mysql_list_tables($datab);
	   
	if (!$result) 
	{
	        print "DB Error, could not list tables\n";
	        print 'MySQL Error: ' . mysql_error();
	        exit;
	}
	
	if ($multidrop)
	{
		$result_9 = mysql_query("drop TABLE $cat_tbl");
		$result_9 = mysql_query("drop TABLE $ads_tbl");
		$result_9 = mysql_query("drop TABLE $pic_tbl");
		$result_9 = mysql_query("drop TABLE template");
		$result_9 = mysql_query("drop TABLE $usr_tbl");
			
	}
	
	
	while ($row = mysql_fetch_row($result)) 
	{
	        if (strtolower($row[0]) == strtolower($cat_tbl))
	        {
	        	print "Table $cat_tbl alredy exists!<br>";	
	        	$exit = 1;
	        }
	        if (strtolower($row[0]) == strtolower($ads_tbl))
	        {
	        	print "Table $ads_tbl alredy exists!<br>";	
	        	$exit = 1;
	        }
	        if (strtolower($row[0]) == strtolower($pic_tbl))
	        {
	        	print "Table $pic_tbl alredy exists!<br>";	
	        	$exit = 1;
	        }
	        if (strtolower($row[0]) == strtolower("template"))
	        {
	        	print "Table template alredy exists!<br>";	
	        	$exit = 1;
	        }
	        if (strtolower($row[0]) == strtolower($usr_tbl))
	        {
	        	print "Table $usr_tbl alredy exists!<br>";	
	        	$exit = 1;
	        }
	        
	        
	}


	if ($exit)
	{
		print "<hr><font color='red'><b>Warning:</b></font><br />The installer showed that one or more tables existed. Delete these tables by 
		clicking link to <b>MULTIDROP</b> link below. You will return to this level again when it has
		dropped all tables, and then you can continue (Note: This users must have privelegies in mysql to Drop tables).
		<p>
		<a href='install.php?multidrop=1&level=2&cat_tbl=$cat_tbl&ads_tbl=$ads_tbl&pic_tbl=$pic_tbl&usr_tbl=$usr_tbl&submit=1'>Multidrop (drop all tables needed)</a>
		<p>
		<a href='install.php?level=2'>Use other table names (returns you to previous screen</a>
		<hr>";	
	}
	
	if (!$exit)
	{
			
			$result_cat = mysql_query("CREATE TABLE $cat_tbl(
			  catid int(11) NOT NULL auto_increment,
			  catfatherid int(11) default NULL,
			  catname varchar(100) default NULL,
			  catdescription varchar(150) default NULL,
			  catimage varchar(100) default 'default.gif',
			  total varchar(5) default '0',
			  latest_date varchar(8) default NULL,
			  cattpl varchar(50) default NULL,
			  allowads char(2) default NULL,
			  catfullname varchar(150) default NULL,
			  PRIMARY KEY  (catid))");
			
			$result_ads = mysql_query("CREATE TABLE $ads_tbl (
			  siteid int(11) NOT NULL auto_increment,
			  sitetitle varchar(100) default NULL,
			  sitedescription text,
			  siteurl varchar(100) default NULL,
			  sitedate varchar(10) default NULL,
			  expiredate varchar(12) default NULL,
			  sitecatid int(11) default NULL,
			  sitehits int(11) default NULL,
			  sitevotes double(16,4) default NULL,
			  sites_userid int(11) default NULL,
			  sites_pass varchar(12) default NULL,
			  custom_field_1 varchar(50) default NULL,
			  custom_field_2 varchar(50) default NULL,
			  custom_field_3 varchar(50) default NULL,
			  custom_field_4 varchar(50) default NULL,
			  custom_field_5 varchar(50) default NULL,
			  custom_field_6 varchar(50) default NULL,
			  custom_field_7 varchar(50) default NULL,
			  custom_field_8 varchar(50) default NULL,
			  picture int(11) default '0',
			  img_stored varchar(70) default NULL,
			  datestamp timestamp(8) NOT NULL,
			  f1 varchar(50) default NULL,
			  f2 varchar(50) default NULL,
			  f3 varchar(50) default NULL,
			  f4 varchar(50) default NULL,
			  f5 varchar(50) default NULL,
			  f6 varchar(50) default NULL,
			  f7 varchar(50) default NULL,
			  f8 varchar(50) default NULL,
			  f9 varchar(50) default NULL,
			  f10 varchar(50) default NULL,
			  f11 varchar(50) default NULL,
			  f12 varchar(50) default NULL,
			  f13 varchar(50) default NULL,
			  f14 varchar(50) default NULL,
			  f15 varchar(50) default NULL,
			  ad_username varchar(50) default NULL,
			  valid tinyint(4) default '0',
			  expire_days int(11) default '0',
			  sold tinyint(4) default NULL,
			  notify int(11) default NULL,
			  PRIMARY KEY  (siteid))");
			
			$result_usr = mysql_query("CREATE TABLE $usr_tbl (
			  userid int(11) NOT NULL default '0',
			  name varchar(100) default NULL,
			  adressfield1 varchar(100) default NULL,
			  adressfield2 varchar(100) default NULL,
			  adressfield3 varchar(100) default NULL,
			  phone varchar(30) default NULL,
			  email varchar(50) NOT NULL default '',
			  pass varchar(12) default NULL,
			  registered varchar(8) default '0',
			  emelding tinyint(4) default NULL,
			  num_ads int(11) default '0',
			  country varchar(50) default NULL,
			  hide_email tinyint(4) default NULL,
			  custom_1 varchar(50) default NULL,
			  usr_1 varchar(150) default NULL,
			  usr_2 varchar(150) default NULL,
			  usr_3 varchar(150) default NULL,
			  usr_4 varchar(150) default NULL,
			  usr_5 varchar(150) default NULL,
			  password_enc varchar(30) default NULL,
			  credits int(11) default '0',
			  status tinyint(4) default NULL,
			  verify varchar(30) default '0',
			  last_login varchar(12) default NULL,
			  num_logged int(11) default '0',
			  months int(11) default '0',
			  approve int(11) default NULL,
			  approve_from varchar(8) default '0',
			  request_credits int(11) default '0',
			  PRIMARY KEY  (email))");
			
			$result_pic = mysql_query("CREATE TABLE $pic_tbl (
			  id int(4) NOT NULL auto_increment,
			  pictures_siteid varchar(6) default NULL,
			  bin_data longblob,
			  filename varchar(50) default NULL,
			  filesize varchar(50) default NULL,
			  filetype varchar(50) default NULL,
			  imagew varchar(10) default NULL,
			  imageh varchar(10) default NULL,
			  PRIMARY KEY  (id))");
			
			
			
			$result2 = mysql_query("CREATE TABLE template (
			     tplid int(11) NOT NULL auto_increment,
			  name varchar(50) default NULL,
			  f1_caption varchar(50) default NULL,
			  f1_type varchar(50) default NULL,
			  f1_mandatory char(3) default NULL,
			  f1_length varchar(5) default NULL,
			  f1_filename varchar(50) default NULL,
			  f2_caption varchar(50) default NULL,
			  f2_type varchar(50) default NULL,
			  f2_mandatory char(3) default NULL,
			  f2_length varchar(5) default NULL,
			  f2_filename varchar(50) default NULL,
			  f3_caption varchar(50) default NULL,
			  f3_type varchar(50) default NULL,
			  f3_mandatory char(3) default NULL,
			  f3_length varchar(5) default NULL,
			  f3_filename varchar(50) default NULL,
			  f4_caption varchar(50) default NULL,
			  f4_type varchar(50) default NULL,
			  f4_mandatory char(3) default NULL,
			  f4_length varchar(5) default NULL,
			  f4_filename varchar(50) default NULL,
			  f5_caption varchar(50) default NULL,
			  f5_type varchar(50) default NULL,
			  f5_mandatory char(3) default NULL,
			  f5_length varchar(5) default NULL,
			  f5_filename varchar(50) default NULL,
			  f6_caption varchar(50) default NULL,
			  f6_type varchar(50) default NULL,
			  f6_mandatory char(3) default NULL,
			  f6_length varchar(5) default NULL,
			  f6_filename varchar(50) default NULL,
			  f7_caption varchar(50) default NULL,
			  f7_type varchar(50) default NULL,
			  f7_mandatory char(3) default NULL,
			  f7_length varchar(5) default NULL,
			  f7_filename varchar(50) default NULL,
			  f8_caption varchar(50) default NULL,
			  f8_type varchar(50) default NULL,
			  f8_mandatory char(3) default NULL,
			  f8_length varchar(5) default NULL,
			  f8_filename varchar(50) default NULL,
			  f9_caption varchar(50) default NULL,
			  f9_type varchar(50) default NULL,
			  f9_mandatory char(3) default NULL,
			  f9_length varchar(5) default NULL,
			  f9_filename varchar(50) default NULL,
			  f10_caption varchar(50) default NULL,
			  f10_type varchar(50) default NULL,
			  f10_mandatory char(3) default NULL,
			  f10_length varchar(5) default NULL,
			  f10_filename varchar(50) default NULL,
			  f11_caption varchar(50) default NULL,
			  f11_type varchar(50) default NULL,
			  f11_mandatory char(3) default NULL,
			  f11_length varchar(5) default NULL,
			  f11_filename varchar(50) default NULL,
			  f12_caption varchar(50) default NULL,
			  f12_type varchar(50) default NULL,
			  f12_mandatory char(3) default NULL,
			  f12_length varchar(5) default NULL,
			  f12_filename varchar(50) default NULL,
			  f13_caption varchar(50) default NULL,
			  f13_type varchar(50) default NULL,
			  f13_mandatory char(3) default NULL,
			  f13_length varchar(5) default NULL,
			  f13_filename varchar(50) default NULL,
			  f14_caption varchar(50) default NULL,
			  f14_type varchar(50) default NULL,
			  f14_mandatory char(3) default NULL,
			  f14_length varchar(5) default NULL,
			  f14_filename varchar(50) default NULL,
			  f15_caption varchar(50) default NULL,
			  f15_type varchar(50) default NULL,
			  f15_mandatory char(3) default NULL,
			  f15_length varchar(5) default NULL,
			  f15_filename varchar(50) default NULL,
			  PRIMARY KEY  (tplid));");
			// End of result2
			
			$result3 = mysql_query("INSERT INTO template (tplid, name, f1_caption, f1_type, f1_mandatory, f1_length, f2_caption, f2_type, f2_mandatory, f2_length, f3_caption, f3_type, f3_mandatory, f3_length, f4_caption, f4_type, f4_mandatory, f4_length, f5_caption, f5_type, f5_mandatory, f5_length, f6_caption, f6_type, f6_mandatory, f6_length, f7_caption, f7_type, f7_mandatory, f7_length, f8_caption, f8_type, f8_mandatory, f8_length, f9_caption, f9_type, f9_mandatory, f9_length, f10_caption, f10_type, f10_mandatory, f10_length, f11_caption, f11_type, f11_mandatory, f11_length, f12_caption, f12_type, f12_mandatory, f12_length, f13_caption, f13_type, f13_mandatory, f13_length, f14_caption, f14_type, f14_mandatory, f14_length, f15_caption, f15_type, f15_mandatory, f15_length) VALUES ('', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '') ");
			// End of result3
			
			
			
			
			        if (!$result_ads)
			        {
			              print "<small>The ad table $ads_tbl not created, because it already exists !</small><br />";
			        }
			
			        if (!$result_cat)
			        {
			              print "<small>The cat table $cat_tbl not created, because it already exists !</small><br />";
			        }
			
			        if (!$result_pic)
			        {
			              print "<small>The picture table $cat_tbl not created, because it already exists !</small><br />";
			        }
			
			        if (!$result_usr)
			        {
			              print "<small>The user table $usr_tbl not created, because it already exists !</small><br />";
			        }
			
			
			
			
			        if ($result_ads & $result_cat & $result_pic & $result_usr & $result2 & !$exit)
			        {
			        	           ?>
			                    <input type="hidden" name="usr_tbl" value="<? echo $usr_tbl ?>">
			                    <input type="hidden" name="ads_tbl" value="<? echo $ads_tbl ?>">
			                    <input type="hidden" name="cat_tbl" value="<? echo $cat_tbl ?>">
			                    <input type="hidden" name="pic_tbl" value="<? echo $pic_tbl ?>">
			                     <?
			                   print("<p>Tables created/altered, and all seems OK.
			                   <p><a href='install.php?level=3&usr_tbl=$usr_tbl&ads_tbl=$ads_tbl&cat_tbl=$cat_tbl&pic_tbl=$pic_tbl'>Next step</a>.");
			
			         }
			        else
			        {
			                print(" Table creation/alteration failed. Maybe tables already exists ? ");
			                print "<b>DEBUGINFO:</b><br />";
							print "Result ads: $result_ads<br />";
							print "Result cat: $result_cat<br />";
							print "Result pic: $result_pirc<br />";
							print "Result usr: $result_usr<br />";
							print "Result Template: $result2<br />";
							print "Result Insert: $result3<br />";
							
							print "<p>Where there is a 1, a table alredaDo NOT proceed! <p> <a href='install.php?level=3&usr_tbl=$usr_tbl&ads_tbl=$ads_tbl&cat_tbl=$cat_tbl&pic_tbl=$pic_tbl'>Next step</a>.";
							
			        }

	}
}
?>
