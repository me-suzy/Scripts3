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

$result = mysql_query("SELECT * FROM clients ORDER BY id ASC",$db);
while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF CLIENTS
printf("<tr><td width='50'><a href='viewclients.php?id=%s' class='link'>%s</a></td>
<td width='260'><a href='http://www.%s%s' class='link'>www.%s%s</a></td>",
$myrow["id"], $myrow["id"], $myrow["domainname"], $myrow["domain"], $myrow["domainname"], $myrow["domain"]);

printf("<td width='260'><a href='mailto:%s' class='link'>%s</a></td></tr>", $myrow["email"], $myrow["email"]);
} // PRINT LIST OF CLIENTS
?>