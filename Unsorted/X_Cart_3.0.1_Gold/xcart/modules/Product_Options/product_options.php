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
# $Id: product_options.php,v 1.7 2002/05/20 06:55:19 lucky Exp $
#

if ($REQUEST_METHOD=="POST" && $mode=="product_options_modify" && !empty($product_info)) {

    while(list($key,$val)=each($HTTP_POST_VARS))
    {
#
# Update product options table
#
        if (strstr($key,"-")) {
            list($field,$optionid)=split("-",$key);
            db_query("update product_options set $field='$val' where optionid='$optionid'");
        }
    }

    if ($optclassnew && $opttextnew) 
        db_query("insert into product_options (productid, optclass, opttext, options, orderby) values ('$productid','$optclassnew','$opttextnew','$optionsnew','$orderbynew')");

#
# Update exceptions and/or add a new one
#
	if ($option_ex) {
		foreach ($option_ex as $key=>$value) {
			db_query ("UPDATE product_options_ex SET exception='$value' WHERE optionid='$key'");
		}
	}

	if ($opt_ex_new) {
		db_query ("INSERT INTO product_options_ex (productid, exception) VALUES ('$productid', '$opt_ex_new')");
	}

    header("Location: product_modify.php?productid=$productid#product_options");
    exit;

} elseif ($mode=="product_options_delete") {
	db_query("delete from product_options where optionid='$optionid'");
    header("Location: product_modify.php?productid=$productid#product_options");
    exit;
} elseif ($mode=="product_options_ex_delete") {
	db_query ("DELETE FROM product_options_ex WHERE optionid='$optionid'");
	header ("Location: product_modify.php?productid=$productid&option_ex_deleted");
	exit;
}

$product_options=func_query("select * from product_options where productid='$productid' order by orderby");

$product_options_ex = func_query ("SELECT * FROM product_options_ex WHERE productid='$productid' ORDER BY exception");

$smarty->assign("product_options", $product_options);
$smarty->assign("product_options_ex", $product_options_ex);
?>
