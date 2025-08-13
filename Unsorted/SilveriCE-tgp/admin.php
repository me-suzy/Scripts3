<?
/////////////////////////////////////////////////////
//       SilveriCE TGP Script - FREE Version       //
//                                                 //
//                 Copyright 2002                  //
//                 Simon Yorkston                  //
//            quantum-x@qserve.8m.com              //
//              All Rights Reserved                //
//                                                 //
//          In using this script you               //
//           agree to the following:               //
//                                                 //
//                                                 //
//     You may not distibute this script, or       //
//           any modifications of it.              //
//                                                 //
//   A link must be provided on the website that   //
//             uses the script to:                 //
//          http://quantum-x.ice.org/tgp           //
//                                                 //
//      Any breaches of these conditions           //
//        will result in legal action.             //
//                                                 //
//      This script is distributed with            //
//                 no warranty                     //
//                                                 //
/////////////////////////////////////////////////////


require 'auth.php'
?>
<HTML>
<BODY>
<?
if ($submit) {
	if ($choice=="submit") {echo "<meta http-equiv=\"refresh\" content=\"0;URL=browse.php\">";}
	elseif ($choice=="blacklist") {echo "<meta http-equiv=\"refresh\" content=\"0;URL=blacklist.php\">";}
	elseif ($choice=="publish") {echo "<meta http-equiv=\"refresh\" content=\"0;URL=publish.php\">";}
	else {}
}
else{
echo $start;
echo "<center>

    <form method=\"post\" action=\"admin.php\">
    <select name=\"choice\">
                    <option value=\"submit\">View submissions</option>
                    <option value=\"blacklist\">View / Edit blacklist</option>
                    <option value=\"publish\">Publish accepted</option>
                  </select>
    <input type=\"submit\" name=\"submit\" value=\"submit\">
    </form>
    </center>";
echo $end;
}
?>