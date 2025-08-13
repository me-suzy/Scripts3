<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
require("hf.php");
pageHeader();
?>
<?php
if(!$action) {
?>
<p align=center><font size=2 face=Verdana><b>Search for Order!</b></font></div>

<form action='search.php' method='post'>
<input type='hidden' name='action' value='submit'>
<div align='center'>
<center>
<table border='0' cellpadding='0' cellspacing='8' width='80%'>

<tr>
<td width='100%' align='center'><font size='2' face='Verdana'>Order ID:</font> <input type='text' name='order_id' size='20'></td>
</tr>
<tr>
<td width='100%' colspan='2'>
<p align='center'><input type='submit' name='submit' value='Search Orders'></p>
</td>
</tr>
</table>
</center>
</div>
</form>

<?php } ?>
<?php
if ($action=="submit") {
$filename = "../orders/$order_id.txt";
if (file_exists($filename)) {
    print "<center><font size=2 face=Verdana><B>Order ID: $order_id</B></font></center>\n";
    print "<font size=2 face=Verdana>";
    print "<center><textarea rows='15' name='order' cols='79' style='font-family: Verdana; font-size: 10pt'>";
   include("../orders/$order_id.txt");
    print" </textarea></center>";
    print "</font>";
} else {
    print "$filename<center><font size=2 face=Verdana>Order number does not exist!</font></center>\n";
}
}
?>
<?php pageFooter();?>