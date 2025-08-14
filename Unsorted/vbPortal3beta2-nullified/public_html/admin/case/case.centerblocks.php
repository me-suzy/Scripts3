<?php
if(!eregi('admin.php', $PHP_SELF)){die('Access Denied');}
switch($op) {
	case 'CenterBlocksAdmin':
	case 'CenterBlocksAdd':
	case 'CenterBlocksEdit':
	case 'CenterBlocksEditSave':
	case 'CenterBlocksChangeStatus':
	case 'CenterBlocksDelete':
	case 'CenterBlocksOrder':
	case 'CenterHeadlinesAdmin':
	case 'CenterHeadlinesEdit':
	case 'CenterHeadlinesAdd':
	case 'CenterHeadlinesSave':
	case 'CenterHeadlinesDel':
		include 'admin/modules/centerblocks.php';
		break;
}
?>