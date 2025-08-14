<?
umask(0);
#
# If we are in subdir of X-Cart dir, then include with '../'
# else include with './'
#
if (!@include("../Smarty-2.1.1/Smarty.class.php"))
	@include("./Smarty-2.1.1/Smarty.class.php");

#
# Smarty object for processing html templates
#
$smarty = new Smarty;

$smarty->template_dir = "../skin1";
$smarty->compile_dir = "../templates_c";
$smarty->config_dir = "../skin1";
$smarty->cache_dir = "../cache";
$smarty->secure_dir = "../skin1";
$smarty->debug_tpl="file:debug_templates.tpl";

$file_temp_dir=$smarty->compile_dir;

#
# Smarty object for processing mail templates
#
$mail_smarty = $smarty;

#
# WARNING :
# Please ensure that you have no whitespaces / empty lines below this message.
# Adding a whitespace or an empty line below this line will cause a PHP error.
#
?>
