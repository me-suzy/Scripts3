<?
#################################################################################################
#
#  project           : phpBazar
#  filename          : vote_show.php
#  last modified by  : Erich Fuchs
#  supplied by       : Reseting
#  nullified by	     : CyKuH [WTN]
#  purpose           : Show the voting's
#
#################################################################################################



echo "<form action=\"vote_submit.php?source=$PHP_SELF\" method=\"POST\">";

    mysql_connect($server,$db_user,$db_pass);

    $result=mysql_db_query($database,"select sum(votes) as sum from votes");

    if($result) {

        $sum = (int) mysql_result($result,0,"sum");

        mysql_free_result($result);

    }



    $result=mysql_db_query($database,"select * from votes order by votes DESC");

    echo "<table border=0 cellspacing=1 cellpadding=1 height=183><tr><td class=\"votetext\">$vote_vote</td><td class=\"votetext\">$vote_answer</td><td class=\"votetext\">%</td></tr>\n";

    while($db=mysql_fetch_array($result)) {

	$id=$db[id];

	if (!$voteanswer[$id]) {$voteanswer[$id]=$db[name];}

	echo "<tr><td align=center><input type=radio name=vote value=\"$id\"></td>";

    	echo "<td class=\"votetext\" colspan=\"2\">" .$voteanswer[$id]."</td></tr><tr>

	<td class=\"votetext\" align=\"center\">".$db[votes]."</td><td class=\"votetext\">";



        if($sum && (int)$db[votes]) {

    	    $per = (int)(100 * $db[votes]/$sum);



	    echo "<table align=center border=0 cellspacing=0 cellpadding=1 width=\"$votebar_width\" height=\"$votebar_height\">\n";

            echo " <tr>\n";

	    echo "  <td class=\"votebarout\">\n";

    	    echo "   <table align=left border=0 cellspacing=0 cellpadding=0 width=\"$per%\" height=\"100%\">\n";

    	    echo "    <tr>\n";

	    echo "     <td class=\"votebarin\">\n";

    	    echo "        <div class=\"votespace\">&nbsp;</div>\n";

	    echo "     </td>\n";

	    echo "    </tr>\n";

	    echo "   </table>\n";

	    echo "  </td>\n";

	    echo " </tr>\n";

    	    echo "</table>\n";



	    echo"</td><td class=\"votetext\">$per</td>";

      	    }

        echo "</tr>\n";

        }

     mysql_free_result($result);

     mysql_close();

echo "</table>\n";

echo "<br><input type=submit value=\"$vote_button\"></form>";

?>