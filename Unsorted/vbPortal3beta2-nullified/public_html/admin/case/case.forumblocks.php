<?php
if(!eregi('admin.php', $PHP_SELF)){die('Access Denied');}
switch($op) {
	case 'ForumBlocksAdmin':
	case 'ForumBlocksAdd':
	case 'ForumBlocksEdit':
	case 'ForumBlocksEditSave':
	case 'ForumBlocksChangeStatus':
	case 'ForumBlocksDelete':
	case 'ForumBlocksOrder':
	case 'ForumHeadlinesAdmin':
	case 'ForumHeadlinesEdit':
	case 'ForumHeadlinesAdd':
	case 'ForumHeadlinesSave':
	case 'ForumHeadlinesDel':
		include 'admin/modules/forumblocks.php';
		break;
}
?>