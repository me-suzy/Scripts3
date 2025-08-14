<html>
<head>
<title>Unique Spider Agent Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php
require 'config/spider_config.php';
$connection = mysql_connect($db_host, $username, $pass);
mysql_select_db($db, $connection);
$query = "SELECT DISTINCT agent AS agent, COUNT(*) AS count FROM $tablename GROUP BY agent ORDER BY count DESC"; // SELECT owner, COUNT(*) FROM pet GROUP BY owner;
$result = mysql_query($query, $connection);
$totalrows = mysql_num_rows($result);
$query2 = "SELECT COUNT(*) AS count FROM $tablename";
$result2 = mysql_query($query2);
$total = mysql_result($result2, 0);
?>
<table width="98%" border="1" cellspacing="0" cellpadding="4">
    <tr bgcolor="#003366"> 
        <td colspan="2"><font color="#FF9900" size="2" face="Verdana, Arial, Helvetica, sans-serif">This 
            site has been Crawled by <strong><?php echo $totalrows;?></strong> 
            Unique Spiders a total of <strong><?php echo $total;?></strong> times.</font></td>
        <td align="center"><font color="#FF9900" size="1" face="Verdana, Arial, Helvetica, sans-serif">Times 
            Crawled</font></td>
    </tr>
    <?php
	$i =1;
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
	// Alternate row background colors
	if($bgcolor == "#CCCCCC"){$bgcolor = "#EEEEEE";}
    	else {$bgcolor = "#CCCCCC";}
	
	?>
    <tr> 
        <td bgcolor="<?php echo $bgcolor;?>" align="center"><?php echo $i;?></td>
        <td bgcolor="<?php echo $bgcolor;?>"><?php echo $row['agent']; ?></td>
        <td bgcolor="<?php echo $bgcolor;?>" align="center"><?php echo $row['count']; ?></td>
    </tr>
    <?php 
	$i++;
	} ?>
</table><br/>
<table border="1" align="center" cellpadding="4" cellspacing="0" bordercolor="#003366">
    <tr>
        <td align="center" bgcolor="#003366"><font color="#FF9900" size="2" face="Verdana, Arial, Helvetica, sans-serif">Unique Spider 
            Agents</font></td>
    </tr>
  <tr>
        
    <td align="center" valign="middle" bgcolor="#999999"><img src="spider_agent_graph.php" alt="Spider Agent Graph"> 
    </td>
	</tr>
</table>
</body>
</html>