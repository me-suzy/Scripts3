<?
// funkydunk 2003
// very simple routine to query the faqs

$query = "SELECT * FROM `xcart_faq` WHERE enabled = 'Y' order by orderby"; 
$faqs = func_query($query);
#
# Assign Smarty variables
#

$smarty->assign("faqs",$faqs);
?>
