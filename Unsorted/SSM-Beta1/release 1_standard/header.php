<?php
//---------------------------------------------------------------------/*
######################################################################
# Support Services Manager											 #
# Copyright 2002 by Shedd Technologies International | sheddtech.com #
# All rights reserved.												 #
######################################################################
# Distribution of this software is strictly prohibited except under  #
# the terms of the STI License Agreement.  Email info@sheddtech.com  #
# for information.  												 #
######################################################################
# Please visit sheddtech.com for technical support.  We ask that you #
# read the enclosed documentation thoroughly before requesting 		 #
# support.															 #
######################################################################*/
//---------------------------------------------------------------------
$_nav_1="Community";
$_lnk_7b=$openbb_webpath;

$_nav_2="Home";
$_lnk_2="system.php";

$_nav_4="Admin";
$_lnk_4="./admin/index.php";

$_nav_3="New Support Request";
$_lnk_3="system.php?action=New";

$_nav_5="&nbsp;Open Requests&nbsp;";
$_lnk_5="system.php?action=Open";

$_nav_6="Closed Requests";
$_lnk_6="system.php?action=Closed";

$_nav_7="User Manager";
$_lnk_7="system.php?action=user";

$_nav_8="&nbsp;Knowledge Base&nbsp;";
$_lnk_8="system.php?action=kb";

$_nav_9="Log Out";
$_lnk_9="index.php?system=logout";
?>
<tr>
<TD bgColor="#324C69">
<TABLE cellSpacing="0" cellPadding="0" border="0">
<TR>
<TD onMouseUp="up(this);" <?php if($at=="2"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="2"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_2; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_2; ?></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="3"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="3"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_3; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_3; ?></a></TD>
<td>&nbsp;</td>
<?php
if($usergroup=="3"){
?><!--
<TD onMouseUp="up(this);" <?php if($at=="4"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="4"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_4; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_4; ?></a></TD>
<td>&nbsp;</td>-->
<?php
}
?>
<TD onMouseUp="up(this);" <?php if($at=="5"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="5"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_5; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_5; ?></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="6"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="6"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_6; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_6; ?></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="7"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="7"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_7; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_7; ?></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="8"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php }?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="8"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php }?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_8; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_8; ?></a></TD>
<?php
if($usergroup!="3"){
?>
<td>&nbsp;</td>
<td><img src="pixel.gif" width="6" height="1" alt="" border="0"></td>
<?php
}
?>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" class="UnselectedButton" onMouseDown="down(this);" onMouseOver="over(this);" onMouseOut="out(this);" align="middle" width="100" nowrap><a href="<?php echo $_lnk_9; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_9; ?></a></TD>
<td>&nbsp;</td>
<TD onMouseUp="up(this);" <?php if($at=="1"){ ?>class="SelectedButton"<?php } else{ ?>class="UnselectedButton"<?php } ?> onMouseDown="down(this);" onMouseOver="over(this);" <?php if($at=="1"){ ?>onMouseOut="outSelected(this);"<?php } else{ ?>onMouseOut="out(this);"<?php } ?> align="middle" width="80" nowrap><a href="<?php echo $_lnk_7b; ?>" style="color: ffffff; text-decoration: none;"><?php echo $_nav_1; ?></a></TD>
</TR>
</TABLE>
</td>
</tr>
