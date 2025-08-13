<?php

////////////////////////////////////////////////////////////////////////
//                                                                    //
//                - phpManager, Copyright 2002 Taysoft -              //
//                                                                    //
// All scripts contained within are protected by international        //
// copyright law. Any unauthorised copying or distribution of         //
// this software will be dealt with accordingly.                      //
//                                                                    //
// Subsequently, the copyright message must not be                    //
// removed from the software. A fee of US$200 is payable to           //
// remove the copyright message. Additionally, phpManager is          //
// distributed as a single domain license (Single IP). You will       //
// be required to purchase another license if you wish to install     //
// on another domain.                                                 //
// Please note: Removal of the copyright message does NOT give        //
// you distribution rights to the software. All you are paying        //
// for is to remove the copyright message to give a more professional //
// look for your clients.                                             //
//                                                                    //
////////////////////////////////////////////////////////////////////////

$sql = "SELECT * FROM plans WHERE package!=''";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$result = mysql_query("SELECT * FROM plans ORDER BY id ASC",$db);

while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF PLANS

printf("<form method='post' action='$PHP_SELF?nochex=yes'>
<tr><td width='200' rowspan='5' valign='top'><input type='hidden' name='id' value='%s'><br>Name:<br><br>%s<br><br>
<input type='submit' name='editnochex' value='Edit' class='formfield'></td></tr>
<tr><td width='370'><br>Monthly:<br><input type='text' name='nochex_monthly' value='%s' class='formfield' size='58'></td></tr>
<tr><td width='370'>Quarterly:<br><input type='text' name='nochex_quarterly' value='%s' class='formfield' size='58'></td></tr>
<tr><td width='370'>Bi-Annually:<br><input type='text' name='nochex_biannually' value='%s' class='formfield' size='58'></td></tr>
<tr><td width='370'>Yearly:<br><input type='text' name='nochex_yearly' value='%s' class='formfield' size='58'></td></tr></form>\n",
$myrow["id"], $myrow["package"], $myrow["nochex_monthly"], $myrow["nochex_quarterly"], $myrow["nochex_biannually"], $myrow["nochex_yearly"]);

} // END PRINT LIST OF PLANS

} else {

echo "<td width='570' colspan='5' class='summary_message'>No Plans Added</td>";

}
?>