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
# $Id: stats.php,v 1.2 2002/04/22 17:10:29 mav Exp $
#

	$stats_info = array ();

	$result = func_query ("SELECT orders.*, partner_payment.* FROM orders, partner_payment WHERE orders.orderid=partner_payment.orderid AND partner_payment.login='$login'");
	$stats_info ["total_sales"] = count($result);

	$result = func_query ("SELECT orders.*, partner_payment.* FROM orders, partner_payment WHERE orders.orderid=partner_payment.orderid AND partner_payment.login='$login' AND orders.status!='P'");
	$stats_info ["unapproved_sales"] = count($result);

	$result = func_query_first ("SELECT SUM(partner_payment.commitions) AS numba FROM partner_payment, orders WHERE partner_payment.orderid=orders.orderid AND partner_payment.login='$login' AND orders.status='Q' AND partner_payment.paid!='Y'");
	$stats_info ["pending_commitions"] = ($result["numba"] ? $result["numba"] : "0.00");

	$result = func_query_first ("SELECT SUM(partner_payment.commitions) AS numba FROM partner_payment, orders WHERE partner_payment.orderid=orders.orderid AND partner_payment.login='$login' AND orders.status='P' AND partner_payment.paid!='Y'");
	$stats_info ["approved_commitions"] = ($result["numba"] ? $result["numba"] : "0.00");

	$result = func_query_first ("SELECT SUM(commitions) AS numba FROM partner_payment WHERE login='$login' AND paid='Y'");
	$stats_info ["paid_commitions"] = ($result["numba"] ? $result["numba"] : "0.00");

	$smarty->assign ("stats_info", $stats_info);
?>
