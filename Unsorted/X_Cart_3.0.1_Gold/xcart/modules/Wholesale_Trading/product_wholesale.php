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
# $Id: product_wholesale.php,v 1.7 2002/04/22 17:10:30 mav Exp $
#

if ($REQUEST_METHOD=="POST" && $mode=="wholesales_modify" && !empty($product_info)) {
#
# Add new wholesale price
#
        if (!empty($newquantity) and(($newquantity > 1) or ($membership != "")) ) db_query("insert into pricing (productid,quantity, price, membership) values('$productid','$newquantity','$newprice', '$membership')");

        while (list($key,$val) = each($HTTP_POST_VARS)) {
		if (strstr($key,"-")) {
            list($field,$qid)=split("-",$key);
            db_query("update pricing set $field='$val' where productid='$productid' and priceid='$qid'");
            }
        }
	header("Location: product_modify.php?productid=$productid#product_wholesale");
	exit;
}
elseif ($mode=="wholesales_delete") {
#
# Delete pricing
#
	db_query("delete from pricing where priceid='$priceid'");
	header("Location: product_modify.php?productid=$productid#product_wholesale");
	exit;
}

#
# Collect wholesale pricing data
#

$pricing = func_query("select priceid, quantity, price, membership from pricing where productid='$productid' order by membership, quantity");
$smarty->assign("pricing",$pricing);
?>
