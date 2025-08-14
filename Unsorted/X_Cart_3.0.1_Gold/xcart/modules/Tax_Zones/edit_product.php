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
# $Id: edit_product.php,v 1.3 2002/04/22 17:10:30 mav Exp $
#

if ($REQUEST_METHOD == "POST" && $mode=="product_tax") {
	if (!empty ($product_rate_id)) {
		foreach ($product_rate_id as $key=>$dummy) {
			db_query ("UPDATE tax_rates SET zoneid='$product_zone[$key]', rate_a='$product_tax_a[$key]', rate_p='$product_tax_p[$key]' WHERE rateid='$key' AND productid='$productid'");
		}
	}

	if ($new_product_tax_a!=0 or $new_product_tax_p!=0) {
		db_query ("INSERT INTO tax_rates (productid, zoneid, rate_a, rate_p) VALUES ('$productid','$new_product_zone','$new_product_tax_a','$new_product_tax_p')");
	}

	header ("Location: product_modify.php?productid=$productid#tax_zones");
	exit;
}

if ($mode == "product_tax_delete") {
	db_query ("DELETE FROM tax_rates WHERE rateid='$rate_id' AND productid='$productid'");

	header ("Location: product_modify.php?productid=$productid#tax_zones");
	exit;
}

$product_taxes = func_query ("SELECT * FROM tax_rates WHERE productid='$productid'");
$smarty->assign ("product_taxes", $product_taxes);
?>
