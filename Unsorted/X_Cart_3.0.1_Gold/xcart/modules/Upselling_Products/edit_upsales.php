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
# $Id: edit_upsales.php,v 1.6 2002/04/22 17:10:30 mav Exp $
#
# Module process adding and deleting upsales links
#

#
# Insert upsales link into database 
#
if ($REQUEST_METHOD=="POST" && $mode == "upselling_links") {

	while(list($key,$val)=each($HTTP_POST_VARS))
    {
#
# Update upselling table
#
        if (strstr($key,"-")) {
            list($field,$prodid)=split("-",$key);
            db_query("update product_links set $field='$val' where productid2='$prodid'");
        }
    }

	if ($selected_productid && $productid!=$selected_productid) {

		db_query("INSERT INTO product_links (productid1, productid2) VALUES ('$productid', '$selected_productid')");

		if ($bi_directional == "on")
			db_query("INSERT INTO product_links (productid1, productid2) VALUES ('$selected_productid', '$productid')");
	}

	header("Location: product_modify.php?productid=$productid#product_links");
	exit;
}

#
# Deleting upsales link from database 
#
if ($mode == "del_upsale_link"){
	db_query("DELETE FROM product_links WHERE productid1='$productid' AND productid2='$product_link'");
	header("Location: product_modify.php?productid=$productid#product_links");
	exit;
}

#
# Select all linked products
#
$product_links = func_query("select product_links.orderby, products.productid, products.product from products, product_links where (products.productid=product_links.productid2) and (product_links.productid1='$productid') ORDER BY product_links.orderby");

$smarty->assign("product_links",$product_links);
?>
