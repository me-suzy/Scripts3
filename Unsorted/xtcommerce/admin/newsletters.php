<?php
/* --------------------------------------------------------------
   $Id: newsletters.php,v 1.1 2003/09/06 22:05:29 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(newsletters.php,v 1.15 2002/11/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (newsletters.php,v 1.10 2003/08/18); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'lock':
      case 'unlock':
        $newsletter_id = xtc_db_prepare_input($_GET['nID']);
        $status = (($_GET['action'] == 'lock') ? '1' : '0');

        xtc_db_query("update " . TABLE_NEWSLETTERS . " set locked = '" . $status . "' where newsletters_id = '" . xtc_db_input($newsletter_id) . "'");

        xtc_redirect(xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));
        break;
      case 'insert':
      case 'update':
        $newsletter_id = xtc_db_prepare_input($_POST['newsletter_id']);
        $newsletter_module = xtc_db_prepare_input($_POST['module']);
        $title = xtc_db_prepare_input($_POST['title']);
        $content = xtc_db_prepare_input($_POST['content']);

        $newsletter_error = false;
        if (empty($title)) {
          $messageStack->add(ERROR_NEWSLETTER_TITLE, 'error');
          $newsletter_error = true;
        }
        if (empty($module)) {
          $messageStack->add(ERROR_NEWSLETTER_MODULE, 'error');
          $newsletter_error = true;
        }

        if (!$newsletter_error) {
          $sql_data_array = array('title' => $title,
                                  'content' => $content,
                                  'module' => $newsletter_module);

          if ($_GET['action'] == 'insert') {
            $sql_data_array['date_added'] = 'now()';
            $sql_data_array['status'] = '0';
            $sql_data_array['locked'] = '0';

            xtc_db_perform(TABLE_NEWSLETTERS, $sql_data_array);
            $newsletter_id = xtc_db_insert_id();
          } elseif ($_GET['action'] == 'update') {
            xtc_db_perform(TABLE_NEWSLETTERS, $sql_data_array, 'update', 'newsletters_id = \'' . xtc_db_input($newsletter_id) . '\'');
          }

          xtc_redirect(xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletter_id));
        } else {
          $_GET['action'] = 'new';
        }
        break;
      case 'deleteconfirm':
        $newsletter_id = xtc_db_prepare_input($_GET['nID']);

        xtc_db_query("delete from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($newsletter_id) . "'");

        xtc_redirect(xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page']));
        break;
      case 'delete':
      case 'new': if (!$_GET['nID']) break;
      case 'send':
        $cs_audience='0';
      case 'confirm_send':
        $newsletter_id = xtc_db_prepare_input($_GET['nID']);

        $check_query = xtc_db_query("select locked from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($newsletter_id) . "'");
        $check = xtc_db_fetch_array($check_query);

        if ($check['locked'] < 1) {
          switch ($_GET['action']) {
            case 'delete': $error = ERROR_REMOVE_UNLOCKED_NEWSLETTER; break;
            case 'new': $error = ERROR_EDIT_UNLOCKED_NEWSLETTER; break;
            case 'send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
            case 'confirm_send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
          }
          $messageStack->add_session($error, 'error');
          xtc_redirect(xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']. '&cs_audience=' . $_GET['cs_audience']));
        }
        break;
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
<div id="spiffycalendar" class="text"></div>
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo xtc_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($_GET['action'] == 'new') {
    $form_action = 'insert';
    if ($_GET['nID']) {
      $nID = xtc_db_prepare_input($_GET['nID']);
      $form_action = 'update';

      $newsletter_query = xtc_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($nID) . "'");
      $newsletter = xtc_db_fetch_array($newsletter_query);

      $nInfo = new objectInfo($newsletter);
    } elseif ($_POST) {
      $nInfo = new objectInfo($_POST);
    } else {
      $nInfo = new objectInfo(array());
    }

    $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    $directory_array = array();
    if ($dir = dir(DIR_WS_MODULES . 'newsletters/')) {
      while ($file = $dir->read()) {
        if (!is_dir(DIR_WS_MODULES . 'newsletters/' . $file)) {
          if (substr($file, strrpos($file, '.')) == $file_extension) {
            $directory_array[] = $file;
          }
        }
      }
      sort($directory_array);
      $dir->close();
    }

    for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
      $modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
    }
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo xtc_draw_form('newsletter', FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&action=' . $form_action); if ($form_action == 'update') echo xtc_draw_hidden_field('newsletter_id', $nID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
            <td class="main"><?php echo xtc_draw_pull_down_menu('module', $modules_array, $nInfo->module); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_TITLE; ?></td>
            <td class="main"><?php echo xtc_draw_input_field('title', $nInfo->title, '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_NEWSLETTER_CONTENT; ?></td>
            <td class="main"><?php echo xtc_draw_textarea_field('content', 'soft', '100%', '20', $nInfo->content); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right"><?php echo (($form_action == 'insert') ? xtc_image_submit('button_save.gif', IMAGE_SAVE) : xtc_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } elseif ($_GET['action'] == 'preview') {
    $nID = xtc_db_prepare_input($_GET['nID']);

    $newsletter_query = xtc_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($nID) . "'");
    $newsletter = xtc_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);
?>
      <tr>
        <td align="right"><?php echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . xtc_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
      <tr>
        <td><tt><?php echo nl2br($nInfo->content); ?></tt></td>
      </tr>
      <tr>
        <td align="right"><?php echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . xtc_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } elseif ($_GET['action'] == 'send') {
    $nID = xtc_db_prepare_input($_GET['nID']);
    // add cs v1.1
    $cs_audience = xtc_db_prepare_input($_GET['cs_audience']);
    // End ad cs v1.1

    $newsletter_query = xtc_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($nID) . "'");
    $newsletter = xtc_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

    include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><?php if ($module->show_choose_audience) { echo $module->choose_audience(); } else { echo $module->confirm(); } ?></td>
      </tr>
<?php
  } elseif ($_GET['action'] == 'confirm') {
    $nID = xtc_db_prepare_input($_GET['nID']);

    $newsletter_query = xtc_db_query("select title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($nID) . "'");
    $newsletter = xtc_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

    include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><?php echo $module->confirm(); ?></td>
      </tr>
<?php
  } elseif ($_GET['action'] == 'confirm_send') {
    $nID = xtc_db_prepare_input($_GET['nID']);
    // Add v1.1
    $cs_audience = xtc_db_prepare_input($_GET['cs_audience']);
    // End add v1.1

    $newsletter_query = xtc_db_query("select newsletters_id, title, content, module from " . TABLE_NEWSLETTERS . " where newsletters_id = '" . xtc_db_input($nID) . "'");
    $newsletter = xtc_db_fetch_array($newsletter_query);

    $nInfo = new objectInfo($newsletter);

    include(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    include(DIR_WS_MODULES . 'newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.')));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="middle"><?php echo xtc_image(DIR_WS_IMAGES . 'ani_send_email.gif', IMAGE_ANI_SEND_EMAIL); ?></td>
            <td class="main" valign="middle"><b><?php echo TEXT_PLEASE_WAIT; ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php
  xtc_set_time_limit(0);
  flush();
  $module->send($nInfo->newsletters_id);
?>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><font color="#ff0000"><b><?php echo TEXT_FINISHED_SENDING_EMAILS; ?></b></font></td>
      </tr>
      <tr>
        <td><?php echo xtc_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . xtc_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWSLETTERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MODULE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $newsletters_query_raw = "select newsletters_id, title, length(content) as content_length, module, date_added, date_sent, status, locked from " . TABLE_NEWSLETTERS . " order by date_added desc";
    $newsletters_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsletters_query_raw, $newsletters_query_numrows);
    $newsletters_query = xtc_db_query($newsletters_query_raw);
    while ($newsletters = xtc_db_fetch_array($newsletters_query)) {
      if (((!$_GET['nID']) || (@$_GET['nID'] == $newsletters['newsletters_id'])) && (!$nInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
        $nInfo = new objectInfo($newsletters);
      }

      if ( (is_object($nInfo)) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id'] . '&action=preview') . '">' . xtc_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $newsletters['title']; ?></td>
                <td class="dataTableContent" align="right"><?php echo number_format($newsletters['content_length']) . ' bytes'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $newsletters['module']; ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['status'] == '1') { echo xtc_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK); } else { echo xtc_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS); } ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['locked'] > 0) { echo xtc_image(DIR_WS_ICONS . 'locked.gif', ICON_LOCKED); } else { echo xtc_image(DIR_WS_ICONS . 'unlocked.gif', ICON_UNLOCKED); } ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($nInfo)) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) { echo xtc_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '">' . xtc_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsletters_split->display_count($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsletters_split->display_links($newsletters_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'action=new') . '">' . xtc_image_button('button_new_newsletter.gif', IMAGE_NEW_NEWSLETTER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

      $contents = array('form' => xtc_draw_form('newsletters', FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $nInfo->title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . xtc_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . xtc_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($nInfo)) {
        $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

        if ($nInfo->locked > 0) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=new') . '">' . xtc_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=delete') . '">' . xtc_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . xtc_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a> <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=send') . '">' . xtc_image_button('button_send.gif', IMAGE_SEND) . '</a> <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=unlock') . '">' . xtc_image_button('button_unlock.gif', IMAGE_UNLOCK) . '</a>');
        } else {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . xtc_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a> <a href="' . xtc_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=lock') . '">' . xtc_image_button('button_lock.gif', IMAGE_LOCK) . '</a>');
        }
        $contents[] = array('text' => '<br>' . TEXT_NEWSLETTER_DATE_ADDED . ' ' . xtc_date_short($nInfo->date_added));
        if ($nInfo->status == '1') $contents[] = array('text' => TEXT_NEWSLETTER_DATE_SENT . ' ' . xtc_date_short($nInfo->date_sent));
//         echo '$newsletter_id =' . $newsletter_id . ' & $nInfo->newsletters_id = ' . $nInfo->newsletters_id . ' & $nInfo->title = ' . $nInfo->title;
        // Added CStatus_V1.1
        $newsletters_history_query = xtc_db_query("select news_hist_id, news_hist_cs, news_hist_cs_date_sent from " . TABLE_NEWSLETTERS_HISTORY . " where news_hist_id = '" . xtc_db_input($nInfo->newsletters_id) . "' order by news_hist_cs_date_sent desc");
        $contents[] = array('text' => '<table border="1" cellspacing="0" cellpadding="5"><tr><td class="smallText" align="center">' . TABLE_HEADING_NEWS_HIST_CS_VALUE .' </td><td class="smallText" align="center">' . TABLE_HEADING_NEWS_HIST_DATE_ADDED . '</td></tr>');
        if (xtc_db_num_rows($newsletters_history_query)) {
          while ($newsletters_history = xtc_db_fetch_array($newsletters_history_query)) {
            $contents[] = array('text' => '<tr>' . "\n" . '<td class="smallText">' . $customers_statuses_array[$newsletters_history['news_hist_cs']]['text'] . '</td>' . "\n" .'<td class="smallText" align="center">' . xtc_datetime_short($newsletters_history['news_hist_cs_date_sent']) . '</td>' . "\n" .'<td class="smallText" align="center">');
            $contents[] = array('text' => '</tr>' . "\n");
          }
        } else {
          $contents[] = array('text' => '<tr>' . "\n" . ' <td class="smallText" colspan="2">' . TEXT_NO_NEWSLETTERS_CS_HISTORY . '</td>' . "\n" . ' </tr>' . "\n");
        }
        $contents[] = array('text' => '</table>');
        // End Added CStatus_V1.1
      }
      break;
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