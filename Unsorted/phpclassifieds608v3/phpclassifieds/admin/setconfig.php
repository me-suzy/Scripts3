<?
$v = 6.04;
if (!$setup)
{
require("admheader.php");
?><!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
                <td bgcolor="lightgrey">
                                 
                                &nbsp; Settings 
                </td>
</tr>

<tr bgcolor="white">
                <td width="100%">
                                 
<?
}


// NOTE: Here you can set full path to you files if the in-built php function
// getcwd doesn´t work.
// Example: $curdir = "/home/user/public_html/phpclassifieds";
// Remember to remove! the $curdir = getcwd(); if you do this.

// Uncomment THE BELOW LINE if manually set:
// $curdir = "/home/user/public_html/phpclassifieds";

// Delete BELOW LINE if manually set $dir above ! ! !



if (!$curdir)
{
        $curdir = dirname($SCRIPT_FILENAME);
}

if (!$setup)
{
 $curdir = str_replace ("/admin","", $curdir);
}

if ($setup)
{
 $fileadress = $curdir . "/config";
}
else
{
 $fileadress = "../config";
}

$permission = fileperms("$fileadress");
if ($permission == 16895)
{
        print "<b>Ready to save, all is OK.</b>";
        if (!$setup)
        {
        print "<br />Remember to do a backup";
        print " of this file before clicking Save. Use the <a href='backup.php'>backup tool</a>. ";
        print "If the transfer is beeing interupted, you might broke the install, but by getting the ";
        print "backup file config.inc.php (or mysql table config), you can fix it.<p>";
        }
}
else
{
 print(" ERROR <br />You must set the directory <i>$fileadress</i> to all writeable (chmod 777 $file) in order for this program to work. Please correct this before preceeding.");
}
if (!$demo)
{

?>

<form method="post" action"<? echo $PHP_SELF ?>">
<input type="hidden" name="setup" value="<?echo $setup ?>">
<?
}

if (!$submit)
{
?>
<input type="submit" name="submit" value="Save">
<?
}



if ($setup)
{
include("admin/db.php");
}
else
{
include("db.php");
}
$sql_select = "select * from Config where id = 1";
$sql_update = "update Config set version='$v',language='$lan',urladress='$urladress',full_path='$full_path',from_adress='$from_adress_mail',advanced_delete='$advancedd',auto='$autou',delete_members='$delete_members',cat_per_c='$cat_per_c',expire_after='$expire_after',latest='$late',upload='$upload',fileupload='$fileupload',cat_tbl='$cat_t',ads_tbl='$ads_t',usr_tbl='$usr_t',pic_tbl='$pic_t', c1='$c1',c2='$c2',c3='$c3',c4='$c4',c5='$c5',c6='$c6',c7='$c7',c8='$c8',simple='$simple',piclimit='$piclimit',numads='$numads',email_act='$email_act',spec='$spec',header_file='$header_f',footer_file='$footer_f',name_of_site='$names',usr_1_text='$usr_1_t',usr_2_text='$usr_2_t',usr_3_text='$usr_3_t',usr_4_text='$usr_4_t',usr_5_text='$usr_5_t', xy ='$xy_c', validation = '$validation_c' where id = 1";

if (!$submit)
{
 $result = mysql_query ($sql_select);

while ($row = mysql_fetch_array($result)) {


        $lan = $row["language"];
        $urladress = $row["urladress"];
        $full_path = $row["full_path"];
        $from_adress_mail = $row["from_adress"];
        $advancedd = $row["advanced_delete"];
        $autou = $row["auto"];
        $delete_members = $row["delete_members"];
        $cat_per_c = $row["cat_per_c"];
        $expire_after = $row["expire_after"];
        $late = $row["latest"];
        $upload = $row["upload"];
        $fileupload = $row["fileupload"];
        $cat_t = $row["cat_tbl"];
        $ads_t = $row["ads_tbl"];
        $usr_t = $row["usr_tbl"];
  $pic_t = $row["pic_tbl"];
        $c1 = $row["c1"];
        $c2 = $row["c2"];
        $c3 = $row["c3"];
        $c4 = $row["c4"];
        $c5 = $row["c5"];
        $c6 = $row["c6"];
        $c7 = $row["c7"];
        $c8 = $row["c8"];
        $piclimit = $row["piclimit"];
        $simple = $row["simple"];
        $numads = $row["numads"];
        $email_act = $row["email_act"];
        $spec = $row["spec"];
        $header_file = $row["header_file"];
        $footer_file = $row["footer_file"];
        $names = $row["name_of_site"];
        $usr_1_text = $row["usr_1_text"];
        $usr_2_text = $row["usr_2_text"];
        $usr_3_text = $row["usr_3_text"];
        $usr_4_text = $row["usr_4_text"];
        $usr_5_text = $row["usr_5_text"];
        $xy = $row["xy"];
        $validation = $row["validation"];
        }
}
// Update:
if ($submit)
{

         if ($full_path & $cat_t & $ads_t & $usr_t & $pic_t & $from_adress_mail)
         {
                 $result2 = mysql_query ($sql_update);
                //print "--------------------------------\n";
                //print $sql_update;
                //print "--------------------------------\n";
                 $error = 0;

         }
         else
         {
                 print("<p> <b>Error</b><br />On of the mandatory fields was not filled out!
                 Go back to correct ");
                 $error = 1;
         }


        if ($setup)
        {
                  $file_name = $curdir . "/config/config.inc.php";
        }
        else
        {
                  $file_name = "../config/config.inc.php";
        }

        if (!$error)
        {

         if ($expire_after < 10)
         {
              $expire_after = 10;
         }
         $file_pointer = fopen($file_name, "w");

        fwrite($file_pointer,"<? /*PHP CLASSIFIEDS\n
        NOTE: This file is generated automatically from admin ! DO NOT EDIT*/\n
        \$version=\"6.03\";\r
        \$language=\"$lan\";\r
        \$url=\"$urladress\";\r
        \$full_path_to_public_program = \"$full_path\";\r
        \$from_adress=\"$from_adress_mail\";\r
        \$advanced_delete_activated=\"$advancedd\";\r
        \$auto=\"$autou\";\r
        \$delete_members_activated=\"$delete_members\";\r
        \$categories_per_column=\"$cat_per_c\";\r
        \$delete_after_x_days=\"$expire_after\";\r
        \$latest=\"$late\";\r
        \$picture_upload_enable=\"$upload\";\r
        \$fileimg_upload=\"$fileupload\";\r
        \$cat_tbl=\"$cat_t\";\r
        \$ads_tbl=\"$ads_t\";\r
        \$usr_tbl=\"$usr_t\";\r
        \$pic_tbl=\"$pic_t\";\r
        \$custom_field_1_text=\"$c1\";\r
        \$custom_field_2_text=\"$c2\";\r
        \$custom_field_3_text=\"$c3\";\r
        \$custom_field_4_text=\"$c4\";\r
        \$custom_field_5_text=\"$c5\";\r
        \$custom_field_6_text=\"$c6\";\r
        \$custom_field_7_text=\"$c7\";\r
        \$custom_field_8_text=\"$c8\";\r
        \$piclimit=$piclimit;\r
        \$simple_structure=\"$simple\";\r
        \$number_of_ads_per_page=$numads;\r
        \$email_activated=\"$email_act\";\r
        \$email_validation=\"$email_val\";\r
        \$special_mode=\"$spec\";\r
        \$name_of_site=\"$names\";\r
        \$usr_1_text=\"$usr_1_t\";\r
        \$usr_2_text=\"$usr_2_t\";\r
        \$usr_3_text=\"$usr_3_t\";\r
        \$usr_4_text=\"$usr_4_t\";\r
        \$usr_5_text=\"$usr_5_t\";\r
        \$xy=\"$xy_c\";\r
        \$validation=\"$validation_c\";\r
        require(\"$full_path/language/$lan\");\r
        require(\"$full_path/admin/db.php\");\r ?>");
        fclose($file_pointer);


        print "<p><b>Config.inc.php created sucessfully!</b>";

        // Write header_file
        if ($setup)
        {
                $file_name_header = $curdir . "/config/header.inc.php";
        }
        else
        {
                $file_name_header = "../config/header.inc.php";
        }

        $file_pointer_2 = fopen($file_name_header, "w");
        $header_file = str_replace('\"', '"', $header_f);
        fwrite($file_pointer_2,"$header_file");
        fclose($file_pointer_2);
        print "<br /><b>header.inc.php created sucessfully!</b>";

        // Write footer_file
        if ($setup)
        {
                $file_name_footer = $curdir . "/config/footer.inc.php";
        }
        else
        {
                $file_name_footer = "../config/footer.inc.php";
        }
        //$file_name_footer = $curdir . "/config/footer.inc.php";

        $file_pointer_3 = fopen($file_name_footer, "w");
        $footer_file = str_replace('\"', '"', $footer_f);
        fwrite($file_pointer_3,"$footer_file");
        fclose($file_pointer_3);
        print "<br /><b>footer.inc.php created sucessfully!</b><p>";

        print "<br /><a href=\"setconfig.php\">Edit Settings</a>";
}
}

if (!$submit)
{
?>


<table width="100%">
<tr><td> <b>Settings for PHP Classifieds</b><br />From here you can set many options relating to PHP Classifieds program. Be sure to notice the instructions, since it is rather important that all info is placed on the right format. <p></td></tr>

<tr>
        <td> <b>Language</b><br />Here you set your language file. Default is eng for english, but
        many more languages is availble. This language must exists as a language.php file on your server in the /language dir. <br />
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
        <select name="lan">
        <?
        if (!$lan)
        {
                  $lan = "eng.php";
        }
        print "<option value='$lan' selected>$lan";
        while ($file = readdir($dir))
        {
         print "<option value='$file'>$file</option>";
        }
        closedir($dir);
        ?>
        </select>

        </td>
</tr>

<tr>
        <td><p> <b>Name of you site</b><br />This is the name that will be shown on all pages. Normally your sitetitle. <br />
                <input type="text" size="50" maxlength="140" name="names" value="<?php echo $names ?>"><p>
        </td>
</tr>

<tr>
        <td><b>URL</b><br />This is the adress to the classifieds main directory. Like: <i>www.a4.no/phpclassifieds/.</i> <br />
                <input type="text" size="50" maxlength="140" name="urladress" value="<?php echo $urladress ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Full path</b><br />The program needs to know the full path to the phpclassifieds main dir. Below is the scripts suggestion, you might change the <i>classifieds</i> to something else.
        NOTE: This is one of the most important settings in the program. If this is set wrong, you will get errors over
        the hole program. Normally, the path is like /home/username/public_html/classifieds. The default path is set below, and <b>should</b> be the correct path !
         Give the path without trailing slash (/). <br />
                <input type="text" size="50" maxlength="140" name="full_path" value="<?php
if (!$full_path)
{
 echo $curdir;
}
else
{
echo $full_path;
}
?>">


<p>
        </td>
</tr>



<tr>
        <td><b>Webmaster adress</b><br />The program often uses your adress as the sender when sending newsletter, but also. when notifying about new ads. <br />
                <input type="text" size="50" maxlength="50" name="from_adress_mail" value="<?php echo $from_adress_mail ?>"><p>
        </td>
</tr>

<tr>
        <td>
                <b>Moderate ads, validation ?</b><br />You will need to approve all ads from the admin area. <br />
                <input type="checkbox" name="validation_c" value="1" <? if ($validation == 1) { print "checked"; } ?>><p>
        </td>
</tr>


<tr>
        <td>
                <b>Advanced delete ?</b><br />This lets you use advanced and powerful delete. Ads will be deleted automatically after x days. <br />
                <input type="checkbox" name="advancedd" value="1" <? if ($advancedd == 1) { print "checked"; } ?>><p>
        </td>
</tr>



<tr>
        <td>
                                <b>Autoupdate</b><br />Autoupdate update the number of ads-counter on the main categories, and also delete ads and users. Note that if you have many ads/users, your should NOT activate this, and rather run the update script at regulary intervall. <br />
                <input type="checkbox" name="autou" value="1" <? if ($autou == 1) { print "checked"; } ?>><p>
        </td>
</tr>


<!--
<tr>
        <td>
                                <b>Delete members activated ?</b><br />Do you want to delete users that have not a posted ad, and has not an ad inserted on the 90th day? <br />
                <input type="checkbox" name="delete_members" value="1" <? if ($delete_members == 1) { print "checked"; } ?>><p>
        </td>
</tr>
-->
<tr>
        <td><b>Break in x or y direction ?</b><br />Should I fill one column with categories before I make another column (x), or should I print categories vertical, and add a new row when the limit set below is set (y) ? <br />
                <select name="xy_c">
                <?
                if ($xy == 'x') { print("<option value=\"x\" selected>x"); }
                elseif ($xy == 'y') { print("<option value=\"y\">y"); }
                ?>
                <option value="x">x
                <option value="y">y
                </select><p>
        </td>
</tr>




<tr>
        <td><b>Numbers of categories per column (x) OR Number of columns</b><br />Here you set how many categories below eachother it should be in all pages. With many cats, it should be a high number, else it should be low. <br />
                <select name="cat_per_c">
                <? for ($i=0; $i<200; $i++)
                if ($cat_per_c == $i) { print("<option value=\"$i\" selected>$i"); }
                else { print("<option value=\"$i\">$i"); }        ?>
                </select><p>
        </td>
</tr>

<tr>
        <td><b>Numbers of ads per view in category</b><br />Display x numbers as default in each category. Next and previous buttons is displayed if not all ads is viewable here. <br />
                <select name="numads">
                <? for ($i=0; $i<200; $i++)
                if ($numads == $i) { print("<option value=\"$i\" selected>$i"); }
                else { print("<option value=\"$i\">$i"); }        ?>
                </select><p>
        </td>
</tr>

<tr>
        <td>
                                <b>Email newesletter activated ?</b><br />Shall newsletter function be activated, so that you can easy send email-news from admin area? <br />
                <input type="checkbox" name="email_act" value="1" <? if ($email_act == 1) { print "checked"; } ?>><p>
        </td>
</tr>


<tr>
        <td>
                                <b>Emailvalidation ?</b><br />Ensures that the user has access to a valid emailaddress. <br />
                <input type="checkbox" name="email_val" value="1" <? if ($email_val == 1) { print "checked"; } ?>><p>
        </td>
</tr>

<tr>
        <td>
                                <b>Special mode</b><br />By activating this, all user menus as de-activated, and you must provide navigation youselves. Default is not checked. <br />
                <input type="checkbox" name="spec" value="1" <? if ($spec == 1) { print "checked"; } ?>><p>
        </td>
</tr>




<tr>
        <td>                <b>Expire ad after x days</b><br />After how many days shall we delete ads ?
        <font color="red">NOTE: This will delete all ads imidiately. The program only see when the ad is registered. If that date + the date you set below is reached, the ads will be deleted imidiately, without warning.  <br />
                <select name="expire_after">
                <? for ($i=20; $i<200; $i++)
                if ($expire_after == $i) { print("<option value=\"$i\" selected>$i"); }
                else { print("<option value=\"$i\">$i"); }        ?>
                </select><p>

        </td>
</tr>



<tr>
        <td>
                <b>Latest ad on frontpage</b><br />Do you want to display the latest 10 ads on the frontpage, this item should be checked. <br />
                <input type="checkbox" name="late" value="1" <? if ($late == 1) { print "checked"; } ?>><p>
        </td>
</tr>



<tr>
        <td>
                <b>Upload</b><br />Do you want to let users upload pictures to their ads ? <br />
                <input type="checkbox" name="upload" value="1" <? if ($upload == 1) { print "checked"; } ?>><p>
        </td>
</tr>

<tr>
        <td><b>MySql upload limit</b><br />Default size is 47000 (wich is 47 kb). <br />
            <input type="text" size="20" maxlength="20" name="piclimit" value="<?php echo $piclimit ?>"><p>
        </td>
</tr>

<tr>
        <td>
                                <b>Fileupload to files INSTEAD of MySql</b><br />When uploading ads, the program normally uploads images as binary numbers into MySql. If you want to (or your host doesnt allow it), activate it now. <br />
                <input type="checkbox" name="fileupload" value="1" <? if ($fileupload == 1) { print "checked"; } ?>><p>
        </td>
</tr>



<tr>
        <td><b>Category table</b><br />MySql field where PHP Classifieds will store its categories. <br />
                <input type="text" size="10" maxlength="10" name="cat_t" value="<?php echo $cat_t ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Ads table</b><br />Ad table where all your ads is saved. <br />
                <input type="text" size="10" maxlength="10" name="ads_t" value="<?php echo $ads_t ?>"><p>
        </td>
</tr>



<tr>
        <td><b>User table</b><br />MySql field where PHP Classifieds will store its users. <br />
                <input type="text" size="10" maxlength="10" name="usr_t" value="<?php echo $usr_t ?>"><p>
        </td>
</tr>

<tr>
        <td><b>Picture table</b><br />MySql field where PHP Classifieds will store its images (if choosen). <br />
                <input type="text" size="10" maxlength="10" name="pic_t" value="<?php echo $pic_t ?>"><p>
        </td>
</tr>


<tr>
        <td><b>Custom field #1</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c1" value="<?php echo $c1 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #2</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c2" value="<?php echo $c2 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #3</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c3" value="<?php echo $c3 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #4</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c4" value="<?php echo $c4 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #5</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c5" value="<?php echo $c5 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #6</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c6" value="<?php echo $c6 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #7</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c7" value="<?php echo $c7 ?>"><p>
        </td>
</tr>



<tr>
        <td><b>Custom field #8</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="c8" value="<?php echo $c8 ?>"><p>
        </td>
</tr>

<tr>
        <td><b>User-reg custom field #1</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="usr_1_t" value="<?php echo $usr_1_text ?>"><p>
        </td>
</tr>

<tr>
        <td><b>User-reg custom field #2</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="usr_2_t" value="<?php echo $usr_2_text ?>"><p>
        </td>
</tr>

<tr>
        <td><b>User-reg custom field #3</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="usr_3_t" value="<?php echo $usr_3_text ?>"><p>
        </td>
</tr>

<tr>
        <td><b>User-reg custom field #4</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="usr_4_t" value="<?php echo $usr_4_text ?>"><p>
        </td>
</tr>

<tr>
        <td><b>User-reg custom field #5</b><br />Custom fields lets you register additional fields in add_ad.php. Fields you type in will be added at detail.php in addition. <br />
                <input type="text" size="15" maxlength="15" name="usr_5_t" value="<?php echo $usr_5_text ?>"><p>
        </td>
</tr>

<tr>
        <td><b>Header</b><br />This header will apear on all pages. html is allowed. <br />
                <textarea rows="5" name="header_f" cols="70"><?php echo $header_file ?></textarea><p>
        </td>
</tr>

<tr>
        <td><b>Footer</b><br />This footer will apear on all pages. html is allowed.. <br />
                <textarea rows="5" name="footer_f" cols="70"><?php echo $footer_file ?></textarea><p>
        </td>
</tr>

</table>
<?
 if ($setup)
 {
        if (!$submit)
        {
?>
<input type="submit" class="txt" name="submit" value="Save">
<?
} }
else
{
?>
<input type="submit" class="txt" name="submit" value="Save">
<?
}
?>
</form>

<?
}
else
{

                if ($setup == 1)
                {
                          print("<a href='?level=4'>Next level</a>");
                }


}
if (!$setup)
{
                ?>
                 <p>
         </td>
</tr>
</table>
<!-- END Table menu -->
<?
require("admfooter.php");
}
?>
