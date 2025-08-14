<?

session_start();
session_register("sessionc");

include "_header.php";

if ($page>999 or $page<1) $page=1;
if (!$search) $page=0;

if (!$age1) $age1=18;
if (!$age2) $age2=40;

if ($search)
{

$c_pic="and m.id=pi.member and pi.type='Main' ";
$t_pic=", pictures pi";
$s_pic=", pi.id as pid, pi.picture as picture, pi.details as palt";
if (!$pictures) {$c_pic=""; $t_pic="";$s_pic="";};

$c="m.id=p.id $c_pic";
if ($login) $c.="and m.login like '%$login%' ";
if ($fname) $c.="and m.fname like '%$fname%' ";
if ($lname) $c.="and m.lname like '%$lname%' ";
if ($country) $c.="and m.country like '%$country%' ";
if ($state) $c.="and m.state like '%$state%' ";
if ($city) $c.="and m.city like '%$city%' ";
if ($yahoo) $c.="and p.yahoo like '%$yahoo%' ";
if ($msn) $c.="and p.msn like '%$msn%' ";
if ($aol) $c.="and p.aol like '%$aol%' ";
if ($icq) $c.="and p.icq='$icq' ";

$age1=$age1+0;
$age2=$age2+0;
if ($age1) $c.="and (YEAR(CURRENT_DATE)-YEAR(p.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(p.birthdate,5)) >= $age1 ";
if ($age2) $c.="and (YEAR(CURRENT_DATE)-YEAR(p.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(p.birthdate,5)) <= $age2 ";

if ($sex) $c.="and p.sex='$sex' ";
if ($likes) $c.="and p.likes='$likes' ";

$height1=$height1+0;
$height2=$height2+0;
if ($height1) $c.="and p.height >= $height1 ";
if ($height2) $c.="and p.height <= $height2 ";

$weight1=$weight1+0;
$weight2=$weight2+0;
if ($weight1) $c.="and p.weight >= $weight1 ";
if ($weight2) $c.="and p.weight <= $weight2 ";

if ($maritalstatus) $c.="and p.maritalstatus='$maritalstatus' ";
if ($skin) $c.="and p.skin='$skin' ";
if ($eyes) $c.="and p.eyes='$eyes' ";
if ($hair) $c.="and p.hair='$hair' ";

if ($search==2) 
{
$c=$sessionc;    
};

$sessionc=$c;
//echo "<br> Query : '$c' <br> ";
$r=q("select m.id as id, m.login as login, m.country as contry, m.state as state, m.city as city, (YEAR(CURRENT_DATE)-YEAR(p.birthdate)) - (RIGHT(CURRENT_DATE,5)<RIGHT(p.birthdate,5)) as age, p.ldate as ldate, p.details as details $s_pic from members m, profiles p $t_pic where $c ORDER BY m.login ASC LIMIT ".(($page-1)*10).", 10");

if (!$onlinetimeout) $onlinetimeout=60*3;
$logt= time()-$onlinetimeout;

?>

<CENTER>
  <p><b> </b></p>
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <TR class='tr1'> 
      <TD colspan="2" bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Browse members &gt; </font></strong>
		<?for ($i=($page-12);$i<$page;$i++) if ($i>0) echo " [ <a href=search.php?search=2&page=".($i).">".($i)."</a> ] ";?>
		[ page <?php echo $page; ?> ]
		<?if ($page+1>0) echo "[ <a href=search.php?search=2&page=".($page+1)."> next </a> ]";?>
		</TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD><div align="center"><strong>Member</strong></div></TD>
      <TD><div align="center"><strong>Details</strong></div></TD>
    </TR>
    <?php
if (!e($r))
while ($m=f($r))
{?>
    <TR valign="top" bgcolor="#F0F0F0" class='tr1'> 
      <TD width="50%" align="center"> <?php echo "<a href=mem.php?mid=$m[id]>$m[login]</a>";
	  if ($m[pid]) echo "<br><a href='picture.php?pid=$m[pid]'> <IMG src='".piurl($m[picture])."'  width=50 border=1 alt=\"$m[palt]\"></a>";
	  if ($m[details]) echo "<br>".$m[details];
	  ?> </TD>
      <TD bgcolor="#F0F0F0">
	  <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">       
          <tr bgcolor="#F0F0F0" class='tr1'> 
            <td width="55" height="20" valign="top" bgcolor="#D5DFEE">Location</td>
            <td width="538" height="20" valign="top" bgcolor="#F0F0F0"><?php echo "$m[Country] $m[State] $m[City]";?></td>
          <tr> 
            <td width="55" height="20" valign="top" bgcolor="#D5DFEE">Age</td>
            <td height="20" valign="top" bgcolor="#F0F0F0"><?php echo "$m[age]";?></td>
          </tr>
          <tr> 
            <td width="55" height="20" valign="top" bgcolor="#D5DFEE">Status</td>
            <td height="20" valign="top" bgcolor="#F0F0F0"><?php echo (($m[ldate]>$logt)?"<a href='messages.php?to=$m[login]&subject=Hi, $m[login]!&message=I saw you online...'><b>Online</b></a>":"Offline");?></td>
          </tr>
          </table>
		</TD></TR>
    <?php } else echo "<TR class='tr1'><TD colspan='4'>No members found to fit the required profile...</TD></TR>";
 ?>
  </TABLE></CENTER>
 
 <?php
};

if ($search<2)
{
?>

<form name="form1" method="post" action="search.php">

  <table width="600" border="0" cellpadding="1" cellspacing="1" align="center">
    <TR bgcolor="#D5DFEE" class='tr1'> 
      <TD bgcolor="#FFFFFF"><strong><font size="2" face="Arial, Helvetica, sans-serif">Search 
        &gt;</font></strong></TD>
      <TD bgcolor="#FFFFFF"><div align="right">
          <input type="submit" name="Submit3" value="Show &gt;">
        </div></TD>
    </TR>
    <TR bgcolor="#000000" class='tr1'> 
      <TD height="3" colspan="2"></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Nickname</TD>
      <TD><input name="login" type="text" id="login" value="<?php echo $login; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>First Name</TD>
      <TD><input name="fname" type="text" id="fname" value="<?php echo $fname; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Last Name</TD>
      <TD><input name="lname" type="text" id="lname" value="<?php echo $lname ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Country</TD>
      <TD><input name="country" type="text" id="country" value="<?php echo $country; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>State</TD>
      <TD><input name="state" type="text" id="state" value="<?php echo $state; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>City</TD>
      <TD><input name="city" type="text" id="city" value="<?php echo $city; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Yahoo messenger username</TD>
      <TD><input name="yahoo" type="text" id="yahoo" value="<?php echo $yahoo; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>MSN messenger email</TD>
      <TD><input name="msn" type="text" id="msn"  value="<?php echo $msn; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>AOL messenger username</TD>
      <TD><input name="aol" type="text" id="aol" value="<?php echo $aol; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>ICQ number</TD>
      <TD><input name="icq" type="text" id="icq" value="<?php echo $icq; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Age</TD>
      <TD>From 
        <SELECT NAME='age1' VALUE='<?php echo $age1; ?>' >
          <?php 
	    for ($i=14;$i<100;$i++) {?>
          <option <?php if ($age1=="$i") echo "selected"; ?>><?php echo $i; ?></option>
          <?php };
        ?>
        </SELECT>
        to 
        <SELECT NAME='age2' VALUE='<?php echo $age2; ?>' >
          <?php 
	    for ($i=14;$i<100;$i++) {?>
          <option <?php if ($age2=="$i") echo "selected"; ?>><?php echo $i; ?></option>
          <?php };
        ?>
          <option <?php if ($age2>="100") echo "selected"; ?> VALUE='10000'>many</option>
        </SELECT>
        years. </TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Sex</TD>
      <TD><select NAME='sex' size=1 >
          <option></option>
          <option <?php if ($sex=="Male") echo "selected"; ?>>Male</option>
          <option <?php if ($sex=="Female") echo "selected"; ?>>Female</option>
        </select></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Likes</TD>
      <TD><select NAME='likes' size=1 >
          <option></option>
          <option <?php if ($likes=="Males") echo "selected"; ?>>Males</option>
          <option <?php if ($likes=="Females") echo "selected"; ?>>Females</option>
          <option <?php if ($likes=="Both") echo "selected"; ?>>Both</option>
        </select></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Occupation</TD>
      <TD><input name="occupation"  type="text" id="occupation" size="30" value="<?php echo $occupation; ?>"></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Marital Status</TD>
      <TD><select NAME='maritalstatus' size=1 >
          <option></option>
          <option <?php if ($maritalstatus=="Single") echo "selected"; ?>>Single</option>
          <option <?php if ($maritalstatus=="Married") echo "selected"; ?>>Married</option>
          <option <?php if ($maritalstatus=="Divorced") echo "selected"; ?>>Divorced</option>
        </select></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Height</TD>
      <TD>From 
        <INPUT NAME='height1' VALUE='<?php echo $height1; ?>' SIZE=8 >
        to 
        <INPUT NAME='height2' VALUE='<?php echo $height2; ?>' SIZE=8 ></INPUT> 
        m .</TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Weight</TD>
      <TD>From 
        <INPUT NAME='weight1' VALUE='<?php echo $weight1; ?>' SIZE=8 ></INPUT> 
        to 
        <INPUT NAME='weight2' VALUE='<?php echo $weight2; ?>' SIZE=8 >
        kg .</TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Skin</TD>
      <TD> <select NAME='skin' size=1 >
          <option></option>
          <option <?php if ($skin=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($skin=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($skin=="Red") echo "selected"; ?>>Red</option>
          <option <?php if ($skin=="White") echo "selected"; ?>>White</option>
          <option <?php if ($skin=="Yellow") echo "selected"; ?>>Yellow</option>
          <option <?php if ($skin=="Pink") echo "selected"; ?>>Pink</option>
        </select> </TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Ethnicity</TD>
      <TD><input name="ethnicity" type="text" id="ethnicity" value="<?php echo $ethnicity; ?>" size="30" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Eyes</TD>
      <TD><select NAME='eyes' size=1 >
          <option></option>
          <option <?php if ($eyes=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($eyes=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($eyes=="Green") echo "selected"; ?>>Green</option>
          <option <?php if ($eyes=="Blue") echo "selected"; ?>>Blue</option>
        </select></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD>Hair</TD>
      <TD><select NAME='hair' size=1 >
          <option></option>
          <option <?php if ($hair=="Black") echo "selected"; ?>>Black</option>
          <option <?php if ($hair=="Brown") echo "selected"; ?>>Brown</option>
          <option <?php if ($hair=="Red") echo "selected"; ?>>Red</option>
          <option <?php if ($hair=="Blonde") echo "selected"; ?>>Blonde</option>
          <option <?php if ($hair=="Blue") echo "selected"; ?>>Blue</option>
          <option <?php if ($hair=="Green") echo "selected"; ?>>Green</option>
          <option <?php if ($hair=="None") echo "selected"; ?>>None</option>
        </select></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Languages Spoken</TD>
      <TD><INPUT NAME='languages' VALUE='<?php echo $languages; ?>' SIZE=60 ></INPUT> 
      </TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Show only members with pictures</TD>
      <TD><input name="pictures" type="checkbox" id="pictures" value="on" <?php if ($pictures) echo "checked"; ?>></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD>Page</TD>
      <TD><input name="page" type="text" id="page" value="1" size="3" ></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD colspan="2"><div align="center"> 
          <input name="search" type="hidden" value="search">
          <input type="reset" name="Submit2" value="Reset">
          <input type="submit" name="Submit" value="Show">
        </div></TD>
    </TR>
  </table>
</form>
<?
};

include "_footer.php";
?>
