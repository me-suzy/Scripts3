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
# $Id: discounts.php,v 1.12 2002/04/22 17:10:30 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

#
# Use this condition when single mode is disabled
#
$provider_condition=($single_mode?"":"and provider='$login'");

if ($REQUEST_METHOD=="POST") {
	while(list($key,$val)=each($HTTP_POST_VARS))
	{
#
# Update discounts table
#
    	if (strstr($key,"-")) {
        	list($field,$discountid)=split("-",$key);
        	db_query("update discounts set $field='$val' where discountid='$discountid' $provider_condition");
    	}
	}
#
# Add new discount
#

    if ($discount_new!=0)
		db_query ("insert into discounts (minprice, discount, discount_type, provider, membership) values ('$minprice_new','$discount_new','$discount_type_new', '$login','$discount_membership_new')");
}

if ($mode=="delete") {
#
# Delete discount
#
    db_query("delete from discounts where discountid='$discountid' $provider_condition");
}

$discounts = func_query("select * from discounts where 1 $provider_condition order by minprice");

$smarty->assign("discounts", $discounts);
$smarty->assign("main","discounts");

@include "../modules/gold_display.php";
$smarty->display("provider/home.tpl");
?>
