<?php

// -----------------------------------------------------------------------------
//
// phpFaber TinyLink v.1.0
// Copyright(C), phpFaber LLC, 2004-2005, All Rights Reserved.
// E-mail: products@phpfaber.com
//
// All forms of reproduction, including, but not limited to, internet posting,
// printing, e-mailing, faxing and recording are strictly prohibited.
// One license required per site running phpFaber TinyLink.
// To obtain a license for using phpFaber TinyLink, please register at
// http://www.phpfaber.com/i/products/tinylink/
//
// 19:59 28.07.2005
//
// -----------------------------------------------------------------------------

require_once("../tiny_includes/config.php");

checkAdmin($HTTP_SESSION_VARS['inadmin']);

if($ad) updateAd($ad_text);

$smarty = new Smarty;
$smarty->template_dir = "$dir_ws/tiny_templates/admin";
$smarty->compile_dir = "$dir_ws/tiny_templates_c";
$smarty->assign("ad",getAd());
$smarty->display("ad.htm");

?>