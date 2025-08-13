<?PHP

	include "../../affconfig.php"; 

	print "<html>
<head>
<title>POST Affiliate Invoice</title>
<meta http-equiv=Content-Type content=text/html; charset=iso-8859-1>
</head>

<body bgcolor=#FFFFFF text=#000000>
<table width=600 border=0 cellspacing=0 cellpadding=0>
  <tr> 
    <td width=400> 
      <p>&nbsp;</p>
      <p><font face=Arial, Helvetica, sans-serif size=5><b><font size=6>"; 
      
      
      print $yoursitename;
      print "</font></b></font></p>
    </td>
    <td> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
  </tr>
  <tr> 
    <td width=400><font face=Arial, Helvetica, sans-serif size=4><b><font size=3>Affiliate 
      Program Payment Receipt</font></b></font></td>
    <td><font face=Arial, Helvetica, sans-serif></font></td>
  </tr>
  <tr> 
    <td width=400> 
      <p>&nbsp;</p>
      <p><font face=Arial, Helvetica, sans-serif>";
      
    
            
      mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 39)"); 
      $result = mysql_db_query($database, "select * from affiliates where refid = '".$_REQUEST['aff']."'") 
      or die ("Database INSERT Error"); 
  if (mysql_num_rows($result)) {
    while ($qry = mysql_fetch_array($result)) {
      print $qry[title];
      print " ";
      print $qry[firstname];
      print " ";
      print $qry[lastname];
      print "<br>";
      print $qry[company];
      print "<br>";
      print $qry[street];
      print "<br>";
      print $qry[town];
      print "<br>";
      print $qry[county];
      print "<br>";
      print $qry[postcode];
      print "<br>";
      print $qry[country];
    }
    }
    
    echo "</font></p>
    </td>
    <td><font face=Arial, Helvetica, sans-serif></font></td>
  </tr>
  <tr> 
    <td width=400><font face=Arial, Helvetica, sans-serif></font></td>
    <td> 
      <div align=right><font face=Arial, Helvetica, sans-serif>";
      
      print (date ("Y-m-d"));
      
      echo "</font></div>
    </td>
  </tr>
  <tr> 
    <td width=400> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
    <td><font face=Arial, Helvetica, sans-serif></font></td>
  </tr>
  <tr> 
    <td width=400><font face=Arial, Helvetica, sans-serif>Payment For:</font></td>
    <td><font face=Arial, Helvetica, sans-serif>Total Value:</font></td>
  </tr>
  <tr> 
    <td width=400><font face=Arial, Helvetica, sans-serif></font></td>
    <td> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
  </tr>
  <tr> 
    <td width=400><font face=Arial, Helvetica, sans-serif>Referral To Our 
      Site Resulting In Sales</font></td>
    <td><font face=Arial, Helvetica, sans-serif>$ ";
    
    {
  
	    mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 102)"); 
	    $set = mysql_db_query($database, "select SUM(payment) AS total from sales WHERE refid = '".$_REQUEST['aff']."'") 
        or die ("Database INSERT Error"); 
	    $row = mysql_fetch_array( $set );
	    print $row['total'];
 
    }
    
    echo "</font></td>
  </tr>
  <tr> 
    <td width=400> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
    <td><font face=Arial, Helvetica, sans-serif></font></td>
  </tr>
  <tr> 
    <td width=400> 
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td width=400>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width=400><font face=Arial, Helvetica, sans-serif><b>Thank you for 
      using ";
      print $yoursitename;
      print "!</b></font></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>";

?>