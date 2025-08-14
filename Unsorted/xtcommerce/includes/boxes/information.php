<?php
/* -----------------------------------------------------------------------------------------
   $Id: information.php,v 1.1 2003/09/06 22:13:53 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(information.php,v 1.6 2003/02/10); www.oscommerce.com
   (c) 2003	 nextcommerce (information.php,v 1.8 2003/08/21); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
$content_string='';
$content_query=xtc_db_query("SELECT
 					content_id,
 					categories_id,
 					parent_id,
 					content_title,
 					content_group
 					FROM ".TABLE_CONTENT_MANAGER."
 					WHERE languages_id='".$_SESSION['languages_id']."'
 					and file_flag=0");
 while ($content_data=xtc_db_fetch_array($content_query)) {
 	
 $content_string .= '<a href="' . xtc_href_link(FILENAME_CONTENT,'coID='.$content_data['content_group']) . '">' . $content_data['content_title'] . '</a><br>';	
}

?>
<!-- information //-->


          <tr>
            <td><?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => BOX_HEADING_INFORMATION);

  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => '<a href="' . xtc_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a><br>'.
                                         $content_string);

  new infoBox($info_box_contents);
?></td>
          </tr>
<!-- information_eof //-->
