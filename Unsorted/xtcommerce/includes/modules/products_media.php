<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_media.php,v 1.1 2003/09/06 22:13:54 fanta2k Exp $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (products_media.php,v 1.8 2003/08/25); www.nextcommerce.org
   
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/ 

// check if allowed to see
require_once(DIR_FS_INC.'xtc_in_array.inc.php');
$check_query=xtc_db_query("SELECT DISTINCT
				products_id
				FROM ".TABLE_PRODUCTS_CONTENT."
				WHERE languages_id='".$_SESSION['languages_id']."'");
$check_data=xtc_db_fetch_array($check_query);
if (xtc_in_array($_GET['products_id'],$check_data)) {

// get content data

require_once(DIR_FS_INC.'xtc_filesize.inc.php');

//get download
$content_query=xtc_db_query("SELECT
				content_id,
				content_name,
				content_link,
				content_file,
				file_comment
				FROM ".TABLE_PRODUCTS_CONTENT."
				WHERE
				products_id='".$_GET['products_id']."' AND
				languages_id='".$_SESSION['languages_id']."'");

				

?>
<!-- products_media //-->
<br>
<div class="pageHeading"><?php echo TITLE_MEDIA_CONTENT; ?></div>
<br>
<table width="100%" cellspacing="0" cellpadding="0">
 <tr>
   <td class="main">
   <table border="0" width="100%" cellspacing="2" cellpadding="0">
   <tr>
   <td  width="1" class="infoBoxHeading">&nbsp;</td>
   <td width="100%" class="infoBoxHeading">Datei</td>
   <td align="right" width="1" class="infoBoxHeading">Dateigröße</td>
   <td align="right" width="1" class="infoBoxHeading">&nbsp;</td>
   
   </tr>
<?php
	while ($content_data=xtc_db_fetch_array($content_query)) {
		
		
		
	echo '<tr><td width="35" style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="top">';
	if ($content_data['content_link']!='')	{
	echo xtc_image(DIR_WS_CATALOG.'admin/images/icons/icon_link.gif');
	} else {
	
	echo xtc_image(DIR_WS_CATALOG.'admin/images/icons/icon_'.str_replace('.','',strstr($content_data['content_file'],'.')).'.gif');
	}
	echo '</td>';
	
	// filename
	echo '<td style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="top" class="main">';
	
	
	if ($content_data['content_link']!='')	echo '<a href="'.$content_data['content_link'].'" target="new">';
	echo $content_data['content_name'];
	if ($content_data['content_link']!='') echo '</a>';
	
	// file comment
	echo '<br><i>';
	echo $content_data['file_comment'];
	echo '</i></td>';
	
		// filesize
	echo '<td style="border-bottom: 1px solid; border-color: #f1f1f1;" valign="top" class="main">';
	if ($content_data['content_file']!='')	echo xtc_filesize($content_data['content_file']);
	echo '&nbsp;</td>';
	
	// buttons
	echo '<td valign="center" align="right" style="border-bottom: 1px solid; border-color: #f1f1f1;">';
	
	if ($content_data['content_link']=='') {
	if (eregi('.html',$content_data['content_file']) 
	or eregi('.htm',$content_data['content_file'])	
	or eregi('.txt',$content_data['content_file'])
	or eregi('.bmp',$content_data['content_file'])
	or eregi('.jpg',$content_data['content_file'])
	or eregi('.gif',$content_data['content_file'])
	or eregi('.png',$content_data['content_file'])
	or eregi('.tif',$content_data['content_file'])
	) 
	{
	
	?>
	 <a style="cursor:hand" onClick="javascript:window.open('<?php echo xtc_href_link(FILENAME_MEDIA_CONTENT,'coID='.$content_data['content_id']); ?>', 'popup', 'toolbar=0, width=640, height=600')">
	 <?php
	 echo xtc_image_button('button_view.gif',TEXT_VIEW).'</a>';
	
	} else {
	//echo '<a href="'.xtc_href_link(FILENAME_PRODUCT_INFO,'action=get_download&products_id='.$_GET['products_id'].'&cID='.$content_data['content_id']).'">'.xtc_image_button('button_download.gif',TEXT_DOWNLOAD).'</a>';	
	echo '<a href="'.xtc_href_link('media/products/'.$content_data['content_file']).'">'.xtc_image_button('button_download.gif',TEXT_DOWNLOAD).'</a>';	
	
	}
	}	
	echo '&nbsp;</td>';
	
	
	echo '</tr>';
	} 
?>  
    </table>
   </td>
 </tr>
</table>
<!-- products_media_off //-->
<?php
}
?>
