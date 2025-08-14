<?php 
include($_SERVER['DOCUMENT_ROOT']."/classes/count_visitor_statistics/count_visitors_class.php");

$stats = new Count_visitors;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Show Visitors (test application)</title>
</head>
<body>
<h1>Visitor statistics (example)</h1>
<table width="480" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td width="20%">First visit at: </td>
    <td width="35%"><?php echo $stats->first_last_visit("first"); ?></td>
    <td width="20%">Visits today: </td>
    <td><?php echo $stats->show_visits_today(); ?></td>
  </tr>
  <tr>
    <td>Last visit at: </td>
    <td><?php echo $stats->first_last_visit("last"); ?></td>
    <td>Total visits: </td>
    <td><?php echo $stats->show_all_visits(); ?></td>
  </tr>
</table>
<?php echo $stats->stats_country(); ?>
<?php echo $stats->stats_totals(); ?>
<?php echo $stats->stats_top_referer(); ?>
<?php echo $stats->stats_monthly(date("m"), date("Y")); ?>
</body>
</html>
