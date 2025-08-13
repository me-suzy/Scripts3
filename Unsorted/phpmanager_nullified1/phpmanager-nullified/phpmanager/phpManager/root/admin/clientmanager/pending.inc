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

$sql = "SELECT * FROM pending WHERE username!=''";
$result = mysql_query($sql);
if(mysql_num_rows($result)) {

$result = mysql_query("SELECT * FROM pending ORDER BY id ASC",$db);

while ($myrow = mysql_fetch_array($result)) { // PRINT LIST OF PENDING CLIENTS

printf("<tr><td width='50'><a href='clientmanager/pending.php?id=%s' class='link'>%s</a></td>
<td width='350'><a href='clientmanager/pending.php?id=%s' class='link'>%s %s</a></td>",
$myrow["id"], $myrow["id"], $myrow["id"], $myrow["firstname"], $myrow["surname"]);

printf("<td width='170' class='summary_message'>(<a href='clientmanager/pending.php?id=%s&delete=yes' class='link'>Cancel</a>)</td></tr>\n",
$myrow["id"]);

} // END PRINT LIST OF PENDING CLIENTS

} else {

echo "<td width='570' colspan='3' class='summary_message'>$client_nopending</td>";

}

?>

