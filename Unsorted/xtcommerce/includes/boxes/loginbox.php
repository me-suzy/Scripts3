<?php
/* -----------------------------------------------------------------------------------------
   $Id: loginbox.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (loginbox.php,v 1.10 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Loginbox V1.0        	Aubrey Kilian <aubrey@mycon.co.za>

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  require_once(DIR_FS_INC . 'xtc_image_submit.inc.php');
?>
<!-- loginbox //-->
<?php
  if (!xtc_session_is_registered('customer_id')) {
?>
          <tr>
            <td><?php
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => BOX_LOGINBOX_HEADING);

    new infoBoxHeading($info_box_contents, false, false);
    $loginboxcontent = '
<table border="0" width="100%" cellspacing="0" cellpadding="0"><form name="login" method="post" action="' . xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . '">
              <tr>
                <td align="left" class="main">' . BOX_LOGINBOX_EMAIL . '</td>
              </tr>
              <tr>
                <td align="left" class="main"><input type="text" name="email_address" maxlength="96" size="20" value=""></td>
              </tr>
              <tr>
                <td align="left" class="main">' . BOX_LOGINBOX_PASSWORD . '</td>
              </tr>
              <tr>
                <td align="left" class="main"><input type="password" name="password" maxlength="40" size="20" value=""></td>
              </tr>
              <tr>
                <td class="main" align="center">' . xtc_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . '</td>
              </tr>
            </form></table>
';

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => $loginboxcontent);
    new infoBox($info_box_contents);
?></td>
          </tr>
<?php
  }
?>
<!-- loginbox_eof //-->
