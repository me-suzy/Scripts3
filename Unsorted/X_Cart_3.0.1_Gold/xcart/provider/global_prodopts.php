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
# $Id: global_prodopts.php,v 1.7 2002/05/29 07:08:35 lucky Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

#   
# Use this condition when single mode is disabled
#
$provider_condition=($single_mode?"":"and products.provider='$login'");

#
# Handle POST request
#
if ($REQUEST_METHOD=="POST" && !empty($categories)) {
#
# Select product IDs in category that belong to the provider
#
	$success = false;

	foreach($categories as $categoryid) {
		$productids = func_query("select products.productid from products where categoryid='$categoryid' $provider_condition");

		if ($mode=="product_options_modify" && !empty($optclassnew) && !empty($opttextnew)) {
			$success = true;

			if ($productids)
				foreach($productids as $productid)
					db_query("insert into product_options (productid, optclass, opttext, options, orderby) values ('$productid[productid]','$optclassnew','$opttextnew','$optionsnew','$orderbynew')");

		}
		elseif ($mode=="product_options_delete" && (!empty($optclassnew) || !empty($opttextnew) || !empty($optionsnew))) {

			$optclass_condition=(empty($optclassnew)?"":"and optclass='$optclassnew'");
			$opttext_condition=(empty($opttextnew)?"":"and opttext='$opttextnew'");
			$options_condition=(empty($optionsnew)?"":"and options='$optionsnew'");

			$success = true;
		
			if ($productids)
				foreach($productids as $productid)
					db_query("delete from product_options where productid='$productid[productid]' $optclass_condition $opttext_condition $options_condition");
		}
	}

	if($success) {
		header("Location: global_prodopts.php?mode=success");
		exit;
	}
}

#   
# Get non-empty categories for this provider intead of using
# require "../include/categories.php";
#
require "../include/categories.php";

$smarty->assign("main","product_options");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
