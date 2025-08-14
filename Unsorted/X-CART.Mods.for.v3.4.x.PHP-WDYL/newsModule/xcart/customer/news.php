<?
// funkydunk 2003
// very simple routine to query the news items

$query = "SELECT * FROM `xcart_news` WHERE enabled = 'Y' order by orderby"; 
$news = func_query($query);
#
# Assign Smarty variables
#

$smarty->assign("news",$news);
?>
