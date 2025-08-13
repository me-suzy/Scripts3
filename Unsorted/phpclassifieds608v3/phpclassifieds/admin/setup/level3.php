<form method="post" action"<? echo $PHP_SELF ?>">
<input type="hidden" name="level" value="3">

<b>FILE GENERATION</b><br />
<small>Here the install program will try to create som default files. <p>
<?


$gen = "$admindir/config/general.inc.php";
$opt = "$admindir/config/options.inc.php";
$hea = "$admindir/config/header.inc.php";
$foo = "$admindir/config/footer.inc.php";
$gad = "$admindir/config/globalad.inc.php";
$gus = "$admindir/config/globaluser.inc.php";
$mai = "$admindir/config/mail.inc.php";
$cre = "$admindir/config/credits.inc.php";

if (file_exists($gen))
{
    print "<br />The file general.inc.php already exists!";
    chmod ($gen, 0777);

}
if (file_exists($opt))
{
    print "<br />The file options.inc.php already exists!!";
    chmod ($opt, 0777);
}
if (file_exists($hea))
{
    print "<br />The file header.inc.php already exists!";
    chmod ($hea, 0777);
}
if (file_exists($foo))
{
    print "<br />The file footer.inc.php already exists!";
    chmod ($foo, 0777);
}
if (file_exists($gad))
{
    print "<br />The file globalad.inc.php already exists!";
    chmod ($gad, 0777);
}
if (file_exists($gus))
{
    print "<br />The file globaluser.inc.php already exists!";
    chmod ($gus, 0777);
}
if (file_exists($mai))
{
    print "<br />The file mail.inc.php already exists!";
    chmod ($mai, 0777);
}
if (file_exists($cre))
{
    print "<br />The file credits.inc.php already exists!";
    chmod ($mai, 0777);
}
?>
<p>
<u>Overwrite</u> if a file already exists: <input type="checkbox" name="overwrite" value="1">
</small><p>

<table>
<tr>
 <td><b>Full path</b>
 <br />The program needs to know the full path to the phpclassifieds main dir.
 Below is the scripts suggestion.<br />
 <input type="text" size="50" maxlength="140" name="full_path" value="<?php echo $dir ?>">
 <p></td>
</tr>
</table>

<input type="submit" class="txt" name="submit" value="Save">
</form>


<?
if ($submit)
{

 if ((file_exists($gen) AND $overwrite) OR (!file_exists($gen)))
 {
  $file_name = "$full_path/admin/config/general.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,"<?
  \$version=\"6.08\";\r
  \$language=\"eng.php\";\r
   \$full_path_to_public_program = \"$full_path\";\r
  \$usr_tbl = \"$usr_tbl\";\r
  \$ads_tbl = \"$ads_tbl\";\r
  \$cat_tbl = \"$cat_tbl\";\r
  \$pic_tbl = \"$pic_tbl\";\r
  require(\"$full_path/language/eng.php\");\r
  require(\"$full_path/admin/db.php\");\r ?>");
  fclose($file_pointer);
 }


 if ((file_exists($opt) AND $overwrite) OR (!file_exists($opt)))
 {
  $file_name = "$full_path/admin/config/options.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,"<? 
 \$advanced_delete_activated=\"1\";
 \$auto=\"1\";
 \$delete_members_activated=\"\";
 \$categories_per_column=\"4\";
 \$delete_after_x_days=\"100\";
 \$expire_days_option=\"\";
 \$latest=\"\";
 \$picture_upload_enable=\"1\";
 \$fileimg_upload=\"1\";
 \$piclimit=\"47000\";
 \$number_of_ads_per_page=\"20\";
 \$email_activated=\"1\";
 \$email_val=\"\";
 \$special_mode=\"\";
 \$xy=\"y\";
 \$validation=\"\";
 \$approve_mem=\"\";
 \$member_ab=\"3,5,8\";
 \$imagew=\"\";
 \$imageh=\"\";
 \$maxSize=\"200\";
 \$maxSize_gallery=\"70\";
 \$maxSize_large=\"500\";
 \$max_links=\"100\";
 \$list_img=\"\";
 \$nl2br=\"1\";
 \$credits_option=\"\";
 \$admin_new_ad_subject=\"New ad added\";
 \$admin_new_ad=\"New Classified added!  Go to http://{url}/detail.php?siteid={id} to check, or go to http://{url}/ads.php to delete/validate.\";  
 \$no_webmastermail=\"\";
 \$opt_verify=\"\";
 \$bans = \"test@test.com\";
 \$maxpic = \"4\";
 \$bad_words = \"\";
 \$magic= \"\";
 \$magic_path = \"convert\";
 \$magic_large_size = \"200x150\";
 \$magic_large_q= \"60\";
 \$magic_small_size = \"80x80\";
 \$magic_small_q= \"40\";
     ?>");
  fclose($file_pointer);
 }

 if ((file_exists($hea) AND $overwrite) OR (!file_exists($hea)))
 {
  $file_name = "$full_path/admin/config/header.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,'<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
     
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=x-user-defined" />
     
<title>DeltaScripts PHP Classifieds</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body bgcolor="#FFFFFF">');
  fclose($file_pointer);
 }


 if ((file_exists($foo) AND $overwrite) OR (!file_exists($foo)))
 {
  $file_name = "$full_path/admin/config/footer.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,'<!-- Footer: Table 1 Open -->
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="http://<? echo $url ?>/images/spacerbig.gif" width="1" height="1" alt="" /></td>
  </tr>
</table>
<!-- Footer: Table 1 Close -->

<!-- Footer: Table 2 Open -->
<table border="0" width="100%" cellspacing="0" cellpadding="10">
  <tr>
    <td width="100%"> 

<form action="search.php" method="post">
<!-- Footer: Table 2 Open -->  
<table cellspacing="0" border="0" width="100%">
    <tbody>
      <tr>
        <td>
<font color="#2F2C5B">
<? if ($show_bar) { print $la_s_bar; } ?></font>
        </td>
      </tr>
      <tr>
        <td>
<!-- Footer: Table 3 Open -->
          <table cellspacing="0" cellpadding="3" border="0" width="100%">
            <tbody>
              <tr>
                <td width="100%">
<!-- Footer: Table 4 Open -->
                  <table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
<!-- Footer: Table 5 Open -->
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><input name="searchword" size="20" style="FONT-SIZE: 8pt" />&nbsp;</td>
                            <td><input type="submit" value="<?echo $la_search?>" style="FONT-SIZE: 8pt" />&nbsp;</td>
                            <td>
                  <?echo $la_s_category?>&nbsp;</td>
                            <td>

                  <? include("list_hovedkat.php");
                  ?>&nbsp;</td>
                            <td>

                    <? echo $la_s_num_res ?>&nbsp;</td>
                            <td><select style="FONT-SIZE: 8pt" size="1" name="limit">
                    <option value="10" selected="selected">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                  </select>   &nbsp;   </td>
                            <td>   <? echo $la_s_num_res2 ?> </td>
                          </tr>
                        </table>
<!-- Footer: Table 5 Close -->
                      </td>
                      <td align="right">
<!-- Footer: Table 5 Open -->
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td></td>
                          </tr>
                        </table>
<!-- Footer: Table 5 Close -->
                      </td>
                    </tr>
                  </table>
<!-- Footer: Table 4 Close -->
      </td>
              </tr>
            </tbody>
          </table>
<!-- Footer: Table 3 Open -->
        </td>
      </tr>
    </tbody>
  </table>
<!-- Footer: Table 2 Close -->
</form>
<!-- // End search-->


    </td>
  </tr>
</table>
<!-- Footer: Table 1 Close -->
<!-- Footer: Table 1 Open -->
<table border="0" width="100%" bgcolor="#A9B8D1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" height="1"><img border="0" src="http://<? echo $url ?>/images/spacerbig.gif" width="1" height="1" alt="" /></td>
  </tr>
</table>
<!-- Footer: Table 1 Close -->
<p />
<center><a href="http://www.deltascripts.com" target="_blank"><img border="0" src="images/powered.gif" width="109" height="22" alt="" /></a></center>
</body></html>');

  fclose($file_pointer);
 }

 if ((file_exists($gad) AND $overwrite) OR (!file_exists($gad)))
 {
  $file_name = "$full_path/admin/config/globalad.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,"");
  fclose($file_pointer);
 }

 if ((file_exists($gus) AND $overwrite) OR (!file_exists($gus)))
 {
  $file_name = "$full_path/admin/config/globaluser.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,"");
  fclose($file_pointer);
 }
 
  if ((file_exists($cre) AND $overwrite) OR (!file_exists($cre)))
 {
  $file_name = "$full_path/admin/config/credits.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,"
1:Mini (1 ad)
5:Normal (5 ads)
10:Large (10 ads)
");
  fclose($file_pointer);
 }

 if ((file_exists($mai) AND $overwrite) OR (!file_exists($mai)))
 {
  $file_name = "$full_path/admin/config/mail.inc.php";
  $file_pointer = fopen($file_name, "w");
  fwrite($file_pointer,'<?$welcome_newu_msg="Hi {NAME} !
  
We have added you at http://{URL}. You can log into your control panel.
  
VITALE DATA
----------------------------------
Username: {EMAIL}
Password: {PASSWD}
  
Log in at: http://{URL}
----------------------------------
Thanks for the visit!
  
Regards,
{SITENAME}";
  $welcome_newu_ttl="User added";
  $val_msg="Hi {EMAIL} !
Your ad submitted to us, has now been approved. 
You can see this ad at http://{URL}/detail.php?siteid={AD_ID}.

Regards,
{SITENAME}  ";
  $val_ttl="Ad approved !"; 
  $val_user_title="Hi {NAME} !"; 
  $val_user_msg="Before you can use the Control Panel you 
need to verify your registration by clicking the link
below:

http://{URL}/verify.php?user_email={EMAIL}&verify={VERIFY}&name={NAME}&passwd={PASSWD}

(if you cannot click the link please copy and paste into 
your browser address bar, be sure to leave no spaces)"; 
  $warn_ttl="Hi {EMAIL} !"; 
  $warn_msg="You ad with title {ADTITLE} is about to expire within 7 days. 
If you would like to extend your ad at http://{URL}/detail.php?siteid={AD_ID}, 
you will need to log into your control panel and change expire date on this ad.

Regards,
{SITENAME}";
  $approve_user_title="Subscription approved!";
  $approve_user_msg="You can now log in with username/password as given in earlier mail.";
  $sub_title="Subscription approved";
  $sub_msg="You may now login. If you need to change your subscription, please do this before it expires, or else you will have to email support and ask for reopening.";

?>');
  fclose($file_pointer);
   }
 
chmod ($gen, 0777);
chmod ($opt, 0777);
chmod ($hea, 0777);
chmod ($foo, 0777);
chmod ($gad, 0777);
chmod ($gus, 0777);
chmod ($mai, 0777);


         print "<p><b>Files are generated</b>";
         print "<br />You may continue to <a href='install.php?level=4'>next step</a>.";

        }
?>





