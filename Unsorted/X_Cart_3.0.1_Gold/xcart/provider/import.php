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
# $Id: import.php,v 1.14 2002/05/22 11:21:01 verbic Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

function func_create_category ($c) {
	$pos = strpos ($c, "/");
	for ($i=0; $i<substr_count($c, "/"); $i++) {
		$fc = substr ($c, 0, $pos);
		$result = func_query_first ("SELECT * FROM categories WHERE category='$fc'");
		if (!$result) {
			db_query ("INSERT INTO categories (category) VALUES ('$fc')");
			$id = db_insert_id ();
			db_query ("INSERT INTO icons (categoryid) VALUES ('$id')");
		}

		$pos ++;

		while (($pos < strlen ($c)) and (substr ($c, $pos, 1) != "/")) {
			$pos ++;
		}
	}
	$result = func_query_first ("SELECT * FROM categories WHERE category='$c'");
	if ($result)
		return $result["categoryid"];
	else {
		db_query ("INSERT INTO categories (category) VALUES ('$c')");
			$id = db_insert_id ();
		db_query ("INSERT INTO icons (categoryid) VALUES ('$id')");

		return $id;
	}
}

#
# CSV import facility, default delimiter '\t'
#
$quote_symbol="\"";

if (substr ($images_directory, -1, 1) != '/')
	$images_directory .= '/';

$insert_string="insert into products (provider,add_date";

if ($REQUEST_METHOD=="POST") {

	$category_index = -1;
	$price_index = -1;
	$thumbnail_index = -1;
#
# Generate insert string
#
	$provider_condition=($single_mode?"":" and products.provider='$login'");
	

	if ($delete_products=='yes') {
		$products_to_delete = func_query ("SELECT productid FROM products$provider_condition");
		foreach ($products_to_delete as $value) {
			func_delete_product ($value["productid"]);
		}
	}

	foreach($HTTP_POST_VARS as $key => $val) {
		if ($val=="category") {
			$category_index=$key;
			$val="categoryid";
		}
		if ($val == "price")
			$price_index = $key;
		elseif ($val == "thumbnail")
			$thumbnail_index = $key;
		elseif (is_numeric($key) && !empty($val))
			$insert_string.=",$val";
	}
	if ($category_index==-1)
		$insert_string.=",categoryid";
	$insert_string.=") values ('$login','".time()."'";

# categoryid keeps ID of the default category
	
#
# Read file line by line
#
# Then explode by delimiter, chop first and last ", remove "" dups, add slashes
# and append to $insert_string
#
	$entries=0;
	if ($localfile)
		$userfile = $localfile;
	if($fp = @fopen($userfile,"r")) {
		while ($columns = fgetcsv ($fp, 65536, $delimiter)) {
			$_insert_string=$insert_string;
			$price = 0; # default price
			$thumbnail = ""; # Empty thumbnail by default
			foreach($columns as $key => $column) {
				if ($key==$price_index) {
					$price=$column;
				} elseif ($key == $thumbnail_index) {
					$thumbnail = $column;
				} elseif (!empty($HTTP_POST_VARS[$key])) {
					$column = addslashes ($column);
					if ($key==$category_index) {
						if (empty ($column)) {  # Empty category, use default
							$column = $categoryid;
						} else {
							$column = func_create_category ($column);
						}
					}
				
					$_insert_string.=",'$column'";
				}
			}
			if ($category_index==-1) {
				$_insert_string.=",'$categoryid'";
			}
			$_insert_string.=")";
			#echo $_insert_string."<BR>";
			db_query($_insert_string);
			$entries++;
			$productid = db_insert_id();
#
# Insert pricing info
#
			if ($productid!=0) {
				db_query("insert into pricing (productid,quantity,price) values ('$productid','1','$price')");
				# If not empty thumbnail then insert it!
				if ($thumbnail) {
					$tfp = fopen ($images_directory.$thumbnail, "rb");
					if ($tfp) {
						$thumbnail_content = addslashes (fread ($tfp, 65536));
						$thumbnail_ext = substr ($thumbnail, -3, 3);
						if (strcasecmp ($thumbnail_ext, "jpg") == 0)
							$thumbnail_type = "image/jpeg";
						elseif (strcasecmp ($thumbnail_ext, "gif") == 0)
							$thumbnail_type = "image/gif";
						else
							$thumbnail_type = "image/$thumbnail_ext";
						db_query ("INSERT INTO thumbnails (productid, image, image_type) VALUES ('$productid', '$thumbnail_content', '$thumbnail_type')");
						fclose ($tfp);
					}
				}
			} else {
				header("Location: error_message.php?import_error"); 
				exit; 
			}
		}
		fclose($fp);
	}
	else 
	{ header("Location: error_message.php?import_error"); exit; }
	header("Location: import.php?mode=imported&entries=$entries");
	exit;
}

#
# Obtain columns from table products
#

$columns = func_query("show columns from products");

foreach($columns as $key => $column) {
	if ($column["Field"]!="productid" && $column["Field"]!="provider" && $column["Field"]!="categoryid" && $column["Field"]!="image_x" && $column["Field"]!="image_y" && $column["Field"]!="add_date" && $column["Field"]!="rating") $mycolumns[]=$column;
}

require "../include/categories.php";

$smarty->assign("columns",$mycolumns);
$smarty->assign("main","import");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
