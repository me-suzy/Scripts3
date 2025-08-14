<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.6                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
define('IN_VCARD', true);
require("./lib.inc.php");

dothml_pageheader();

// ##################### Nullification Screen #######################
dohtml_table_header("extrainfo","$msg_admin_extrainfo");
?>
	<td>
	<!-- Avaiable Null -->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">

<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_program_name"; ?>:</td><td><b>vCard PRO</td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_program_version"; ?>:</td><td><b><?php echo "$vcardversion"; ?></td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_program_author"; ?>:</td><td><b>Joao Kikuchi,  Belchior Foundry         </td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_home_page"; ?>:</td><td>     <b>http://www.belchiorfoundry.com/         </td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_retail_price"; ?>:</td><td>  <b>$80.00 United States Dollars            </td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_webForum_price"; ?>:</td><td><b>$00.00 Always 100% Free                 </td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_xcgi_price"; ?>:</td><td>    <b>$00.00 Always 100% Free                 </td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_forumru_price"; ?>:</td><td><b>$00.00 Always 100% Free                 </td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_cgihaven_price"; ?>:</td><td>    <b>$00.00 Always 100% Free                 </td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_supplied_by"; ?>:</td><td>   <b>South [WTN]                            </td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_nullified_by"; ?>:</td><td>  <b>CyKuH [WTN]                             </td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_distribution"; ?>:</td><td>  <b>via WebForum, ForumRU and associated file dumps</td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_protection"; ?>:</td><td>    <b>Call Home and Seed call, Licencec check remove, Referrer links, Hot Links Remove</td></tr>
<tr><td valign="top"><b><?php echo "$msg_admin_language"; ?>:</td><td>      <b>PHP, MySQL                              </td></tr>
<tr bgcolor="#D0DCE0"><td valign="top"><b><?php echo "$msg_admin_ss"; ?>:</td><td>    <b>Stable</td></tr>
	</tr>                            
	</table>                         
	</font>
	<!-- /Avaiable Null -->
	</td>
</tr>
</table>
<!-- /EXTRA -->
<?php
dothml_pagefooter();
?>