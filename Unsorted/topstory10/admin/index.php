<?php
include("../inc/config.php");
include("header.php");
echo '<br><center class="heading">'.$tst["lang"]["welcomeText"].'</center><br><br>';
$actArt=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]." WHERE status='1'");
$totalAct=mysql_num_rows($actArt);
echo"<u><span class=\"heading\">".$tst["lang"]["statSummary"]."</span></u><br><br>";
echo $tst["lang"]["totalActive"]." : ".$totalAct;
$pasArt=mysql_query("SELECT * FROM ".$tst["tbl"]["articles"]." WHERE status='0'");
$totalPas=mysql_num_rows($pasArt);
echo "<br>".$tst["lang"]["totalPassive"]." : ".$totalPas;
$total=$totalAct+$totalPas;
echo"<br>".$tst["lang"]["totalArticles"]." : ".$total;
include("footer.php");