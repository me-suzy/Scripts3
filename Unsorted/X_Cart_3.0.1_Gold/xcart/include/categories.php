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
# $Id: categories.php,v 1.34 2002/06/03 11:56:44 lucky Exp $
#

#
# For users some categories may be disabled
#

if ($current_area == "C") {
	$membership_condition = " AND (categories.membership='$user_account[membership]' OR categories.membership='') ";
} else {
	$membership_condition = "";
}

$categories_data = func_query("select categories.*, 0 as product_count from categories ".($current_area=="C"?"where categories.avail='Y' $membership_condition ":"")." group by categories.categoryid order by ".($current_area=="C" ? "order_by" : "category"));

#
# Put all categories into $all_categories array and find current_category
# by categoryid stored in $cat
#
if(!$categories_data) $categories_data = array();

$all_categories = $categories_data;

foreach($categories_data as $category_data) {
	if ($category_data["categoryid"]==$cat) {
		$current_category = $category_data;
		$current_category["product_count"] = array_pop(func_query_first("SELECT COUNT(*) FROM products WHERE (categoryid=$category_data[categoryid] OR categoryid1=$category_data[categoryid] OR categoryid2=$category_data[categoryid] OR categoryid3=$category_data[categoryid]) AND avail>0"));
	}
}

#
# Put all root categories to $categories array
# Put all subcategories of current_category to $categories array
#

foreach($all_categories as $all_category) {

	$category=$all_category["category"];

	$cur_dir_len = strlen($current_category["category"]);

	if(!strstr($category,"/")) {
            $categories[]=$all_category;
			if(empty($current_category)) { 
				$all_category["product_count"]=array_pop(func_query_first("SELECT COUNT(*) FROM products WHERE (categoryid='$all_category[categoryid]' OR categoryid1='$all_category[categoryid]' OR categoryid2='$all_category[categoryid]' OR categoryid3='$all_category[categoryid]')"));
				$subcategories[]=$all_category;
			}
	}

	if(substr($category,0,$cur_dir_len+1) == $current_category["category"]."/" and $category!=$current_category["category"]) 
		if(!strstr(substr($category,$cur_dir_len+1),"/")) {
			$all_category["product_count"]=array_pop(func_query_first("select count(*) from products where categoryid='$all_category[categoryid]' OR categoryid1='$all_category[categoryid]' OR categoryid2='$all_category[categoryid]' OR categoryid3='$all_category[categoryid]'"));
			$all_category["category"]=ereg_replace("^.*/","",$all_category["category"]);
			$subcategories[]=$all_category;
		}

}

#
# Generate category sequence, i.e.
# Books, Books/Poetry, Books/Poetry/Philosophy ...
#

$current_category_array = explode("/",$current_category["category"]);
$prev = $current_category_array[0];
list($key,$val)=each($current_category_array);

$new_array = array($val);

while(list($key,$val)=each($current_category_array)) {
	$new_array[] = $prev."/".$val;
	$prev = $prev."/".$val;
}
unset($current_category_array);

#
# Generate array for displaying categories sequence in location
#

$category_location=array();
reset($all_categories);

$my_cats = array ();

foreach($all_categories as $all_category) {

	$categoryid=$all_category["categoryid"];
	$category=$all_category["category"];

	$my_cats [$categoryid] = $category;
}

asort ($my_cats);

foreach ($my_cats as $categoryid => $category) {
	reset ($new_array);
	while(list($key,$val)=each($new_array))
		if ($val==$category) $category_location[]=array(ereg_replace(".*/","",$val),"home.php?cat=".$categoryid);
}

#
# Assign Smarty variables
#

$smarty->assign("allcategories",$all_categories);
$smarty->assign("categories",$categories);
$smarty->assign("subcategories",$subcategories);
$smarty->assign("current_category",$current_category);
$smarty->assign("cat",$cat);
?>
