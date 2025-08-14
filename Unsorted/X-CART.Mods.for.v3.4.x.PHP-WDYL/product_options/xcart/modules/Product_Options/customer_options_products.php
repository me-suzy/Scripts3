<?
/*****************************************************************************\
+-----------------------------------------------------------------------------+
| X-Cart                                                                      |
| Copyright (c) 2001-2002 Ruslan R. Fazliev. All rights reserved.            |
+-----------------------------------------------------------------------------+
| The Ruslan R. Fazliev forbids, under any circumstances, the unauthorized   |
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
| THIS SOFTWARE IS PROVIDED BY Ruslan R. Fazliev ``AS IS'' AND ANY  |
| EXPRESSED OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED |
| WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE      |
| DISCLAIMED.  IN NO EVENT SHALL Ruslan R. Fazliev OR ITS           |
| CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,       |
| EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,         |
| PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; |
| OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,    |
| WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR     |
| OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF      |
| ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.                                  |
|                                                                             |
| The Initial Developer of the Original Code is Ruslan R. Fazliev.           |
| Portions created by Ruslan R. Fazliev are Copyright (C) 2001-2002          |
| Ruslan R. Fazliev. All Rights Reserved.                                    |
+-----------------------------------------------------------------------------+
\*****************************************************************************/

#
# $Id: customer_options.php,v 1.8 2002/11/14 07:58:20 zorg Exp $
#

if ($err == "options") {
	$options_ex = func_query ("SELECT * FROM $sql_tbl[product_options_ex] WHERE productid='$productid'");
	$smarty->assign ("options_ex", $options_ex);
    $smarty->assign ("err", $err);
}

$product_option_lines = func_query("select * from $sql_tbl[product_options] where productid='$productid' order by orderby");

$product_options_js = func_query_first("select javascript_code from $sql_tbl[product_options_js] where productid='$productid'");

$product_options = array();

if($product_option_lines)
	foreach($product_option_lines as $product_option_line) {
		$product_options[] = array_merge($product_option_line,array("options" => func_parse_options($product_option_line["options"])));
	}

$smarty->assign("product_options",$product_options);
$smarty->assign("javascript_code", $product_options_js["javascript_code"]);
?>