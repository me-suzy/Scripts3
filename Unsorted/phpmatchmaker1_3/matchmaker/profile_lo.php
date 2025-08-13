<?
require_once("php_inc.php");
session_start();
include("header_inc.php");
db_connect();

if (session_is_registered("valid_user"))
{
 print "<form method=post action=profile_lo.php>";
 do_html_heading("Wish Partner");
 member_menu();
 
 if (!$submit)
 {
 	print "<p>Note:	In order to get a match, all fields below must be used.<br>";
 }

 if ($submit_form)
 {
 $lo_sex = trim($lo_sex);
 $lo_agefrom = trim($lo_agefrom);
 $lo_ageto = trim($lo_ageto);
 $lo_marital= trim($lo_marital);
 $lo_heightfrom = trim($lo_heightfrom);
 $lo_heightto = trim($lo_heightto);
 $lo_weightfrom = trim($lo_weightfrom);
 $lo_weightto = trim($lo_heightto);
 $lo_build = trim($lo_build);
 $lo_hair = trim($lo_hair);
 $lo_eye = trim($lo_eye);
 $lo_place = trim($lo_place);
 $lo_occupation = trim($lo_occupation);
 $lo_religion = trim($lo_religion);
 $lo_education = trim($lo_education);
 $lo_children = trim($lo_children);
 $about = $row[about];

 $sql_string = "update user set "  .
 "lo_sex = '$lo_sex', " .
 "lo_agefrom = '$lo_agefrom', " .
 "lo_ageto = '$lo_ageto', " .
 "lo_marital = '$lo_marital', " .
 "lo_build = '$lo_build', " .
 "lo_hair = '$lo_hair', " .
 "lo_heightfrom = '$lo_heightfrom', " .
 "lo_heightto = '$lo_heightto', " .
 "lo_weightfrom = '$lo_weightfrom',  " .
 "lo_weightto = '$lo_weightto',  " .
 "lo_place = '$lo_place',  " .
 "lo_religion = '$lo_religion',  " .
 "lo_children = '$lo_children'  " .
 " where username = '$valid_user'";

 $result = mysql_query($sql_string);
 
 print "<p><b>Saved</b><br>Your wish-profile is saved.";
 
 if ($debug)
 {
      print "$sql_string";
 }

 }
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
                print "<input type='radio' value='$options[$i]' name='lo_sex'";
                if (trim($lo_sex) == trim($options[$i])) { print " checked";  }
                print ">&nbsp;&nbsp;&nbsp;";
        }
        ?>
        </td></tr>

        <tr><td><b><? echo $la_age ?> from:</b><br></td>

        <?
        $options = file("optionfiles/age.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='lo_agefrom'>";
        print "<option value='$lo_agefrom' selected>$lo_agefrom";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_agefrom == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "</select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>

                <tr><td><b><? echo $la_age ?> to:</b><br></td>

        <?
        $options = file("optionfiles/age.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='lo_ageto'>";
        print "<option value='$lo_ageto' selected>$lo_ageto";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_ageto == $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "</select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr>



        <tr><td><b><? echo $la_height; ?> from:</b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='lo_heightfrom' value='$lo_heightfrom'  size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>

                <tr><td><b><? echo $la_height; ?> to:</b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='lo_heightto' value='$lo_heightto'  size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>

        <tr><td><b><? echo $la_weight; ?> from:</b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='lo_weightfrom' value='$lo_weightfrom' size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>

        <tr><td><b><? echo $la_weight; ?> to:</b><br></td>
        <?
        print "<td align=right>";
        print "<input type='text' name='lo_weightto' value='$lo_weightto' size='4' maxlength='3'>&nbsp;&nbsp;&nbsp;";
        ?>
        </td></tr>


                <tr><td><b><? echo $la_marrital ?></b><br></td>

        <?
        $options = file("optionfiles/marital.txt");
        $num_options =  count($options);

        print "<td align=right>";
        print "<select size='1' name='lo_marital'>";
        print "<option value='$lo_marital' selected>$lo_marital";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if (trim($lo_marital)== trim($options[$i]))
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
        print "<select size='1' name='lo_build'>";
        print "<option value='$lo_build' selected>$lo_build";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_build == $options[$i])
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
        print "<select size='1' name='lo_hair'>";
        print "<option value='$lo_hair' selected>$lo_hair";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_hair == $options[$i])
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
        print "<select size='1' name='lo_place'>";
        print "<option value='$lo_place' selected>$lo_place";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_place == $options[$i])
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
        print "<select size='1' name='lo_religion'>";
        print "<option value='$lo_religion' selected>$lo_religion";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_religion == $options[$i])
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
        print "<select size='1' name='lo_children'>";
        print "<option value='$lo_children' selected>$lo_children";
        for ($i=0; $i<$num_options; $i++)
        {
                print "<option value='$options[$i]' ";

                if ($lo_children== $options[$i])
                {
                        print "selected";
                }

                print ">$options[$i]</option>";
        }
        print "<option>--------------------------</option></select>&nbsp;&nbsp;&nbsp;";
        ?>

        </td></tr></table>
        <!-- // END interest form -->
<p> <input type='submit' value='Save' name='submit_form'></form>


<?

// ----- END OF CONTENT ----------- //
}
else
{
                         print "Session expired, please <a href='logon.php'>logon again</a>.";
                        exit;
}
include("footer_inc.php");
?>
