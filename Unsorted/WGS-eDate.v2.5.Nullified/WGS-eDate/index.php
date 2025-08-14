<?php
include("_header.php");

if (!$age1) $age1=18;
if (!$age2) $age2=40;

?> 
<p align="center"><br>
  <a href=login.php?username=test&password=test> </a></p>
  <form name="form1" method="post" action="search.php">

  <table width="100%" border="0" cellpadding="2" cellspacing="2" align="center">
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD><strong>News &gt;</strong></TD>
      <TD colspan="2"><strong><font size="2" face="Arial, Helvetica, sans-serif">Fast 
        Search &gt;</font></strong></TD>
    </TR>
    <TR  bgcolor="#000000"  class='tr1'> 
      <TD></TD>
      <TD height="2"  colspan="2"></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr1'> 
      <TD rowspan="11"><p align="center"><strong>Welcome to NeoDate 2.5 !</strong></p>
        <blockquote> 
          <p>You don't know what to do next ? Please select one of these options: 
          </p>
        </blockquote>
        <ul>
          <ul>
            <ul>
              <li><a href=login.php?username=test&password=test>See test member 
                area</a></li>
              <li><a href="login.php">Login</a></li>
              <li><a href="register.php"> Register </a></li>
            </ul>
          </ul>
        </ul>
        <p><strong>Website's main features :</strong></p>
        <ul>
          <li> Comprehensive member profiles 
            <ul>
              <li><font size="-3">Contact details</font></li>
              <li><font size="-3">Personal details</font></li>
              <li><font size="-3">Web identity (ICQ, MSN, AOL, Yahoo)</font></li>
            </ul>
          </li>
          <li>Fast / advanced search</li>
          <li> Picture upload/link and management</li>
          <li>Picture rating, top pictures, most recent pictures</li>
          <li>Internal inbox private messaging and notification system</li>
          <li>View online members, most recent boy/girl</li>
          <li>Browse Members</li>
          <li>Flash Chat Area</li>
          <li> Easy registration and intuitive navigation</li>
          <li>High speed optimized by multi indexed mysql tables data storage</li>
          <li>Paypal integration</li>
          <li>Member rank and priviledge system</li>
          <li>4 Membership types<br>
            <font size="-3"> (free membership + 3 paid membership types,</font><font size="-2"> 
            see details below)</font></li>
        </ul></TD>
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
      <TD>Marital Status</TD>
      <TD><select NAME='maritalstatus' size=1 >
          <option></option>
          <option value="Single">Single</option>
          <option value="Married">Married | Couple</option>
          <option value="Divorced">Divorced</option>
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
    <TR bgcolor="#FFFFFF" class='tr1'>
      <TD class="tr0">Only with pictures</TD>
      <TD class="tr0"><input name="pictures" type="checkbox" id="pictures" value="on" <?php if ($pictures) echo "checked"; ?>></TD>
    </TR>
    <TR bgcolor="#FFFFFF" class='tr0'> 
      <TD colspan="2"><div align="center"> 
          <p> 
            <input name="search" type="hidden" value="search">
            <input type="reset" name="Submit2" value="Reset">
            <input type="submit" name="Submit" value="Search &gt;">
            <br>
            Leave not important fields blank or try the <a href="search.php">advanced 
            search</a> for more options.</p>
        </div></TD>
    </TR>
  </table>
</form>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr bgcolor="f0f0f0"> 
    <td><strong>Membership types and features &gt;&gt;</strong></td>
  </tr>
  <tr bgcolor="#000000"> 
    <td height="2"></td>
  </tr>
  <tr> 
    <td valign="top"> <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#999999">
        <tr bgcolor="#666699"> 
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">MEMBERSHIP 
              TYPE</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">MINIMUM 
              RANK</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">MESSAGE</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">CHAT</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">PICTURES</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">CONTACT 
              ON WEB</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">CONTACT 
              BY EMAIL</font></strong></div></td>
          <td><div align="center"><strong><font color="#FFFFFF" size="-2">CONTACT 
              BY PHONE</font></strong></div></td>
          <td><div align="center"><font color="#FFFFFF" size="-2"><strong>PRICE 
              (USD)</strong></font></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><font size="-2">NORMAL</font></td>
          <td><div align="center"><font color="#336699"><strong><font size="-2">0 
              </font></strong></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
              <?php if ($mem_free_message) echo "YES"; else echo "No";?>
              </font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
              <?php if ($mem_free_chat) echo "YES"; else echo "No";?>
              </font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_free_pics); ?></font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2">FREE</font></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><font size="-2">SILVER</font></td>
          <td><div align="center"><font color="#336699"><strong><font size="-2">1000</font></strong></font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_silver_pics); ?></font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
              <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
              </font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><?php echo $mem_silver_cost;?></font></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><font size="-2">GOLD</font></td>
          <td><div align="center"><font color="#336699"><strong><font size="-2">2000</font></strong></font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_gold_pics); ?></font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font color="#336633" size="-2"> 
              <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
              </font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#336633"> 
              <?php if ($mem_gold_emails) echo "YES"; else echo "No";?>
              </font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633">NO</font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><?php echo $mem_gold_cost;?></font></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td><font size="-2">PLATINUM</font></td>
          <td><div align="center"><font color="#336699"><strong><font size="-2">3000</font></strong></font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font color="#336633" size="-2">YES</font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"><?php echo ($mem_platinum_pics); ?></font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font color="#336633" size="-2"> 
              <?php if ($mem_silver_web) echo "YES"; else echo "No";?>
              </font></div></td>
          <td><div align="center"><font color="#336633" size="-2"> 
              <?php if ($mem_gold_emails) echo "YES"; else echo "No";?>
              </font></div></td>
          <td><div align="center"><font size="-2"><font color="#333399"><font color="#006633"><font color="#CC0000"><font color="#FF3333"><font color="#FF9900"><font color="#336633"> 
              <?php if ($mem_platinum_phone) echo "YES"; else echo "No";?>
              </font></font></font></font></font></font></font></div></td>
          <td><div align="center"><font size="-2"><?php echo $mem_platinum_cost;?></font></div></td>
        </tr>
      </table>
      <p align="center">Just register as a normal member for free and buy any 
        of this packages to upgrade your account.</p></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr bgcolor="f0f0f0"> 
    <td><strong>Newest Female &gt;&gt;</strong></td>
    <td> <strong>Top 3 Pictures &gt;&gt;</strong></td>
    <td> <strong>Newest Male &gt;&gt;</strong></td>
  </tr>
  <tr bgcolor="#000000"> 
    <td height="2"></td>
    <td height="2"></td>
    <td height="2"></td>
  </tr>
  <tr> 
    <td width="40%" valign="top"> 
      <?php include("inc/m_female.php"); ?>
    </td>
    <td valign="top" background="images/verticaldots.gif"> 
      <?php include("inc/p_top.php"); ?>
    </td>
    <td width="40%" valign="top" background="images/verticaldots.gif"> 
      <?php include("inc/m_male.php"); ?>
    </td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr bgcolor="#f0f0f0"> 
    <td><strong>Members Online &gt;&gt;</strong></td>
	<td> <strong>New Pictures &gt;&gt;</strong></td>
    <td> <strong>New Members &gt;&gt;</strong></td>
  </tr>
  <tr bgcolor="#000000"> 
    <td height="2"></td>
    <td height="2"></td>
    <td height="2"></td>
  </tr>
  <tr> 
    <td width="40%" valign="top"> 
      <?php include("inc/m_online.php"); ?>
    </td>
	<td valign="top" background="images/verticaldots.gif"> 
    <?php include("inc/p_new.php"); ?>
    </td>
    <td width="40%" valign="top" background="images/verticaldots.gif"> 
     <?php include("inc/m_new.php"); ?>
	</td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
include("_footer.php");
?>
