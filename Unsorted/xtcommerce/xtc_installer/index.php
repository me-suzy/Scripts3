<?php
  /* --------------------------------------------------------------
   $Id: index.php,v 1.1 2003/09/06 21:42:56 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (index.php,v 1.18 2003/08/17); www.nextcommerce.org
   
   Released under the GNU General Public License 
   --------------------------------------------------------------*/
   
  require('includes/application.php');

  // include needed functions
  require_once(DIR_FS_INC.'xtc_image.inc.php');
  require_once(DIR_FS_INC.'xtc_draw_separator.inc.php');
  require_once(DIR_FS_INC.'xtc_redirect.inc.php');
  require_once(DIR_FS_INC.'xtc_href_link.inc.php');
  
  include('language/english.php');
  
  // Include Developer - standart settings for installer
  //  require('developer_settings.php');  
  
 define('HTTP_SERVER','');
 define('DIR_WS_CATALOG','');

   $messageStack = new messageStack();

    $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;
        
        
        $_SESSION['language'] = xtc_db_prepare_input($_POST['LANGUAGE']);
                
    $error = false;


      if ( ($_SESSION['language'] != 'german') && ($_SESSION['language'] != 'english') ) {
        $error = true;

        $messageStack->add('index', SELECT_LANGUAGE_ERROR);
        }
        

                    if ($error == false) {
                        xtc_redirect(xtc_href_link('install_step1.php', '', 'NONSSL'));
                }
        }
        
        
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>XT-Commerce Installer - Welcome</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.messageStackError, .messageStackWarning { font-family: Verdana, Arial, sans-serif; font-weight: bold; font-size: 10px; background-color: #; }
-->
</style>
</head>

<body>
<table width="800" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="95" colspan="2" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1"><img src="images/logo.gif"></td>
          <td background="images/bg_top.jpg">&nbsp;</td>
        </tr>
      </table>
      
    </td>
  </tr>
  <tr> 
    <td width="180" valign="top" bgcolor="F3F3F3" style="border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; border-color: #6D6D6D;"> 
      <table width="180" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="17" background="images/bg_left_blocktitle.gif">
<div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><font color="FFAF00">xtc:</font><font color="#999999">Install</font></b></font></div></td>
        </tr>
        <tr> 
          <td bgcolor="F3F3F3" ><br> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="10">&nbsp;</td>
                <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/icons/arrow02.gif" width="13" height="6"><?php echo BOX_LANGUAGE; ?></font></td>
              </tr>
            </table>
            <br></td>
        </tr>
      </table>
    </td>
    <td align="right" valign="top" style="border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid; border-color: #6D6D6D;"> 
      <br>
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="images/title_index.gif" width="586" height="100" border="0"><br>
            <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br><br><?php echo TEXT_WELCOME_INDEX; ?></font></td>
        </tr>
      </table>
      <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/break-el.gif" width="100%" height="1"></font></p>
      <table width="98%" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr> 
          <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong><font size="2"><img src="images/icons/arrow-setup.jpg" width="16" height="16"> 
            <?php echo TITLE_SELECT_LANGUAGE; ?></font></strong><br>
            <img src="images/break-el.gif" width="100%" height="1"><br>
                                                        <?php
  if ($messageStack->size('index') > 0) {
?><br>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="f3f3f3">
            <tr>
              <td><?php echo $messageStack->output('index'); ?></td>
  </tr>
</table>


<?php
  }
?>
            </font> <form name="language" method="post" action="index.php">

              <table width="300" border="0" cellpadding="0" cellspacing="4">
                <tr> 
                  <td width="98"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/icons/arrow02.gif" width="13" height="6">German</font></td>
                  <td width="192"><img src="images/icons/icon-deu.gif" width="30" height="16"> 
                    <?php echo xtc_draw_radio_field_installer('LANGUAGE', 'german'); ?>
                  </td>
                </tr>
                <tr> 
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/icons/arrow02.gif" width="13" height="6">English</font></td>
                  <td><img src="images/icons/icon-eng.gif" width="30" height="16"> 
                    <?php echo xtc_draw_radio_field_installer('LANGUAGE', 'english'); ?> </td>
                </tr>
              </table>
                           
              <input type="hidden" name="action" value="process">
              <p> <input type="image" src="images/button_continue.gif" border="0" alt="Continue"> <br>
                <br>
              </p>
            </form>
            
          </td>
        </tr>
      </table></td>
  </tr>
</table>

<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo TEXT_FOOTER; ?>  </font></p>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  </font></p>
</body>
</html>

</body>
</html>
