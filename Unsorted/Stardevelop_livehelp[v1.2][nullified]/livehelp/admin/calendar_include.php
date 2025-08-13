<?php
/*
stardevelop.com Live Help
International Copyright stardevelop.com

You may not distribute this program in any manner,
modified or otherwise, without the express, written
consent from stardevelop.com

You may make modifications, but only for your own 
use and within the confines of the License Agreement.
All rights reserved.

Selling the code for this program without prior 
written consent is expressly forbidden. Obtain 
permission before redistributing this program over 
the Internet or in any other medium.  In all cases 
copyright and header must remain intact.  
*/
if (!isset($_GET['MONTH'])){ $_GET['MONTH'] = ""; }
$month_jump = $_GET['MONTH'];

if ($_GET['MONTH'] == "") {
$month_jump = 0;
}
?>
<table width="175" border="0" cellpadding="1" cellspacing="1">
  <tr> 
    <td height="30"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <a href="<?php echo($_SERVER['PHP_SELF']); ?>?MONTH=<?php echo($month_jump-1); ?>"><img src="../icons/mini_back.gif" width="16" height="16" border="0" alt="Previous Month"></a> 
        </font></div></td>
    <td height="30" colspan="5"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <?php
	$month = date('F', mktime (0,0,0,date('m')+$month_jump,date('d'),date('Y')));
	if ($language_type != 'en') {
	switch ($month) { 
		case 'January':
			$month = $language['january']; 
			break;
		case 'February':
			$month = $language['february']; 
			break;
		case 'March':
			$month = $language['march']; 
			break;
		case 'April':
			$month = $language['april']; 
			break;
		case 'May':
			$month = $language['may']; 
			break;
		case 'June':
			$month = $language['june']; 
			break;
		case 'July':
			$month = $language['july']; 
			break;
		case 'August':
			$month = $language['august']; 
			break;
		case 'September':
			$month = $language['september']; 
			break;
		case 'October':
			$month = $language['october']; 
			break;
		case 'November':
			$month = $language['november']; 
			break;
		case 'December':
			$month = $language['december']; 
			break;
	}
	}
	 
	$year = date('Y', mktime (0,0,0,date('m')+$month_jump,date('d'),date('Y'))); 
	$num_year_month = date('Y-m', mktime (0,0,0,date('m')+$month_jump,date('d'),date('Y')));
	echo($month . ' ' . $year);
		?>
        </font></div></td>
    <td height="30"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="<?php echo($_SERVER['PHP_SELF']); ?>?MONTH=<?php echo($month_jump+1); ?>"><img src="../icons/mini_forward.gif" width="16" height="16" border="0" alt="Next Month"></a></font></div></td>
  </tr>
  <tr> 
    <td><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">S</font></strong></div></td>
    <td width="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">M</font></strong></div></td>
    <td width="25" height="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">T</font></strong></div></td>
    <td width="25" height="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">W</font></strong></div></td>
    <td width="25" height="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">T</font></strong></div></td>
    <td width="25" height="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">F</font></strong></div></td>
    <td width="25" height="25"><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">S</font></strong></div></td>
  </tr>
  <tr bgcolor="E4F2FB"> 
    <?php
  	$first_day  = date('w', mktime(0,0,0,date('m')+$month_jump,1,date('Y'))); 
	$total_days = date('t', mktime(0,0,0,date('m')+$month_jump,1,date('Y')));
	?>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0:
			print '1'; 
			break; 
		case 6:
			if($total_days > 29) {
			print '30';
			}
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0:
			print '2'; 
			break; 
		case 1: 
			print '1'; 
			break; 
		case 6:
			if($total_days > 30) {
			print '31';
			}
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '3'; 
			break; 
		case 1: 
			print '2'; 
			break; 
		case 2: 
			print '1'; 
			break; 
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '4'; 
			break; 
		case 1: 
			print '3'; 
			break; 
		case 2: 
			print '2'; 
			break; 
		case 3: 
			print '1'; 
			break; 
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '5'; 
			break; 
		case 1: 
			print '4'; 
			break; 
		case 2: 
			print '3'; 
			break; 
		case 3: 
			print '2'; 
			break; 
		case 4: 
			print '1'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '6'; 
			break; 
		case 1: 
			print '5'; 
			break; 
		case 2: 
			print '4'; 
			break; 
		case 3: 
			print '3'; 
			break; 
		case 4: 
			print '2'; 
			break;
		case 5: 
			print '1'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '7'; 
			break; 
		case 1: 
			print '6'; 
			break; 
		case 2: 
			print '5'; 
			break; 
		case 3: 
			print '4'; 
			break; 
		case 4: 
			print '3'; 
			break;
		case 5: 
			print '2'; 
			break;
		case 6: 
			print '1'; 
			break;
	} 
	?>
        </em></font></div></td>
  </tr>
  <tr bgcolor="E4F2FB"> 
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '8'; 
			break; 
		case 1: 
			print '7'; 
			break; 
		case 2: 
			print '6'; 
			break; 
		case 3: 
			print '5'; 
			break; 
		case 4: 
			print '4'; 
			break;
		case 5: 
			print '3'; 
			break;
		case 6: 
			print '2'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '9'; 
			break; 
		case 1: 
			print '8'; 
			break; 
		case 2: 
			print '7'; 
			break; 
		case 3: 
			print '6'; 
			break; 
		case 4: 
			print '5'; 
			break;
		case 5: 
			print '4'; 
			break;
		case 6: 
			print '3'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';"  onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '10'; 
			break; 
		case 1: 
			print '9'; 
			break; 
		case 2: 
			print '8'; 
			break; 
		case 3: 
			print '7'; 
			break; 
		case 4: 
			print '6'; 
			break;
		case 5: 
			print '5'; 
			break;
		case 6: 
			print '4'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '11'; 
			break; 
		case 1: 
			print '10'; 
			break; 
		case 2: 
			print '9'; 
			break; 
		case 3: 
			print '8'; 
			break; 
		case 4: 
			print '7'; 
			break;
		case 5: 
			print '6'; 
			break;
		case 6: 
			print '5'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '12'; 
			break; 
		case 1: 
			print '11'; 
			break; 
		case 2: 
			print '10'; 
			break; 
		case 3: 
			print '9'; 
			break; 
		case 4: 
			print '8'; 
			break;
		case 5: 
			print '7'; 
			break;
		case 6: 
			print '6'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '13'; 
			break; 
		case 1: 
			print '12'; 
			break; 
		case 2: 
			print '11'; 
			break; 
		case 3: 
			print '10'; 
			break; 
		case 4: 
			print '9'; 
			break;
		case 5: 
			print '8'; 
			break;
		case 6: 
			print '7'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '14'; 
			break; 
		case 1: 
			print '13'; 
			break; 
		case 2: 
			print '12'; 
			break; 
		case 3: 
			print '11'; 
			break; 
		case 4: 
			print '10'; 
			break;
		case 5: 
			print '9'; 
			break;
		case 6: 
			print '7'; 
			break;
	} 
	?>
        </em></font></div></td>
  </tr>
  <tr bgcolor="E4F2FB"> 
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '15'; 
			break; 
		case 1: 
			print '14'; 
			break; 
		case 2: 
			print '13'; 
			break; 
		case 3: 
			print '12'; 
			break; 
		case 4: 
			print '11'; 
			break;
		case 5: 
			print '10'; 
			break;
		case 6: 
			print '9'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '16'; 
			break; 
		case 1: 
			print '15'; 
			break; 
		case 2: 
			print '14'; 
			break; 
		case 3: 
			print '13'; 
			break; 
		case 4: 
			print '12'; 
			break;
		case 5: 
			print '11'; 
			break;
		case 6: 
			print '10'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '17'; 
			break; 
		case 1: 
			print '16'; 
			break; 
		case 2: 
			print '15'; 
			break; 
		case 3: 
			print '14'; 
			break; 
		case 4: 
			print '13'; 
			break;
		case 5: 
			print '12'; 
			break;
		case 6: 
			print '11'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '18'; 
			break; 
		case 1: 
			print '17'; 
			break; 
		case 2: 
			print '16'; 
			break; 
		case 3: 
			print '15'; 
			break; 
		case 4: 
			print '14'; 
			break;
		case 5: 
			print '13'; 
			break;
		case 6: 
			print '12'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '19'; 
			break; 
		case 1: 
			print '18'; 
			break; 
		case 2: 
			print '17'; 
			break; 
		case 3: 
			print '16'; 
			break; 
		case 4: 
			print '15'; 
			break;
		case 5: 
			print '14'; 
			break;
		case 6: 
			print '13'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '20'; 
			break; 
		case 1: 
			print '19'; 
			break; 
		case 2: 
			print '18'; 
			break; 
		case 3: 
			print '17'; 
			break; 
		case 4: 
			print '16'; 
			break;
		case 5: 
			print '15'; 
			break;
		case 6: 
			print '14'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '21'; 
			break; 
		case 1: 
			print '20'; 
			break; 
		case 2: 
			print '19'; 
			break; 
		case 3: 
			print '18'; 
			break; 
		case 4: 
			print '17'; 
			break;
		case 5: 
			print '16'; 
			break;
		case 6: 
			print '15'; 
			break;
	} 
	?>
        </em></font></div></td>
  </tr>
  <tr bgcolor="E4F2FB"> 
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '22'; 
			break; 
		case 1: 
			print '21'; 
			break; 
		case 2: 
			print '20'; 
			break; 
		case 3: 
			print '19'; 
			break; 
		case 4: 
			print '18'; 
			break;
		case 5: 
			print '17'; 
			break;
		case 6: 
			print '16'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '23'; 
			break; 
		case 1: 
			print '22'; 
			break; 
		case 2: 
			print '21'; 
			break; 
		case 3: 
			print '20'; 
			break; 
		case 4: 
			print '19'; 
			break;
		case 5: 
			print '18'; 
			break;
		case 6: 
			print '17'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '24'; 
			break; 
		case 1: 
			print '23'; 
			break; 
		case 2: 
			print '22'; 
			break; 
		case 3: 
			print '21'; 
			break; 
		case 4: 
			print '20'; 
			break;
		case 5: 
			print '19'; 
			break;
		case 6: 
			print '18'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '25'; 
			break; 
		case 1: 
			print '24'; 
			break; 
		case 2: 
			print '23'; 
			break; 
		case 3: 
			print '22'; 
			break; 
		case 4: 
			print '21'; 
			break;
		case 5: 
			print '20'; 
			break;
		case 6: 
			print '19'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '26'; 
			break; 
		case 1: 
			print '25'; 
			break; 
		case 2: 
			print '24'; 
			break; 
		case 3: 
			print '23'; 
			break; 
		case 4: 
			print '22'; 
			break;
		case 5: 
			print '21'; 
			break;
		case 6: 
			print '20'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '27'; 
			break; 
		case 1: 
			print '26'; 
			break; 
		case 2: 
			print '25'; 
			break; 
		case 3: 
			print '24'; 
			break; 
		case 4: 
			print '23'; 
			break;
		case 5: 
			print '22'; 
			break;
		case 6: 
			print '21'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0: 
			print '28'; 
			break; 
		case 1: 
			print '27'; 
			break; 
		case 2: 
			print '26'; 
			break; 
		case 3: 
			print '25'; 
			break; 
		case 4: 
			print '24'; 
			break;
		case 5: 
			print '23'; 
			break;
		case 6: 
			print '22'; 
			break;
	} 
	?>
        </em></font></div></td>
  </tr>
  <tr bgcolor="E4F2FB"> 
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0:
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 1: 
			print '28'; 
			break; 
		case 2: 
			print '27'; 
			break; 
		case 3: 
			print '26'; 
			break; 
		case 4: 
			print '25'; 
			break;
		case 5: 
			print '24'; 
			break;
		case 6: 
			print '23'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0:
			if($total_days > 29) {
			print '30';
			}
			break;
		case 1:
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 2: 
			print '28'; 
			break; 
		case 3: 
			print '27'; 
			break; 
		case 4: 
			print '26'; 
			break;
		case 5: 
			print '25'; 
			break;
		case 6: 
			print '24'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 0:
			if($total_days > 30) {
			print '31';
			}
			break;
		case 1:
			if($total_days > 29) {
			print '30';
			}
			break;
		case 2:
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 3: 
			print '28'; 
			break; 
		case 4: 
			print '27'; 
			break;
		case 5: 
			print '26'; 
			break;
		case 6: 
			print '25'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 1:
			if($total_days > 30) {
			print '31';
			}
			break;
		case 2:
			if($total_days > 29) {
			print '30';
			} 
			break;
		case 3: 
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 4: 
			print '28'; 
			break;
		case 5: 
			print '27'; 
			break;
		case 6: 
			print '26'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 2:
			if($total_days > 30) {
			print '31';
			}
			break;
		case 3: 
			if($total_days > 29) {
			print '30';
			} 
			break;
		case 4: 
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 5: 
			print '28'; 
			break;
		case 6: 
			print '27'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 3: 
			if($total_days > 30) {
			print '31';
			}
			break;
		case 4: 
			if($total_days > 29) {
			print '30';
			} 
			break;
		case 5: 
			if($total_days > 28) {
			print '29';
			} 
			break;
		case 6: 
			print '28'; 
			break;
	} 
	?>
        </em></font></div></td>
    <td width="25" height="25" onMouseOver="this.style.background='#CAE6F7';" onMouseOut="this.style.background='#E4F2FB';" onClick="getReport(this,'<?php echo($num_year_month); ?>');"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em> 
        <?php
	switch ($first_day) { 
		case 4: 
			if($total_days > 30) {
			print '31';
			}
			break;
		case 5: 
			if($total_days > 29) {
			print '30';
			} 
			break;
		case 6: 
			if($total_days > 28) {
			print '29';
			} 
			break;
	} 
	?>
        </em></font></div></td>
  </tr>
</table>
<div align="right"></div>