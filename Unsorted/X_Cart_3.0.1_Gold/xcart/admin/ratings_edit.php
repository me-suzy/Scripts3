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
# $Id: ratings_edit.php,v 1.2 2002/04/22 17:10:28 mav Exp $
#
# This script allows administrator to browse thought templates tree
# and edit files (these files must be writable for httpd daemon).
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

if ($mode == "update") {
# Delete votes first
	if ($to_delete) {
		foreach ($to_delete as $key=>$value) {
			db_query ("DELETE FROM product_votes WHERE vote_id='$key'");
		}
	}
# Then update votes
	if ($update_votes) {
		foreach ($update_votes as $key=>$value) {
			db_query ("UPDATE product_votes SET vote_value='$value' WHERE vote_id='$key'");
		}
	}

	header ("Location: ratings_edit.php?sortby=$sortby&sortorder=$orderby&productid=$productid&ip=".urlencode($ip)."&page=$page");
	exit;
}

# sortorder & sortby
if ($sortorder != 0) {
	$sortorder = 1;
	$_sortorder = " DESC ";
} else {
	$sortorder = 0;
	$_sortorder = " ASC ";
}

if ($sortby == 'productid')
	$_sortby = " product_votes.productid ";
elseif ($sortby == 'product')
	$_sortby = " products.product ";
elseif ($sortby == 'ip')
	$_sortby = " product_votes.remote_ip ";
elseif ($sortby == 'vote')
	$_sortby = " product_votes.vote_value ";
else {
	$sortby = 'productid';
	$_sortby = " product_votes.productid ";
}

if ($productid) {
	$condition = " AND product_votes.productid='$productid' ";
	$smarty->assign ("product", func_query_first ("SELECT product FROM products WHERE productid='$productid'"));
} elseif ($ip)
	$condition = " AND product_votes.remote_ip='$ip' ";
else
	$condition = "";

$ratings_total = func_query ("SELECT * FROM product_votes WHERE 1=1 $condition");
$total_nav_pages = ceil(count($ratings_total)/$products_per_page)+1;
require "../include/navigation.php";

$ratings = func_query ("SELECT product_votes.*, products.* FROM product_votes, products WHERE product_votes.productid=products.productid $condition ORDER BY $_sortby $_sortorder LIMIT $first_page, $products_per_page");

$smarty->assign("navigation_script", "ratings_edit.php?sortby=$sortby&orderby=$orderby&productid=$productid&ip=$ip");

$smarty->assign ("ratings", $ratings);
$smarty->assign ("sortby", $sortby);
$smarty->assign ("sortorder", $sortorder);
$smarty->assign ("invsortorder", !$sortorder);
$smarty->assign ("productid", $productid);
$smarty->assign ("ip", $ip);

$smarty->assign ("main", "ratings_edit");

@include "../modules/gold_display.php";

$smarty->display("admin/home.tpl");
?>
