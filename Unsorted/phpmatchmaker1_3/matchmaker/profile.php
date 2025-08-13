<?
require_once("php_inc.php");
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 print "<form method=post action=profile.php>";
 do_html_heading("Your Profile");
 member_menu();

 if (!$submit)
 {
 	print "<p>Note:	In order to get a match, all fields below must be used.<br>";
 }
 
 
 if ($submit_form)
 {
 $sex = trim($sex);
 $age = trim($age);
 $marital= trim($marital);
 $height = trim($height);
 $weight = trim($weight);
 $build = trim($build);
 $hair = trim($hair);
 $eye = trim($eye);
 $place = trim($place);
 $occupation = trim($occupation);
 $religion = trim($religion);
 $education = trim($education);
 $children = trim($children);
 $about = $row[about];
 $sql_string = "update user set "  .
 "sex = '$sex', " .
 "age = '$age', " .
 "marital = '$marital', " .
 "build = '$build', " .
 "hair = '$hair', " .
 "height = '$height', " .
 "weight = '$weight',  " .
 "eye = '$eye',  " .
 "place = '$place',  " .
 "occupation = '$occupation',  " .
 "religion = '$religion',  " .
 "about = '$about_me',  " .
 "children = '$children',  " .
 "i1 = '$i1',  " .
 "i2 = '$i2',  " .
 "i3 = '$i3',  " .
 "i4 = '$i4',  " .
 "i5 = '$i5',  " .
 "i6 = '$i6',  " .
 "i7 = '$i7',  " .
 "i8 = '$i8',  " .
 "i9 = '$i9',  " .
 "i10 = '$i10',  " .
 "i11 = '$i11',  " .
 "i12 = '$i12',  " .
 "i13 = '$i13',  " .
 "i14 = '$i14',  " .
 "i15 = '$i15',  " .
 "i16 = '$i16',  " .
 "i17 = '$i17',  " .
 "i18 = '$i18',  " .
 "i19 = '$i19',  " .
 "i20 = '$i20',  " .
 "education = '$education'  " .
 " where username = '$valid_user'";
 $result = mysql_query($sql_string);
 
 print "<b>Saved</b><br>Your profile is saved.";

 }
 $result = mysql_query("select * from user where username = '$valid_user'");
 $row = mysql_fetch_array($result);
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



?> <p>

 <table border="0" cellpadding="2" cellspacing="2">
        <tr><td><b><? echo $la_gender ?></b><br></td>
        <?
        $options = file("optionfiles/sex.txt");
        $num_options =  count($options);

        print "<td align=right>";
        for ($i=0; $i<$num_options; $i++)
        {

                print $options[$i];
                print "<input type='radio' value='$options[$i]' name='sex'";
                if (trim($sex) == trim($options[$i])) { print " checked";  }
                print ">&nbsp;&nbsp;&nbsp;";
        }
        ?>
        </td></tr>

        <tr><td><b><? echo $la_age ?></b><br></td>

        <?
        $options = file("optionfiles/age.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='age'>";
        print "<option value='$age' selected>$age";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($age == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "</select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

        <tr><td><b><? echo $la_height; ?></b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='height' value='$height'  size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>

        <tr><td><b><? echo $la_weight; ?></b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='weight' value='$weight' size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>

                <tr><td><b><? echo $la_marrital ?></b><br></td>

        <?
        $options = file("optionfiles/marital.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='marital'>";
        print "<option value='$marital' selected>$marital";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if (trim($marital)== trim($options[$i]))
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>



        <tr><td><b><? echo $la_build ?></b><br></td>

        <?
        $options = file("optionfiles/build.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='build'>";
        print "<option value='$build' selected>$build";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($build == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

                <tr><td><b><? echo $la_hair ?></b><br></td>

        <?
        $options = file("optionfiles/haircolor.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='hair'>";
        print "<option value='$hair' selected>$hair";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($hair == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>


                <tr><td><b><? echo $la_eye ?></b><br></td>

        <?
        $options = file("optionfiles/eyecolor.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='eye'>";
        print "<option value='$eye' selected>$eye";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($eye == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

                        <tr><td><b><? echo $la_place ?></b><br></td>

        <?
        $options = file("optionfiles/place.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='place'>";
        print "<option value='$place' selected>$place";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($place == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

        <tr><td><b><? echo $la_occ ?></b><br></td>
        <?
        $options = file("optionfiles/occupation.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='occupation'>";
        print "<option value='$occupation' selected>$occupation";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($occupation == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>



                <tr><td><b><? echo $la_relig ?></b><br></td>
        <?
        $options = file("optionfiles/religion.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='religion'>";
        print "<option value='$religion' selected>$religion";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($religion == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>


                        <tr><td><b><? echo $la_edu ?></b><br></td>
        <?
        $options = file("optionfiles/education.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='education'>";
        print "<option value='$education' selected>$education";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($education== $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

                                <tr><td><b><? echo $la_children ?></b><br></td>
        <?
        $options = file("optionfiles/children.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='children'>";
        print "<option value='$children' selected>$children";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($children== $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>


<tr><td valign=top><b><? echo $la_about_you ?></b><br></td><td align=right>
<textarea rows='4' name='about_me' cols='30'><? print $about; ?></textarea>&nbsp;&nbsp;&nbsp;</td></tr></table>
<table>
                         <tr>
                         <td><b>Interest form</b><br></td>

                         <td align=right>
                         <!-- Interest form -->
                         <td width="100" bgcolor="#FFFFE8" valign="top">
                                          <table border="2" cellpadding="0" cellspacing="0" bgcolor="#EFEFEF" width="364">

<?

for ($counter=1; $counter<$num_interestfields; $counter++)
{
 $fieldname = "i" . $counter;
 $val = 0;
 switch ($counter)
 {
         case 1 :
        $tmp_fieldname = $i1_text;
        $val = $i1;
        break;
        case 2:
        $tmp_fieldname = $i2_text;
        $val = $i2;
        break;
        case 3:
        $tmp_fieldname = $i3_text;
        $val = $i3;
        break;
        case 4:
        $tmp_fieldname = $i4_text;
        $val = $i4;
        break;
        case 5:
        $tmp_fieldname = $i5_text;
        $val = $i5;
        break;
        case 6:
        $tmp_fieldname = $i6_text;
        $val = $i6;
        break;
        case 7:
        $tmp_fieldname = $i7_text;
        $val = $i7;
        break;
        case 8:
        $tmp_fieldname = $i8_text;
        $val = $i8;
        break;
        case 9:
        $tmp_fieldname = $i9_text;
        $val = $i9;
        break;
        case 10:
        $tmp_fieldname = $i10_text;
        $val = $i10;
        break;
        case 11:
        $tmp_fieldname = $i11_text;
        $val = $i11;
        break;
        case 12:
        $tmp_fieldname = $i12_text;
        $val = $i12;
        break;
        case 13:
        $tmp_fieldname = $i13_text;
        $val = $i13;
        break;
        case 14:
        $tmp_fieldname = $i14_text;
        $val = $i14;
        break;
        case 15:
        $tmp_fieldname = $i15_text;
        $val = $i5;
        break;
        case 16:
        $tmp_fieldname = $i16_text;
        $val = $i16;
        break;
                case 17:
        $tmp_fieldname = $i17_text;
        $val = $i17;
        break;
                case 18:
        $tmp_fieldname = $i18_text;
        $val = $i18;
        break;
                case 18:
        $tmp_fieldname = $i18_text;
        $val = $i18;
        break;
                case 19:
        $tmp_fieldname = $i19_text;
        $val = $i19;
        break;
                        case 20:
        $tmp_fieldname = $i20_text;
        $val = $i20;
        break;
 }
 echo "<tr><td width='100' bgcolor='#EFEFEF'>";
 echo "<font size='2' face='Verdana'>&nbsp $tmp_fieldname</font></td>";
 echo "<td width='161' bgcolor='#EFEFEF' align='right'>";
 echo "Yes <input type='checkbox' name='$fieldname' value='1'";
 if ($val == 1) print ("checked");
 echo ">";
 echo "OK <input type='checkbox' name='$fieldname' value='2'";
 if ($val == 2) print ("checked");
 echo ">";
 echo "No <input type='checkbox' name='$fieldname' value='3'";
 if ($val == 3) print ("checked");
 echo ">";
 echo "</td></tr>";
}

?>
                                                                         </table>
                                                                         </td>
                                                                         </tr>
                                                                         </table>

<!-- // END interest form -->
<p> <input type='submit' value='Save info' name='submit_form'></form>
<?




// ----- END OF CONTENT ----------- //
}
else
{
                         print "Session expired, please logon again.";
                        exit;
}
include("footer_inc.php");
?>
