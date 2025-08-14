<?php
// 8.1 footer
 include("config.php");
 global $index;
 if ($index == 1) {
      // Begin right column
	  if ($use_templates){
		eval("dooutput(\"".gettemplate('P_themerightcolumn')."\");");
	  }else{
        echo "</td><td style=\"padding-right: 10;\" align=\"center\" width = \"150\" valign=\"top\">\n";
	  }
      advblocks(right);
    }
   // end right column / close table
   echo "</td></tr></table>\n";
   // display footer
  if ($use_templates){
      eval("dooutput(\"".gettemplate('P_themefooter')."\");"); 
 }else{
    echo "
    <center><font size=1>\n
    $foot1<br>\n
    $foot2<br>\n
    $foot3<br>\n
    $foot4<br>\n
    </font></center>\n
    ";
 }
 echo "
</body>\n
</html>";

?>