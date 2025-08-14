<?
include "member.php";
$p=f(q("select * from profiles where id='$mid'"));
$m=f(q("select * from members where id='$mid'"));

$r=q("select picture, details from pictures where member='$mid' and type='Main'");
if (!e($r))
{
	$pic=f($r);
    echo "<br><center><IMG src='".piurl($pic[picture])."' border=1 alt=\"$pic[details]\"></center><br>";
};
echo "<center><br>[<a href='messages.php?reply=1&to=$m[login]&subject=Hi, $m[login]!&message=...'> Message member </a>]</center>";

if (!$tm0web)
{
 $p[yahoo]=$p[msn]=$p[icq]=$p[aol]="Rank upgrade required.";
};

?>

  <table width="600" border="0" cellpadding="1" cellspacing="1" align="center">
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD colspan="2" bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Member 
        Details &gt;&gt;</font></strong></TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>First Name</TD>
      <TD><input name="fname" type="text" id="fname" value="<?php echo $m[fname]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Last Name</TD>
      <TD><input name="lname" type="text" id="lname" value="<?php echo $m[lname]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Country</TD>
      <TD><input name="country" type="text" id="country" value="<?php echo $m[country]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>State</TD>
      <TD><input name="state" type="text" id="state" value="<?php echo $m[state]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>City</TD>
      <TD><input name="city" type="text" id="city" value="<?php echo $m[city]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Yahoo messenger username</TD>
      <TD><input name="yahoo" type="text" id="yahoo" value="<?php echo $p[yahoo]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>MSN messenger email</TD>
      <TD><input name="msn" type="text" id="msn"  value="<?php echo $p[msn]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>AOL messenger username</TD>
      <TD><input name="aol" type="text" id="aol" value="<?php echo $p[aol]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>ICQ number</TD>
      <TD><input name="icq" type="text" id="icq" value="<?php echo $p[icq]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Birthdate<BR>
        (yyyy-mm-dd)</TD>
      <TD><INPUT NAME='birthdate' VALUE='<?php echo $p[birthdate]; ?>' SIZE=10 readonly="readonly"></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Sex</TD>
      <TD><select NAME='sex' size=1 disabled="disabled">
          <option></option>
          <option <?php if ($p[sex]=="Male") echo "selected"; ?>>Male</option>
          <option <?php if ($p[sex]=="Female") echo "selected"; ?>>Female</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>I like</TD>
      <TD><select NAME='likes' size=1 disabled="disabled">
          <option></option>
          <option <?php if ($p[likes]=="Males") echo "selected"; ?>>Males</option>
          <option <?php if ($p[likes]=="Females") echo "selected"; ?>>Females</option>
          <option <?php if ($p[likes]=="Both") echo "selected"; ?>>Both</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Occupation</TD>
      <TD><input name="occupation" readonly="readonly" type="text" id="occupation" size="30" value="<?php echo $p[occupation]; ?>"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Marital Status</TD>
      <TD><select NAME='maritalstatus' size=1 disabled="disabled">
          <option></option>
          <option <?php if ($p[maritalstatus]=="Single") echo "selected"; ?>>Single</option>
          <option <?php if ($p[maritalstatus]=="Married") echo "selected"; ?>>Married</option>
          <option <?php if ($p[maritalstatus]=="Divorced") echo "selected"; ?>>Divorced</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Height</TD>
      <TD><INPUT NAME='height' VALUE='<?php echo $p[height]; ?>' SIZE=8 readonly="readonly"></INPUT> 
        m </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Weight</TD>
      <TD><INPUT NAME='weight' VALUE='<?php echo $p[weight]; ?>' SIZE=8 readonly="readonly"></INPUT> 
        kg </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Skin</TD>
      <TD><select NAME='skin' size=1 disabled="disabled">
          <option></option>
          <option <?php if ($p[skin]=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($p[skin]=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($p[skin]=="Red") echo "selected"; ?>>Red</option>
          <option <?php if ($p[skin]=="White") echo "selected"; ?>>White</option>
          <option <?php if ($p[skin]=="Yellow") echo "selected"; ?>>Yellow</option>
          <option <?php if ($p[skin]=="Pink") echo "selected"; ?>>Pink</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Ethnicity</TD>
      <TD><input name="ethnicity" type="text" id="ethnicity" value="<?php echo $p[ethnicity]; ?>" size="30" readonly="readonly"></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Eyes</TD>
      <TD><select NAME='eyes' size=1 disabled="disabled">
          <option></option>
          <option <?php if ($p[eyes]=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($p[eyes]=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($p[eyes]=="Green") echo "selected"; ?>>Green</option>
          <option <?php if ($p[eyes]=="Blue") echo "selected"; ?>>Blue</option>
        </select></TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Hair</TD>
      <TD><select NAME='hair' size=1 disabled="disabled">
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
    <TR bgcolor="#f0f0f0" class='tr0'> 
      <TD>Languages Spoken</TD>
      <TD><INPUT NAME='languages' VALUE='<?php echo $p[languages]; ?>' SIZE=60 readonly="readonly"></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#f0f0f0" class='tr1'> 
      <TD>Details about me</TD>
      <TD><TEXTAREA NAME='details' cols=60 rows='4' readonly="readonly" wrap='PHYSICAL'><?php echo $p[details]; ?></TEXTAREA></TD>
    </TR>
  </table>
<br>
<?

if ($tm0emails)
{
 echo "<center><br> Your rank allows you to <a href='mailto:$m[email]'>email member</a>. </center>";
};

if ($tm0phone)
{
 echo "<center><br> Your rank allows you to see member fax/phone number: <br> PHONE : $m[phone] <br> FAX : $m[fax] </center>";
};


$r=q("select id, picture, details, member from pictures where type='Public' and member='$mid' order by rdate desc");

if (!e($r)) while ($pic=f($r))
{
 echo "<TABLE bgcolor=#E0E0E0 width=90% BORDER=0 CELLPADDING=1 CELLSPACING=1 align=center valign=top>";
 echo "<TR><TD align=center BGCOLOR=#F0F0F0 height=50 width=30%><a href='picture.php?pid=$pic[id]'><IMG src='".piurl($pic[picture])."'  height=50 border=1 alt=\"$pic[details]\"></a></TD><TD BGCOLOR=#FAFAFA><blockquote>".nl2br(Htmlspecialchars($pic[details]))."</blockquote></TD></TR>";
 echo "</TABLE>";
};

include "_footer.php";
?>
