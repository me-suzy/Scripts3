<?php
if(!eregi('admin.php', $PHP_SELF)){die('Access Denied');}
switch($op) {
	case 'AdvBlocksAdmin':
	case 'AdvBlocksAdd':
	case 'AdvBlocksEdit':
	case 'AdvBlocksEditSave':
	case 'AdvBlocksChangeStatus':
	case 'AdvBlocksDelete':
	case 'AdvBlocksOrder':
	case 'AdvHeadlinesAdmin':
	case 'AdvHeadlinesEdit':
	case 'AdvHeadlinesAdd':
	case 'AdvHeadlinesSave':
	case 'AdvHeadlinesDel':
		include 'admin/modules/advblocks.php';
		break;
}
?>