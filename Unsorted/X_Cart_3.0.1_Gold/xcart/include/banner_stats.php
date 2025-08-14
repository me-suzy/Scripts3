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
# $Id: banner_stats.php,v 1.2 2002/04/22 17:10:29 mav Exp $
#

	$banner_stats = array ();
# Get the current date, but one day before
	$c_date = getdate (time()-24*60*60);

# Fetch banner views
	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_views WHERE login='$login'");
	$banner_stats ["views_total"] = $result ["amount"];

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_views WHERE login='$login' AND add_date>'".mktime(0,0,0,0,0,$c_date["year"])."'");
	$banner_stats ["views_year"] = $result ["amount"];

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_views WHERE login='$login' AND add_date>'".mktime(0,0,0,$c_date["mon"],0,$c_date["year"])."'");
	$banner_stats ["views_month"] = $result ["amount"];

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_views WHERE login='$login' AND add_date>'".mktime(0,0,0,$c_date["mon"],$c_date["mday"],$c_date["year"])."'");
	$banner_stats ["views_day"] = $result ["amount"];

# Now fetch clicks
	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_clicks WHERE login='$login'");
	$banner_stats ["clicks_total"] = $result ["amount"];

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_clicks WHERE login='$login' AND add_date>'".mktime(0,0,0,0,0,$c_date["year"])."'");
	$banner_stats ["clicks_year"] = $result ["amount"];

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_clicks WHERE login='$login' AND add_date>'".mktime(0,0,0,$c_date["mon"],0,$c_date["year"])."'");
	$banner_stats ["clicks_month"] = $result ["amount"];	

	$result = func_query_first ("SELECT COUNT(*) AS amount FROM partner_clicks WHERE login='$login' AND add_date>'".mktime(0,0,0,$c_date["mon"],$c_date["mday"],$c_date["year"])."'");
	$banner_stats ["clicks_day"] = $result ["amount"];

	$smarty->assign ("banner_stats", $banner_stats);
?>
