<?php

/*
   Copyright (c), 1999, 2000 - phpauction.org
   Copyright (c), 2001, 2002 - webauction.de.vu

   Lizenz siehe lizenz.txt & license

   Die neueste Version kostenfrei zum Download unter:
   http://webauction.de.vu

*/

        include("../includes/config.inc.php");
        include("../includes/messages.inc.php");

	//--Authentication check
	if(!$authenticated){
		Header("Location: login.php");
	}
?>

<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>

<?php include("../includes/styles.inc.php"); ?>

<BODY>

<?php include("./header.php"); ?>

<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BGCOLOR="#FFFFFF">
<TR>
<TD>

	<FONT FACE="Verdana, Arial, Helvetica, sans-serif" SIZE="4"><br>
	<B>
        <CENTER>
		<?php print $MSG_083; ?>
                <br>
        </center>
	</B>
	</FONT>
	<BR>
	<BR>
	<CENTER>
	<TABLE WIDTH=400 CELLPADDING=2>
	<TR>
	<TD WIDTH=50></TD>
	<TD>
		<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">

                <?php
                $country_query = "SELECT country, country_id, countries_long, FIPS_code, capital, domain
	        		FROM ".$dbfix."_countries
				ORDER BY country";
                $result = mysql_query($country_query);
                if(!$result){
                print "Database access error: abnormal termination".mysql_error();
                exit;
                } else {
                if (mysql_num_rows($result)>0) {
                $array_delete = array();

                        if($action == "add") {
                        echo "<b><u><h3>$MSG_014 $MSG_942</u></b></h3>";
                        ?>
                                <form method="post" action="<?php echo "$PHP_SELF?action=insert" ?>">
                                <?php echo "<font size=\"1\" color=\"#ed0b0b\">* $MSG_945</font>"; ?>
                                <br><br>
                                <table border="0" width="100%" cellspacing="2" cellpadding="2">
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_014; ?></b>&nbsp;<font size="1" color="#ed0b0b">*</font>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="country" size="30" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_931; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="countries_long" size="50" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_932; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="FIPS_code" size="3" maxlength="3">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_933; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="capital" size="30" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_934; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="domain" size="2" maxlength="2">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        </td>
                                        <td width="70%">
                                        <input type="submit" value="<?php echo "$MSG_941"; ?>">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                </table>
                                </form>
                        <?php
                        } elseif($action == "insert") {
                                if(!$country) {
                                        echo "<center><font color=\"#ed0b0b\">$MSG_944</font><br><br></center>";
                                        echo "<a class=\"links\" href=\"javascript:history.back();\"><b>$MSG_234</b></a></center>";
                                } else {
                                        $sql="INSERT INTO ".$dbfix."_countries
                                                (domain, country, countries_long, FIPS_code, capital)
                                                VALUES ('$domain', '$country', '$countries_long', '$FIPS_code', '$capital')";
                                        $result=mysql_query($sql);

				        if(!$result) {
                                                print "Database error on update: " . mysql_error();
					        exit;
				        } else {
                                                echo "<center>$MSG_014 <b>$country</b> $MSG_943<br><br>";
                                                echo "<a class=\"links\" href=\"$PHP_SELF\"><b>$MSG_234</b></a></center>";
				        }
                                }

                        } elseif($action == "delete") {

                                $i = 0;
	                        while($i < count($delete)){

	   	                if($delete[$i]){
                                        $sql="DELETE FROM ".$dbfix."_countries WHERE country_id=$delete[$i]";
	   		                $result = mysql_query($sql);
	   		                if(!$result){
	   			                print "Database access error - abnormal termination ".mysql_error();
	   			                exit;
	   		                }
                                }
                                $i++;
                                }
                                if($i == 0) {
                                        echo "<center><font color=\"#ed0b0b\">$MSG_946</font><br><br></center>";
                                        echo "<a class=\"links\" href=\"javascript:history.back();\"><b>$MSG_234</b></a></center>";
                                } else {
                                        echo "<br>";
                                        echo count($delete);
                                        if($i == 1) {
                                                echo " " . $MSG_014 . " " . $MSG_938;
                                        } else {
                                                echo " " . $MSG_940 . " " . $MSG_938;
                                        }
                                echo "<br><br>";
                                echo "<a class=\"links\" href=\"$PHP_SELF\"><b>$MSG_234</b></a>";
                                }


                        } elseif($action == "change") {
                                echo "<b><u><h3>$country $MSG_298</u></b></h3>";
                                ?>
                                <?php echo "<font size=\"1\" color=\"#ed0b0b\">* $MSG_945</font>"; ?>
                                <form method="post" action="<?php echo "$PHP_SELF?action=safe&country_id=$country_id" ?>">
                                <table border="0" width="100%" cellspacing="2" cellpadding="2">
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_014; ?></b>&nbsp;<font size="1" color="#ed0b0b">*</font>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="country" value="<?php echo "$country"; ?>" size="30" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_931; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="countries_long" value="<?php echo "$countries_long"; ?>" size="50" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_932; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="FIPS_code" value="<?php echo "$FIPS_code"; ?>" size="3" maxlength="3">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_933; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="capital" value="<?php echo "$capital"; ?>" size="30" maxlength="100">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        <b><?php echo $MSG_934; ?></b>
                                        </td>
                                        <td width="70%">
                                        <input type="text" name="domain" value="<?php echo "$domain"; ?>" size="2" maxlength="2">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                  <tr>
                                        <td width="5%">
                                        </td>
                                        <td width="20%">
                                        </td>
                                        <td width="70%">
                                        <input type="submit" value="<?php echo "$MSG_298"; ?>">
                                        </td>
                                        <td width="5%">
                                        </td>
                                  </tr>
                                </table>
                                </form>
                        <?php
                        } elseif($action == "safe") {
                                if(!$country) {
                                        echo "<center><font color=\"#ed0b0b\">$MSG_944</font><br><br></center>";
                                        echo "<a class=\"links\" href=\"javascript:history.back();\"><b>$MSG_234</b></a></center>";
                                } else {
                                $sql="UPDATE ".$dbfix."_countries SET domain='$domain',
                                                           country='$country',
                                                           countries_long='$countries_long',
                                                           FIPS_code='$FIPS_code',
                                                           capital='$capital'
                                                                WHERE country_id='$country_id'";
                                $result=mysql_query($sql);

				if(!$result) {
					 print "Database error on update: " . mysql_error();
					 exit;
				} else {
                                        echo "<center>$MSG_936<br><br>";
                                        echo "<a class=\"links\" href=\"$PHP_SELF\"><b>$MSG_234</b></a></center>";
				}
                                }
                        } else {
                ?>
                                <form method="post" action="<?php echo "$PHP_SELF?action=delete"; ?>">
                                <table border="1" width="100%" cellspacing="2" cellpadding="2">
                                  <tr>
                                        <td BGCOLOR="#EEEEEE" width="15%">
                                        <b><?php echo $MSG_014; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="30%">
                                        <b><?php echo $MSG_931; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="10%">
                                        <b><?php echo $MSG_932; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="15%">
                                        <b><?php echo $MSG_933; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="15%">
                                        <b><?php echo $MSG_934; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="15%">
                                        <b><?php echo $MSG_935; ?></b>
                                        </td>
                                        <td BGCOLOR="#EEEEEE" width="15%">
                                        <b><?php echo $MSG_088; ?></b>
                                        </td>
                                  </tr>
                                  <?php
                                  while($row = mysql_fetch_array($result)) {
                                  ?>
                                  <tr>
                                        <td width="15%">
                                        <?php
                                                echo $row[country];
                                                $country = urlencode("$row[country]");
                                        ?>&nbsp;
                                        </td>
                                        <td width="30%">
                                        <?php
                                                echo $row[countries_long];
                                                $countries_long = urlencode("$row[countries_long]");
                                        ?>&nbsp;
                                        </td>
                                        <td width="10%">
                                        <?php echo $row[FIPS_code]; ?>&nbsp;
                                        </td>
                                        <td width="15%">
                                        <?php
                                                echo $row[capital];
                                                $capital = urlencode("$row[capital]");
                                        ?>&nbsp;
                                        </td>
                                        <td align="center" width="15%">
                                        <?php echo $row[domain]; ?>&nbsp;
                                        </td>
                                        <td width="15%">
                                        <?php
                                                echo "<a class=\"links\" href=\"$PHP_SELF?action=change&country_id=$row[country_id]&country=$country&countries_long=$countries_long&FIPS_code=$row[FIPS_code]&capital=$capital&domain=$row[domain]\">$MSG_298</a><br>";
                                        ?>
                                        </td>
                                        <td width="15%" align="center">
                                        <?php
                                        $del_query = "SELECT distinct u.country, a.location
	        		                        FROM ".$dbfix."_users u, ".$dbfix."_auctions a
				                        WHERE u.country=$row[country_id]
                                                        OR a.location=$row[country_id]";
                                        $del_result = mysql_query($del_query);
                                        if(!$del_result){
                                           print "Database access error: abnormal termination".mysql_error();
                                          exit;
                                        } else {
                                        if (mysql_num_rows($del_result)>0) {
                                                if($del_row = mysql_fetch_array($del_result)) {
                                                        print "<IMG SRC=\"../images/nodelete.gif\" ALT=\"<?php $MSG_939 ?>\">";
                                                }
                                        } else {
                                                echo "<input type=\"checkbox\" name=\"delete[]\" value=\"$row[country_id]\">";
                                                $delete[] = $row[country_id];
                                        }
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                    } // while
                        } // if $action
                        ?>

                        </table>
                <?php
                } else {
                        print "Database access error: abnormal termination".mysql_error();
                        exit;
                }
                if($action == "") {
                ?>
                <table border="0" width="100%" cellpadding="3" cellspacing="3">
                  <tr>
                        <td width="5%">
                        </td>
                        <td width="50%" align="right">
                        <input type="submit" value="<?php echo $MSG_088; ?>">
                        <input type="reset" value="<?php echo $MSG_937; ?>">
                        </form>
                        </td>
                        <td width="40%" align="left">
                        <form method="post" action="<?php echo "$PHP_SELF?action=add"; ?>">
                        <input type="submit" value="<?php echo $MSG_014 . " " . $MSG_942; ?>">
                        </form>
                        </td>
                        <td width="5%">
                        </td>
                  </tr>
                </table>
                <?php
                }
                }

                ?>
	</TD>
	</TR>
	<TR>
	<TD WIDTH=50></TD>
	<TD>

	</TD>
	</TR>
	</TABLE>
	<BR><BR>

	<FONT FACE="Verdana, Verdana, Arial, Helvetica, sans-serif" SIZE="2">
	<A class="links" HREF="admin.php" CLASS="links"><?php echo $MSG_062; ?></A>
	</FONT>
	<BR><BR>
</TD>
</TR>
</TABLE>

<!-- Closing external table (header.php) -->
</TD>
</TR>
</TABLE>

<?php include("./footer.php"); ?>
</BODY>
</HTML>
