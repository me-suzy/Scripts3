<?
// include function files for this application
require_once("php_inc.php");
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 print "<form method=post action=profile.php>";
 do_html_heading("Matching . . .");
 member_menu();

 $result = mysql_query("select * from user where username = '$valid_user'");
 $row = mysql_fetch_array($result);

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


 if ($lo_sex AND $lo_place AND $lo_agefrom AND $lo_ageto AND $lo_heightfrom AND $lo_heightto AND $lo_weightfrom AND $lo_weightto AND $lo_build AND $lo_marital AND $lo_children AND $lo_religion AND $lo_hair)
 {
        $string = mysql_query("select * from user where sex like '$lo_sex%' AND place like '$lo_place%' AND (age between $lo_agefrom AND $lo_ageto) AND (height between $lo_heightfrom AND $lo_heightto) AND (weight between $lo_weightfrom AND $lo_weightto) AND build like '$lo_build%' AND marital like '$lo_marital%' AND children like '$lo_children%' AND religion like '$lo_religion%' AND hair like '$lo_hair'");
        $numrows=mysql_num_rows($string);

        if (empty($offset))
        {
    		$offset=0;
   		}
        $limit=20;
        $string = "select * from user where sex like '$lo_sex%' AND place like '$lo_place%' AND (age between $lo_agefrom AND $lo_ageto) AND (height between $lo_heightfrom AND $lo_heightto) AND (weight between $lo_weightfrom AND $lo_weightto) AND build like '$lo_build%' AND marital like '$lo_marital%' AND children like '$lo_children%' AND religion like '$lo_religion%' AND hair like '$lo_hair' limit $offset,$limit";
   		$result=mysql_query($string);
        

print "<p><table width='100%'>";
print "<tr><td bgcolor='#D8D4D8'>$la_gender</td><td bgcolor='#D8D4D8'>$la_age</td><td bgcolor='#D8D4D8'>Username</td>";
print "<td bgcolor='#D8D4D8'>$la_place</td><td bgcolor='#D8D4D8'>Points</td></tr>";
while ($row=mysql_fetch_array($result)) {

 $username = $row[username];
 $sex = $row[sex];
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
 $lo_i1 = $row[i1];
 $lo_i2 = $row[i2];
 $lo_i3 = $row[i3];
 $lo_i4 = $row[i4];
 $lo_i5 = $row[i5];
 $lo_i6 = $row[i6];
 $lo_i7 = $row[i7];
 $lo_i8 = $row[i8];
 $lo_i9 = $row[i9];
 $lo_i10 = $row[i10];
 $lo_i11 = $row[i11];
 $lo_i12 = $row[i12];
 $lo_i13 = $row[i13];
 $lo_i14 = $row[i14];
 $lo_i15 = $row[i15];
 $lo_i16 = $row[i16];
 $lo_i17 = $row[i17];
 $lo_i18 = $row[i18];
 $lo_i19 = $row[i19];
 $lo_i20 = $row[i20];

 if ($sex == 'Male')
 {
         $s = 'M';
 }
 elseif ($sex == 'Female')
 {
         $s = 'F';
 }
 elseif ($sex == 'Couple')
 {
          $s = 'C';
 }
 else
 {
          $s = 'U';
 }



 $points = 0;
 if ($i1 == $lo_i1)
 {
                 $points++;
 }
 if ($i2 == $lo_i2)
 {
                 $points++;
 }
 if ($i3 == $lo_i3)
 {
                 $points++;
 }
 if ($i4 == $lo_i4)
 {
                 $points++;
 }
 if ($i5 == $lo_i5)
 {
                 $points++;
 }
 if ($i6 == $lo_i6)
 {
                 $points++;
 }
 if ($i7 == $lo_i7)
 {
                 $points++;
 }
 if ($i8 == $lo_i8)
 {
                 $points++;
 }
 if ($i9 == $lo_i9)
 {
                 $points++;
 }
 if ($i10 == $lo_i10)
 {
                 $points++;
 }
 if ($i11 == $lo_i11)
 {
                 $points++;
 }
 if ($i12 == $lo_i12)
 {
                 $points++;
 }
 if ($i13 == $lo_i13)
 {
                 $points++;
 }
 if ($i14 == $lo_i14)
 {
                 $points++;
 }
 if ($i15 == $lo_i15)
 {
                 $points++;
 }
 if ($i16 == $lo_i16)
 {
                 $points++;
 }
 if ($i17 == $lo_i17)
 {
                 $points++;

 }
 if ($i18 == $lo_i18)
 {
                 $points++;

 }
 if ($i19 == $lo_i19)
 {
                 $points++;

 }
 if ($i20 == $lo_i20)
 {
                 $points++;

        }

$num_match = 0;
$match_check = "select * from  matchprofiles where (username_p1 = '$valid_user' AND username_p2 = '$username') or (username_p2 = '$valid_user' AND username_p1 = '$username')";
$result_match=mysql_query($match_check);
$num_match=mysql_num_rows($result_match);

if ($num_match < 1)
{
$string_match= "insert into matchprofiles (username_p1, username_p2, score) values ('$valid_user', '$username', '$points')";
$match_insert=mysql_query($string_match);
}


 print "<tr>";
 print "<td valign=top><font face=arial size=2>$sex</font></td><td><font face=arial size=2>$age</font></td><td><font face=arial size=2><a href='detail.php?profile=$username'>$username</a></font></td><td><font face=arial size=2>$place</font></td><td><font face=arial size=2>$points</font></td>";
 print "</tr>";
}
print "</table><br>";

if ($offset==1) { // bypass PREV link if offset is 0
    $prevoffset=$offset-20;
    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$prevoffset&profile=$profile&sex_s=$sex_s&age_from=$age_from&age_to=$age_to&place_s=$place_s&search=1\">PREV</a> </font>\n";
}

// calculate number of pages needing links
$pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division
if ($numrows%$limit) {
    // has remainder so add one page
    $pages++;
}

for ($i=1;$i<=$pages;$i++) { // loop thru
    $newoffset=$limit*($i-1);
    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$newoffset&profile=$profile&sex_s=$sex_s&age_from=$age_from&age_to=$age_to&place_s=$place_s&search=1\">$i</a> </font>\n";
}

// check to see if last page
if (!(($offset/$limit)==$pages) && $pages!=1) {
    // not last page so give NEXT link
    $newoffset=$offset+$limit;
    print "<font face=arial size=1><a href=\"$PHP_SELF?offset=$newoffset&profile=$profile&sex_s=$sex_s&age_from=$age_from&age_to=$age_to&place_s=$place_s&search=1\">NEXT</a></font><p>\n";
}

// END if not filled out wish profile

if ($numrows < 1)
{
 print "No matches at this time, sorry. ";
 print "Try changing your settings in your <a href='profile_lo.php'>Wishlist</a> ?";
}




}
else
{
 print "<p>In order to create a list of match, you must fill out <u>ALL fields</u> at ";
 print "<a href='profile_lo.php'>this wish form</a>.";
}




// ----- END OF CONTENT ----------- //
}
else
{
                        print "Session expired, please logon again.";
                        exit;
}
print "<p>";
if ($debug)
{
 print "<hr>$string<hr>";
}
include("footer_inc.php");
?>                                                                
