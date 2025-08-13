<?

#################################################################################################

#

#  project              : phpListPro

#  filename             : footer.inc

#  last modified by     : Erich Fuchs

#  e-mail               : office@smartisoft.com

#  purpose              : Footer File

#

#################################################################################################



echo "<div class=\"proctime\"><br>\n";



if ($show_proctime) {

    list($usec, $sec) = explode(" ",$proctime_start);

    $proctime_start = $usec+$sec;



    list($usec, $sec) = explode(" ",microtime());

    $proctime_end = $usec+$sec;

    $proctime = $proctime_end-$proctime_start;

    echo " Processing Time: ".substr($proctime,0,7)." sec.";

}

?>

<!-- BEGIN Your Footer Stuff here -->







<!-- END Your Footer Stuff here -->

</center>

</div>

</body>

</html>
