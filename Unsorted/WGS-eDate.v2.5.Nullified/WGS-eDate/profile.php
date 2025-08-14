<?
include "member.php";
if ($save)
{
q("update profiles set yahoo='$yahoo', msn='$msn', icq='$icq', aol='$aol', birthdate='$birthdate', sex='$sex', likes='$likes', maritalstatus='$maritalstatus', height='$height', weight='$weight', skin='$skin', eyes='$eyes', hair='$hair', languages='$languages', details='$details', occupation='$occupation', ethnicity='$ethnicity' where id='$auth'");
};

$p=f(q("select * from profiles where id='$auth'"));
?> 
<form name="fo
 rm1" method="post" action="profile.php">
  <blockquote> 
    <p>&nbsp;</p>
    <p>Please provide only details you want the others to know about you. The 
      more details you post, the more you will be listed in members searches. 
      <br>Special members will be able to see you web idetity and contact you.</p>
  </blockquote>
  <table width="600" border="0" cellpadding="1" cellspacing="1" align="center">
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD colspan="2" bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Edit 
        Profile &gt;&gt;</font></strong></TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD colspan="2"><div align="center"><strong>Web identity</strong></div></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Yahoo messenger username</TD>
      <TD><input name="yahoo" type="text" id="yahoo" size="30" value="<?php echo $p[yahoo]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>MSN messenger email</TD>
      <TD><input name="msn" type="text" id="msn" size="30"  value="<?php echo $p[msn]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>AOL messenger username</TD>
      <TD><input name="aol" type="text" id="aol" size="30" value="<?php echo $p[aol]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>ICQ number</TD>
      <TD><input name="icq" type="text" id="icq" size="30" value="<?php echo $p[icq]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD colspan="2"><div align="center"><strong>Personal details</strong></div></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Birthdate<BR>
        (yyyy-mm-dd)</TD>
      <TD><INPUT SIZE=10 NAME='birthdate' VALUE='<?php echo $p[birthdate]; ?>'></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr0'> 
      <TD>Sex</TD>
      <TD><select size=1 NAME='sex'>
          <option></option>
          <option <?php if ($p[sex]=="Male") echo "selected"; ?>>Male</option>
          <option <?php if ($p[sex]=="Female") echo "selected"; ?>>Female</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>I like</TD>
      <TD><select size=1 NAME='likes'>
          <option></option>
          <option <?php if ($p[likes]=="Males") echo "selected"; ?>>Males</option>
          <option <?php if ($p[likes]=="Females") echo "selected"; ?>>Females</option>
          <option <?php if ($p[likes]=="Both") echo "selected"; ?>>Both</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Occupation</TD>
      <TD><input name="occupation" type="text" id="occupation" size="30" value="<?php echo $p[occupation]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Marital Status</TD>
      <TD><select size=1 NAME='maritalstatus'>
          <option></option>
          <option <?php if ($p[maritalstatus]=="Single") echo "selected"; ?>>Single</option>
          <option <?php if ($p[maritalstatus]=="Married") echo "selected"; ?>>Married</option>
          <option <?php if ($p[maritalstatus]=="Divorced") echo "selected"; ?>>Divorced</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Height</TD>
      <TD><INPUT SIZE=8 NAME='height' VALUE='<?php echo $p[height]; ?>'></INPUT> 
        m </TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr0'> 
      <TD>Weight</TD>
      <TD><INPUT SIZE=8 NAME='weight' VALUE='<?php echo $p[weight]; ?>'></INPUT> 
        kg </TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Skin</TD>
      <TD><select size=1 NAME='skin'>
          <option></option>
          <option <?php if ($p[skin]=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($p[skin]=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($p[skin]=="Red") echo "selected"; ?>>Red</option>
          <option <?php if ($p[skin]=="White") echo "selected"; ?>>White</option>
          <option <?php if ($p[skin]=="Yellow") echo "selected"; ?>>Yellow</option>
          <option <?php if ($p[skin]=="Pink") echo "selected"; ?>>Pink</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Ethnicity</TD>
      <TD><input name="ethnicity" type="text" id="ethnicity" size="30" value="<?php echo $p[ethnicity]; ?>"></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr0'> 
      <TD>Eyes</TD>
      <TD><select size=1 NAME='eyes'>
          <option></option>
          <option <?php if ($p[eyes]=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($p[eyes]=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($p[eyes]=="Green") echo "selected"; ?>>Green</option>
          <option <?php if ($p[eyes]=="Blue") echo "selected"; ?>>Blue</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Hair</TD>
      <TD><select size=1 NAME='hair'>
          <option></option>
          <option <?php if ($p[hair]=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($p[hair]=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($p[hair]=="Red") echo "selected"; ?>>Red</option>
          <option <?php if ($p[hair]=="Blonde") echo "selected"; ?>>Blonde</option>
          <option <?php if ($p[hair]=="Blue") echo "selected"; ?>>Blue</option>
          <option <?php if ($p[hair]=="Green") echo "selected"; ?>>Green</option>
          <option <?php if ($p[hair]=="None") echo "selected"; ?>>None</option>
        </select></TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr0'> 
      <TD>Languages Spoken</TD>
      <TD><INPUT SIZE=60 NAME='languages' VALUE='<?php echo $p[languages]; ?>'></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#F0F0F0" class='tr1'> 
      <TD>Details about me</TD>
      <TD><TEXTAREA rows='4' wrap='PHYSICAL' cols=60 NAME='details'><?php echo $p[details]; ?></TEXTAREA></TD>
    </TR>
    <tr bgcolor="#F0F0F0"> 
      <td colspan="2"><div align="center"> 
          <input name="save" type="hidden" id="save" value="1">
          <input type="reset" name="Reset" value="Reset">
          <input type="submit" name="Submit2" value="Save">
        </div></td>
    </tr>
  </table>
</form>
<?
include "_footer.php";
?>
