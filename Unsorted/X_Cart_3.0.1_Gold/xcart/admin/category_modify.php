<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 RRF.ru development. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The RRF.RU DEVELOPMENT forbids, under any circumstances, the unauthorized   |
| reproduction of software or use of illegally obtained software. Making      |
| illegal copies of software is prohibited. Individuals who violate copyright |
| law and software licensing agreements may be subject to criminal or civil   |
| action by the owner of the copyright.                                       |
|                                                                             |
| 1. It is illegal to copy a software, and install that single program for    |
| simultaneous use on multiple machines.                                      |
|                                                                             |
| 2. Unauthorized copies of software may not be used in any way. This applies |
| even though you yourself may not have made the illegal copy.                |
|                                                                             |
| 3. Purchase of the appropriate number of copies of a software is necessary  |
| for maintaining legal status.                                               |
|                                                                             |
| DISCLAIMER                                                                  |
|                                                                             |
| THIS SOFTWARE IS PROVIDED BY THE RRF.RU DEVELOPMENT TEAM ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL THE RRF.RU DEVELOPMENT TEAM OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is RRF.ru development.           |
| Portions created by RRF.ru development are Copyright (C) 2001-2002          |
| RRF.ru development. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: category_modify.php,v 1.31 2002/05/29 14:42:10 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

#
# Update category or create new
#

#
# Check fpr errors
#
if($REQUEST_METHOD=="POST" && $mode=="add" && (substr_count($category_name,"/") || empty($category_name))) {
#
# Error: Category name contains symbol "/"
#
	$smarty->assign("category_error","1");
}
elseif ($REQUEST_METHOD=="POST") {

	if (($userfile!="none")&&($userfile!="")) {
            move_uploaded_file($userfile, "$file_temp_dir/$userfile_name");
            $userfile="$file_temp_dir/$userfile_name";
            $fd = fopen($userfile, "rb");
            $image = addslashes(fread($fd, filesize($userfile)));
            fclose($fd);
            list($image_size, $image_x, $image_y) = get_image_size($userfile);
			unlink($userfile);
	}

	if($mode=="add" && !empty($cat) && !substr_count($category_name,"/")) {
#
# Get id, name and icon of parent category
#
			$category_data=func_query_first("select categories.categoryid, categories.category, icons.image, categories.image_x, categories.image_y, categories.order_by, categories.membership, icons.image_type from categories, icons where categories.categoryid='$cat' and categories.categoryid=icons.categoryid");
			if($category_data) {
				$parent_categoryid = $category_data["categoryid"];
				$parent_category = $category_data["category"];
				if($userfile=="none" || $userfile=="") {
					$image = $category_data["image"];
					$image_x = $category_data["image_x"];
					$image_y = $category_data["image_y"];
					$userfile_type = $category_data["image_type"];
					$image = addslashes($image);
				}
			}
#
# Create new category
#
		db_query("insert into categories (category, description, meta_tags, avail, order_by, membership) values ('$parent_category/$category_name', '$description', '$meta_tags', '$avail','$order_by','$cat_membership')");
		$cat = db_insert_id();

		db_query("UPDATE categories SET membership='$cat_membership' WHERE category LIKE '$parent_category/$category_name/%'");

        db_query("insert into icons (categoryid, image, image_type) values ('$cat', '$image', '$userfile_type')");

	}
	elseif ($mode=="add" && !substr_count($category_name,"/")) {
#
# Create new category with root parent
#
		db_query("insert into categories (category, description, meta_tags, avail, order_by, membership) values ('$category_name', '$description', '$meta_tags', '$avail','$order_by','$cat_membership')");
		$cat = db_insert_id();
		db_query ("UPDATE categories SET membership='$cat_membership' WHERE category LIKE '$category_name/%'");
        db_query("insert into icons (categoryid, image, image_type) values ('$cat', '$image', '$userfile_type')");
	} else  {
#
# Update existing category name
# and all subcategories names
#
		$categories_chain = explode("/",$category_name);
		$category_path = "";
#
# Move category feature
#
		foreach($categories_chain as $category_section) {
				$category_path .= $category_section;	
				if($category_path!=$category_name && !func_query_first("select * from categories where category='$category_path'"))
					db_query("insert into categories (category, description, meta_tags, avail, order_by, membership) values ('$category_path', '$description', '$meta_tags', '$avail','','$cat_membership')");
					db_query("UPDATE categories SET membership='$cat_membership' WHERE category LIKE '$category_path/%'");
				$category_path .= "/";	
		}

		$old_category_name = array_pop(func_query_first("select category from categories where categoryid='$cat'"));
		db_query("update categories set category='$category_name', description='$description', meta_tags='$meta_tags', avail='$avail', order_by='$order_by', membership='$cat_membership' where categoryid='$cat'");
		db_query("UPDATE categories SET membership='$cat_membership' WHERE category LIKE '$category_name/%'");

		db_query("update categories set category=replace(category,'$old_category_name','$category_name') where category like '$old_category_name/%'");
	}
#
# Insert category icon
#
    if (($userfile!="none")&&($userfile!=""))
        db_query("update icons set image='$image', image_type='$userfile_type' where categoryid='$cat'");

	header("Location: categories.php?cat=$parent_categoryid");
	exit;

}

require "../include/categories.php";

require "./location_ajust.php";

$smarty->assign("usertype",$usertype);
$smarty->assign("main","category_modify");
$smarty->assign("location",$location);

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
