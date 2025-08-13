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

$result = mysql_query("SELECT * FROM staff ORDER BY id ASC",$db);
while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF STAFF
printf("<tr><td width='50'><a href='viewstaff.php?id=%s' class='link'>%s</a></td>
<td width='350'><a href='viewstaff.php?id=%s' class='link'>%s</a></td>",
$myrow["id"], $myrow["id"], $myrow["id"], $myrow["adminname"]);

printf("<td width='170' class='summary_message'>%s</td></tr>\n", $myrow["access"]);
} // PRINT LIST OF STAFF
?>