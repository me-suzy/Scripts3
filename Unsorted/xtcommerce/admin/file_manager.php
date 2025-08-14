<?php
/* --------------------------------------------------------------
   $Id: file_manager.php,v 1.1 2003/09/06 22:05:29 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(file_manager.php,v 1.39 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (file_manager.php,v 1.9 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  if (!isset($_SESSION['current_path'])) {
    $_SESSION['current_path'] = DIR_FS_DOCUMENT_ROOT;
  }

  if ($_GET['goto']) {
    $_SESSION['current_path'] = $_GET['goto'];
    xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));
  }

  if (strstr($_SESSION['current_path'], '..')) $_SESSION['current_path'] = DIR_FS_DOCUMENT_ROOT;

  if (!is_dir($_SESSION['current_path'])) $_SESSION['current_path'] = DIR_FS_DOCUMENT_ROOT;

  if (!ereg('^' . DIR_FS_DOCUMENT_ROOT, $_SESSION['current_path'])) $_SESSION['current_path'] = DIR_FS_DOCUMENT_ROOT;

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'reset':
        unset($_SESSION['current_path']);
        xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));
        break;

      case 'deleteconfirm':
        if (strstr($_GET['info'], '..')) xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));

        xtc_remove($_SESSION['current_path'] . '/' . $_GET['info']);
        if (!$xtc_remove_error) xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));
        break;

      case 'insert':
        if (mkdir($_SESSION['current_path'] . '/' . $_POST['folder_name'], 0777)) {
          xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_POST['folder_name'])));
        }
        break;

      case 'save':
        if ($fp = fopen($_SESSION['current_path'] . '/' . $_POST['filename'], 'w+')) {
          fputs($fp, stripslashes($_POST['file_contents']));
          fclose($fp);
          xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_POST['filename'])));
        }
        break;

      case 'processuploads':
        for ($i=1; $i<6; $i++) {
          if (isset($GLOBALS['file_' . $i]) && xtc_not_null($GLOBALS['file_' . $i])) {
            new upload('file_' . $i, $_SESSION['current_path']);
          }
        }

        xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));
        break;

      case 'download':
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . urldecode($_GET['filename']));
        readfile($_SESSION['current_path'] . '/' . urldecode($_GET['filename']));
        exit;
        break;

      case 'upload':
      case 'new_folder':
      case 'new_file':
        $directory_writeable = true;
        if (!is_writeable($_SESSION['current_path'])) {
          $directory_writeable = false;
          $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $_SESSION['current_path']), 'error');
        }
        break;

      case 'edit':
        if (strstr($_GET['info'], '..')) xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));

        $file_writeable = true;
        if (!is_writeable($_SESSION['current_path'] . '/' . $_GET['info'])) {
          $file_writeable = false;
          $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $_SESSION['current_path'] . '/' . $_GET['info']), 'error');
        }
        break;
      case 'delete':
        if (strstr($_GET['info'], '..')) xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));
        break;
    }
  }

  $in_directory = substr(substr(DIR_FS_DOCUMENT_ROOT, strrpos(DIR_FS_DOCUMENT_ROOT, '/')), 1);
  $current_path_array = explode('/', $_SESSION['current_path']);
  $document_root_array = explode('/', DIR_FS_DOCUMENT_ROOT);
  $goto_array = array(array('id' => DIR_FS_DOCUMENT_ROOT, 'text' => $in_directory));
  for ($i = 0, $n = sizeof($current_path_array); $i < $n; $i++) {
    if ($current_path_array[$i] != $document_root_array[$i]) {
      $goto_array[] = array('id' => implode('/', xtc_array_slice($current_path_array, 0, $i+1)), 'text' => $current_path_array[$i]);
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo xtc_draw_form('goto', FILENAME_FILE_MANAGER, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE . '<br><span class="smallText">' . $_SESSION['current_path'] . '</span>'; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_draw_separator('pixel_trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_draw_pull_down_menu('goto', $goto_array, $_SESSION['current_path'], 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>
      </tr>
<?php
  if ( ($directory_writeable) && ($_GET['action'] == 'new_file') || ($_GET['action'] == 'edit') ) {
    if (strstr($_GET['info'], '..')) xtc_redirect(xtc_href_link(FILENAME_FILE_MANAGER));

    if (!isset($file_writeable)) $file_writeable = true;
    $file_contents = '';
    if ($_GET['action'] == 'new_file') {
      $filename_input_field = xtc_draw_input_field('filename');
    } elseif ($_GET['action'] == 'edit') {
      if ($file_array = file($_SESSION['current_path'] . '/' . $_GET['info'])) {
        $file_contents = htmlspecialchars(implode('', $file_array));
      }
      $filename_input_field = $_GET['info'] . xtc_draw_hidden_field('filename', $_GET['info']);
    }
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo xtc_draw_form('new_file', FILENAME_FILE_MANAGER, 'action=save'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_FILE_NAME; ?></td>
            <td class="main"><?php echo $filename_input_field; ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FILE_CONTENTS; ?></td>
            <td class="main"><?php echo xtc_draw_textarea_field('file_contents', 'soft', '80', '20', $file_contents, (($file_writeable) ? '' : 'readonly')); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td align="right" class="main" colspan="2"><?php if ($file_writeable) echo xtc_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;'; echo '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info'])) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
    $showuser = (function_exists('posix_getpwuid') ? true : false);
    $contents = array();
    $dir = dir($_SESSION['current_path']);
    while ($file = $dir->read()) {
      if ( ($file != '.') && ($file != 'CVS') && ( ($file != '..') || ($_SESSION['current_path'] != DIR_FS_DOCUMENT_ROOT) ) ) {
        $file_size = number_format(filesize($_SESSION['current_path'] . '/' . $file)) . ' bytes';

        $permissions = xtc_get_file_permissions(fileperms($_SESSION['current_path'] . '/' . $file));
        if ($showuser) {
          $user = @posix_getpwuid(fileowner($_SESSION['current_path'] . '/' . $file));
          $group = @posix_getgrgid(filegroup($_SESSION['current_path'] . '/' . $file));
        } else {
          $user = $group = array();
        }

        $contents[] = array('name' => $file,
                            'is_dir' => is_dir($_SESSION['current_path'] . '/' . $file),
                            'last_modified' => strftime(DATE_TIME_FORMAT, filemtime($_SESSION['current_path'] . '/' . $file)),
                            'size' => $file_size,
                            'permissions' => $permissions,
                            'user' => $user['name'],
                            'group' => $group['name']);
      }
    }

    function xtc_cmp($a, $b) {
      return strcmp( ($a['is_dir'] ? 'D' : 'F') . $a['name'], ($b['is_dir'] ? 'D' : 'F') . $b['name']);
    }
    usort($contents, 'xtc_cmp');
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILENAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PERMISSIONS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_USER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_GROUP; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAST_MODIFIED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  for ($i = 0, $n = sizeof($contents); $i < $n; $i++) {
    if (((!$_GET['info']) || (@$_GET['info'] == $contents[$i]['name'])) && (!$fInfo) && ($_GET['action'] != 'upload') && ($_GET['action'] != 'new_folder') ) {
      $fInfo = new objectInfo($contents[$i]);
    }

    if ($contents[$i]['name'] == '..') {
      $goto_link = substr($_SESSION['current_path'], 0, strrpos($_SESSION['current_path'], '/'));
    } else {
      $goto_link = $_SESSION['current_path'] . '/' . $contents[$i]['name'];
    }

    if ( (is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ) {
      if ($fInfo->is_dir) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'">' . "\n";
        $onclick_link = 'goto=' . $goto_link;
      } else {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'">' . "\n";
        $onclick_link = 'info=' . urlencode($fInfo->name) . '&action=edit';
      }
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
      $onclick_link = 'info=' . urlencode($contents[$i]['name']);
    }

    if ($contents[$i]['is_dir']) {
      if ($contents[$i]['name'] == '..') {
        $icon = xtc_image(DIR_WS_ICONS . 'previous_level.gif', ICON_PREVIOUS_LEVEL);
      } else {
        $icon = ((is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ? xtc_image(DIR_WS_ICONS . 'current_folder.gif', ICON_CURRENT_FOLDER) : xtc_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER));
      }
      $link = xtc_href_link(FILENAME_FILE_MANAGER, 'goto=' . $goto_link);
    } else {
      $icon = xtc_image(DIR_WS_ICONS . 'file_download.gif', ICON_FILE_DOWNLOAD);
      $link = xtc_href_link(FILENAME_FILE_MANAGER, 'action=download&filename=' . urlencode($contents[$i]['name']));
    }
?>
                <td class="dataTableContent" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><?php echo '<a href="' . $link . '">' . $icon . '</a>&nbsp;' . $contents[$i]['name']; ?></td>
                <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><?php echo ($contents[$i]['is_dir'] ? '&nbsp;' : $contents[$i]['size']); ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><tt><?php echo $contents[$i]['permissions']; ?></tt></td>
                <td class="dataTableContent" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><?php echo $contents[$i]['user']; ?></td>
                <td class="dataTableContent" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><?php echo $contents[$i]['group']; ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo xtc_href_link(FILENAME_FILE_MANAGER, $onclick_link); ?>'"><?php echo $contents[$i]['last_modified']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($contents[$i]['name'] != '..') echo '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($contents[$i]['name']) . '&action=delete') . '">' . xtc_image(DIR_WS_ICONS . 'delete.gif', ICON_DELETE) . '</a>&nbsp;'; if (is_object($fInfo) && ($fInfo->name == $contents[$i]['name'])) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($contents[$i]['name'])) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr valign="top">
                    <td class="smallText"><?php echo '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'action=reset') . '">' . xtc_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td class="smallText" align="right"><?php echo '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info']) . '&action=upload') . '">' . xtc_image_button('button_upload.gif', IMAGE_UPLOAD) . '</a>&nbsp;<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info']) . '&action=new_file') . '">' . xtc_image_button('button_new_file.gif', IMAGE_NEW_FILE) . '</a>&nbsp;<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info']) . '&action=new_folder') . '">' . xtc_image_button('button_new_folder.gif', IMAGE_NEW_FOLDER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($_GET['action']) {
      case 'delete':
        $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

        $contents = array('form' => xtc_draw_form('file', FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name) . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_DELETE_INTRO);
        $contents[] = array('text' => '<br><b>' . $fInfo->name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . xtc_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name)) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'new_folder':
        $heading[] = array('text' => '<b>' . TEXT_NEW_FOLDER . '</b>');

        $contents = array('form' => xtc_draw_form('folder', FILENAME_FILE_MANAGER, 'action=insert'));
        $contents[] = array('text' => TEXT_NEW_FOLDER_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_FILE_NAME . '<br>' . xtc_draw_input_field('folder_name'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . (($directory_writeable) ? xtc_image_submit('button_save.gif', IMAGE_SAVE) : '') . ' <a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info'])) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'upload':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_UPLOAD . '</b>');

        $contents = array('form' => xtc_draw_form('file', FILENAME_FILE_MANAGER, 'action=processuploads', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_UPLOAD_INTRO);
        for ($i=1; $i<6; $i++) $file_upload .= xtc_draw_file_field('file_' . $i) . '<br>';
        $contents[] = array('text' => '<br>' . $file_upload);
        $contents[] = array('align' => 'center', 'text' => '<br>' . (($directory_writeable) ? xtc_image_submit('button_upload.gif', IMAGE_UPLOAD) : '') . ' <a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($_GET['info'])) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if (is_object($fInfo)) {
          $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

          if (!$fInfo->is_dir) $contents[] = array('align' => 'center', 'text' => '<a href="' . xtc_href_link(FILENAME_FILE_MANAGER, 'info=' . urlencode($fInfo->name) . '&action=edit') . '">' . xtc_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_FILE_NAME . ' <b>' . $fInfo->name . '</b>');
          if (!$fInfo->is_dir) $contents[] = array('text' => '<br>' . TEXT_FILE_SIZE . ' <b>' . $fInfo->size . '</b>');
          $contents[] = array('text' => '<br>' . TEXT_LAST_MODIFIED . ' ' . $fInfo->last_modified);
        }
    }

    if ( (xtc_not_null($heading)) && (xtc_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>