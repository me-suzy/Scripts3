<?
require_once("php_inc.php");
session_start();
include("header_inc.php");
do_html_heading("Profile for $profile");


if (session_is_registered("valid_user"))
{
	member_menu();
}
else 
{
	print "You must be a registered user in order to use the contact form, or to add to your favorites!";	
	print "<br><a href='register.php'>Register today</a>";
}
$listmode = 1;

db_connect();
$result = mysql_query("select * from user where username = '$profile'");
 $row = mysql_fetch_array($result);
 $username = $row[username];
 $sex = $row[sex];
 $hits = $row[hits];
 $age = $row[age];
 $marital= $row[marital];
 $height = $row[height];
 $weight = $row[weight];
 $build = $row[build];
 $hair = $row[hair];
 $eye = $row[eye];
 $place = $row[place];
 $occupation = $row[occupation];
 $religion = $row[religion];
 $education = $row[education];
 $children = $row[children];
 $about = $row[about];
 $lastlogin = $row[lastlogin];
 $image = $row[image];
 $about = $row[about];
 $i1 = $row[i1];
 $i2 = $row[i2];
 $i3 = $row[i3];
 $i4 = $row[i4];
 $i5 = $row[i5];
 $i6 = $row[i6];
 $i7 = $row[i7];
 $i8 = $row[i8];
 $i9 = $row[i9];
 $i10 = $row[i10];
 $i11 = $row[i11];
 $i12 = $row[i12];
 $i13 = $row[i13];
 $i14 = $row[i14];
 $i15 = $row[i15];
 $i16 = $row[i16];
 $i17 = $row[i17];
 $i18 = $row[i18];
 $i19 = $row[i19];
 $i20 = $row[i20];

 $lo_sex = $row[lo_sex];
 $lo_agefrom = $row[lo_agefrom];
 $lo_ageto = $row[lo_ageto];
 $lo_marital= $row[lo_marital];
 $lo_build = $row[lo_build];
 $lo_heightfrom = $row[lo_heightfrom];
 $lo_heightto = $row[lo_heightto];
 $lo_weightfrom = $row[lo_weightfrom];
 $lo_weightto = $row[lo_weightto];
 $lo_hair = $row[lo_hair];
 $lo_place = $row[lo_place];
 $lo_religion = $row[lo_religion];
 $lo_children = $row[lo_children];

 $lastlogin = $row[lastlogin];
 $year = substr($lastlogin,0,4);
 $month = substr($lastlogin,4,2);
 $day = substr($lastlogin,6,2);
 $formatted_login_date = "$year-$month-$day";


?>

<p><font face="Arial" size="2">

<?
if (session_is_registered("valid_user"))
{
	print "<font size='2' face='Arial'><a href='send.php?profile=$profile'>Contact $profile</a></font><br>";
	print "<font size='2' face='Arial'><a href='favorites.php?profile=$profile'>Add $profile to favorites</a></font><br>";
}

?>
<p>
<table><tr><td>
<table border="0" cellpadding="0" cellspacing="0" width="450">
  <tr>
    <td valign="top" colspan="6" bgcolor="#E8E8E8"><font size="2" face="Arial">&nbsp;Profile
      for <b><? echo $username ?>&nbsp;&nbsp;&nbsp; </b>Last login: <? echo $formatted_login_date ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </i>Viewed:<i> <? echo $hits ?></font></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_gender ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $sex ?></font></b></td>
    <td valign="top" rowspan="10"><font size="2" face="Arial">
                <?
				if ($image AND $set_magic)
                {
                	print "<img border='0' src='upload_images/$image' align='right'>";
                }
                elseif ($image)
                {
                		print "<img border='0' src='upload_images/$image' width='83' align='right'>";
                }
                
                else
                {
                    print "<img border='0' src='upload_images/noimage.gif' width='83' align='right'>";
                }




                ?>

                </font></td>
  </tr>

  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_age ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $age ?></font></b></td>
  </tr>


  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_height ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $height ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_build ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $build ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_place ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $place ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_marrital ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $marital ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_children ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $children?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_hair ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $hair ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_eye ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $eye ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_occ ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $occupation ?></font></b></td>
  </tr>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_relig ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $religion ?></font></b></td>
  </tr>
  <tr>
    <td valign="top" colspan="6"><font face="Arial" size="2"><br>
     <? echo $about ?></font></td>
  </tr>
  <tr>
    <td valign="top" colspan="6"><br>
    </td>
  </tr>

        <tr><td>&nbsp;</td>
</tr>
  <tr>
    <td valign="top" colspan="2" bgcolor="#DADADA" width="30%"><font face="Arial" size="2">Yes</font></td>
    <td valign="top" colspan="2" bgcolor="#DADADA" width="30%"><font face="Arial" size="2">OK</font></td>
    <td valign="top" colspan="2" bgcolor="#DADADA" width="30%"><font face="Arial" size="2">No</font></td>
  </tr>
  <tr>
                        <td valign="top" colspan="2"><font face="Arial" size="2"><?
$numb = 1;
if ($i1 == $numb) print("$i1_text<br>");
if ($i2 == $numb) print("$i2_text<br>");
if ($i3 == $numb) print("$i3_text<br>");
if ($i4 == $numb) print("$i4_text<br>");
if ($i5 == $numb) print("$i5_text<br>");
if ($i6 == $numb) print("$i6_text<br>");
if ($i7 == $numb) print("$i7_text<br>");
if ($i8 == $numb) print("$i8_text<br>");
if ($i9 == $numb) print("$i9_text<br>");
if ($i10 == $numb) print("$i10_text<br>");
if ($i11 == $numb) print("$i11_text<br>");
if ($i12 == $numb) print("$i12_text<br>");
if ($i13 == $numb) print("$i13_text<br>");
if ($i14 == $numb) print("$i14_text<br>");
if ($i15 == $numb) print("$i15_text<br>");
if ($i16 == $numb) print("$i16_text<br>");
if ($i17 == $numb) print("$i17_text<br>");
if ($i18 == $numb) print("$i18_text<br>");
if ($i19 == $numb) print("$i19_text<br>");
if ($i20 == $numb) print("$i20_text<br>");

 ?></font></td>
                        <td valign="top" colspan="2"><font face="Arial" size="2"><?
$numb = 2;
if ($i1 == $numb) print("$i1_text<br>");
if ($i2 == $numb) print("$i2_text<br>");
if ($i3 == $numb) print("$i3_text<br>");
if ($i4 == $numb) print("$i4_text<br>");
if ($i5 == $numb) print("$i5_text<br>");
if ($i6 == $numb) print("$i6_text<br>");
if ($i7 == $numb) print("$i7_text<br>");
if ($i8 == $numb) print("$i8_text<br>");
if ($i9 == $numb) print("$i9_text<br>");
if ($i10 == $numb) print("$i10_text<br>");
if ($i11 == $numb) print("$i11_text<br>");
if ($i12 == $numb) print("$i12_text<br>");
if ($i13 == $numb) print("$i13_text<br>");
if ($i14 == $numb) print("$i14_text<br>");
if ($i15 == $numb) print("$i15_text<br>");
if ($i16 == $numb) print("$i16_text<br>");
if ($i17 == $numb) print("$i17_text<br>");
if ($i18 == $numb) print("$i18_text<br>");
if ($i19 == $numb) print("$i19_text<br>");
if ($i20 == $numb) print("$i20_text<br>");

 ?></font></td>
                        <td valign="top" colspan="2"><font face="Arial" size="2"><?
$numb = 3;
if ($i1 == $numb) print("$i1_text<br>");
if ($i2 == $numb) print("$i2_text<br>");
if ($i3 == $numb) print("$i3_text<br>");
if ($i4 == $numb) print("$i4_text<br>");
if ($i5 == $numb) print("$i5_text<br>");
if ($i6 == $numb) print("$i6_text<br>");
if ($i7 == $numb) print("$i7_text<br>");
if ($i8 == $numb) print("$i8_text<br>");
if ($i9 == $numb) print("$i9_text<br>");
if ($i10 == $numb) print("$i10_text<br>");
if ($i11 == $numb) print("$i11_text<br>");
if ($i12 == $numb) print("$i12_text<br>");
if ($i13 == $numb) print("$i13_text<br>");
if ($i14 == $numb) print("$i14_text<br>");
if ($i15 == $numb) print("$i15_text<br>");
if ($i16 == $numb) print("$i16_text<br>");
if ($i17 == $numb) print("$i17_text<br>");
if ($i18 == $numb) print("$i18_text<br>");
if ($i19 == $numb) print("$i19_text<br>");
if ($i20 == $numb) print("$i20_text<br>");

 ?></font></td>
        </tr>
<tr><td>&nbsp;</td>
</tr><tr>
<td valign="top" colspan="6" bgcolor="#E8E8E8"><font size="2" face="Arial">&nbsp;Looking for ...</font></td>
  </tr>

<? if ($lo_sex) { ?>
        <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_gender ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_sex ?></font></b></td>
    <td valign="top" rowspan="10"><font size="2" face="Arial"></td>
  </tr>
<? } ?>


<? if ($lo_agefrom AND $lo_ageto) { ?>
          <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_age ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? print "$lo_agefrom - $lo_ageto"; ?></font></b></td>
    <td valign="top" rowspan="10"><font size="2" face="Arial"></td>
  </tr>
<? } ?>

<? if ($lo_heightfrom AND $lo_heightto) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_height ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? print "$lo_heightfrom - $lo_heightto"; ?></font></b></td>
  </tr>
<? } ?>


<? if ($lo_weightfrom AND $lo_weightto) { ?>
          <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_weight ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? print "$lo_weightfrom - $lo_weightto"; ?></font></b></td>
  </tr>
<? } ?>


<? if ($lo_build) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_build ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_build ?></font></b></td>
  </tr>
<? } ?>


<? if ($lo_place) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_place ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_place ?></font></b></td>
  </tr>
<? } ?>

<? if ($lo_marital) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_marrital ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_marital ?></font></b></td>
  </tr>
<? } ?>

<? if ($lo_children) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_children ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_children?></font></b></td>
  </tr>
<? } ?>


<? if ($lo_hair) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_hair ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_hair ?></font></b></td>
  </tr>
<? } ?>


<? if ($lo_religion) { ?>
  <tr>
    <td valign="top"><font size="2" face="Arial"><? echo $la_relig ?></font></td>
    <td valign="top" width="12" colspan="2"></td>
    <td valign="top" colspan="2"><b><font size="2" face="Arial"><? echo $lo_religion ?></font></b></td>
  </tr>
<? } ?>

</table>
<p>&nbsp;</p>

<?
$hits = $hits + 1;
$result = mysql_query("update user set hits = $hits where username = '$profile'");
if ($valid_user)
{
 $result = mysql_query("insert into visitors (visitor,owner) values ('$valid_user', '$profile')");
}
?>

</td><td valign=top>
<td valign=top><? include("search_result.php"); ?></td></tr></table>
<?
print "<p>";
include("footer_inc.php");
?>
