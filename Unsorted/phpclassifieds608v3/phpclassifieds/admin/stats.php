<? require("admheader.php"); ?>
<!-- Table menu -->
<table border="1" cellpadding="3" cellspacing="0" width="100%">
<tr>
 <td bgcolor="lightgrey">
  &nbsp; Statistics 
 </td>
</tr>

<tr bgcolor="white">
 <td width="100%">
 
  <?

  $resultat = mysql_query ("select count(*) from $ads_tbl");
  $rad = mysql_fetch_array($resultat);
  $ads_total = $rad["count(*)"];

  $resultat = mysql_query ("select count(*) from $usr_tbl");
  $rad = mysql_fetch_array($resultat);
  $usr_total = $rad["count(*)"];

  $resultat = mysql_query ("select count(*) from $pic_tbl");
  $rad = mysql_fetch_array($resultat);
  $pic_total = $rad["count(*)"];

  $resultat = mysql_query ("select count(*) from $cat_tbl");
  $rad = mysql_fetch_array($resultat);
  $cat_total = $rad["count(*)"];

  $resultat = mysql_query ("select count(*) from template");
  $rad = mysql_fetch_array($resultat);
  $temp_total = $rad["count(*)"];

  print "<table>";
  print "<tr><td>";
  print " Ads Total: $ads_total <br />";
  print " User Total: $usr_total <br />";
  print " Picture Total: $pic_total <br />";
  print " Category Total: $cat_total <br />";
  print " Templates Total: $temp_total <br />";
  print "</td></tr>";
  print "</table>";


  $y = date("Y");
  $m = date("m");
  $d = date("d");
  $mysql_timestamp_date = "$y$m$d";
  $today = "$y";
  $m1 = $today. "01";
  $m2 = $today. "02";
  $m3 = $today. "03";
  $m4 = $today. "04";
  $m5 = $today. "05";
  $m6 = $today. "06";
  $m7 = $today. "07";
  $m8 = $today. "08";
  $m9 = $today. "09";
  $m10 = $today. "10";
  $m11 = $today. "11";
  $m12 = $today. "12";



  $resultat = mysql_query ("select count(*) from $ads_tbl where datestamp like '$y%'") or die(mysql_error());
  $rad = mysql_fetch_array($resultat);
  $total = $rad["count(*)"];
  $total_bredde = $total / 100;




function stats($yearandday, $monthname)
{
  global $h;
  global $total;
  global $ads_tbl;

  $resultat = mysql_query ("select count(*) from $ads_tbl  where datestamp like '$yearandday%'") or die(mysql_error());
  $rad = mysql_fetch_array($resultat);
  $antalldok = $rad["count(*)"];
  $h = (int)((($antalldok/$total)*100));
  $h_w = (int)((($antalldok/$total)*100)*6);
  print("<img src='graph.gif' align='left' height='5' width='$h%' alt='' />  <small>$antalldok ($h %)</small> </td>");

}

?>
<p />

 Ads month by month: 

<table border="0" cellpadding="2" width="100%">
  <tr>

    <td bgcolor="#000000" align="left" width="1"> <b>jan</b> </td>
    <td align="left"><? stats("$m1", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>feb</b> </td>
    <td align="left"><? stats("$m2", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>mar</b> </td>
    <td align="left"><? stats("$m3", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>apr</b> </td>
    <td align="left"><? stats("$m4", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>mai</b> </td>
    <td align="left"><? stats("$m5", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>jun</b> </td>
    <td align="left"><? stats("$m6", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>jul</b> </td>
    <td align="left"><? stats("$m7", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>aug</b> </td>
    <td align="left"><? stats("$m8", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>sep</b> </td>
    <td align="left"><? stats("$m9", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>okt</b> </td>
    <td align="left"><? stats("$m10", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>nov </b> </td>
    <td align="left"><? stats("$m11", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>des</b> </td>
    <td align="left"><? stats("$m12", "januar"); ?>
  </tr>
</table>
<!-- BRUKERE -->
<?

         $resultat = mysql_query ("select count(*) from $usr_tbl where registered like '$year%'") or die(mysql_error());
  $rad = mysql_fetch_array($resultat);
  $total = $rad["count(*)"];
$total_bredde = $total / 100;
function userstats($yearandday, $monthname)
{
  global $total;
        global $usr_tbl;
         $resultat = mysql_query ("select count(*) from $usr_tbl where registered like '$yearandday%'") or die(mysql_error());
  $rad = mysql_fetch_array($resultat);
  $antalldok = $rad["count(*)"];
        $h = ($antalldok/$total)*100;
        $h_w = (($antalldok/$total)*100)*6;
        print("<img src='graph.gif' align='left' height='5' width='$h%' alt='' />  $antalldok ($h %) </td>");

}

?>
<p /><small>Users month by month:</small>

<table border="0" cellpadding="2" width="100%">
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>jan</b> </td>
    <td align="left"><? userstats("$m1", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>feb</b> </td>
    <td align="left"><? userstats("$m2", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>mar</b> </td>
    <td align="left"><? userstats("$m3", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>apr</b> </td>
    <td align="left"><? userstats("$m4", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>mai</b> </td>
    <td align="left"><? userstats("$m5", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>jun</b> </td>
    <td align="left"><? userstats("$m6", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>jul</b> </td>
    <td align="left"><? userstats("$m7", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>aug</b> </td>
    <td align="left"><? userstats("$m8", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>sep</b> </td>
    <td align="left"><? userstats("$m9", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>okt</b> </td>
    <td align="left"><? userstats("$m10", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>nov </b> </td>
    <td align="left"><? userstats("$m11", "januar"); ?>
  </tr>
  <tr>
    <td bgcolor="#000000" align="left" width="1"> <b>des</b> </td>
    <td align="left"><? userstats("$m12", "januar"); ?>
  </tr>
</table>
         </td>
</tr>
</table>
<!-- END Table menu -->
<? require("admfooter.php"); ?>
