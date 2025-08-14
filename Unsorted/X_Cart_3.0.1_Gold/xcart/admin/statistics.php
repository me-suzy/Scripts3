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
# $Id: statistics.php,v 1.8 2002/04/22 17:10:28 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

#
# Collect statistics information
#
$statistics["clients"] = array_pop(func_query_first("select count(login) from customers where usertype='C'"));
$statistics["providers"] = array_pop(func_query_first("select count(login) from customers where usertype='P'"));
$statistics["products"] = array_pop(func_query_first("select count(productid) from products"));
$statistics["categories"] = array_pop(func_query_first("select count(categoryid) from categories where category not like '%/%'"));
$statistics["subcategories"] = array_pop(func_query_first("select count(categoryid) from categories where category like '%/%'"));
$statistics["orders"] = array_pop(func_query_first("select count(orderid) from orders"));

$statistics["clients_last_month"] = array_pop(func_query_first("select count(login) from customers where usertype='C'"));
$statistics["providers_last_month"] = array_pop(func_query_first("select count(login) from customers where usertype='P'"));
$statistics["products_last_month"] = array_pop(func_query_first("select count(productid) from products"));
$statistics["orders_last_month"] = array_pop(func_query_first("select count(orderid) from orders"));

	if($active_modules["Advanced_Statistics"])
		include "../modules/Advanced_Statistics/display_stats.php";

#
# Assign Smarty variables and show template
#

$smarty->assign("statistics", $statistics);
$smarty->assign("main", "statistics");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
