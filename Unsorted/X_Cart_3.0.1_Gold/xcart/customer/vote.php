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
# $Id: vote.php,v 1.2 2002/04/22 17:10:29 mav Exp $
#

	if (($mode=='vote') and ($productid) and ($vote>=1) and ($vote<=5)) {
		$result = func_query_first ("SELECT * FROM product_votes WHERE remote_ip='$REMOTE_ADDR' AND productid='$productid'");
		if ($result) {
			header ("Location: error_message.php?error_already_voted");
			exit;
		} else {
			db_query ("INSERT INTO product_votes (remote_ip, vote_value, productid) VALUES ('$REMOTE_ADDR','$vote', '$productid')");
			header ("Location: product.php?productid=$productid");
			exit;
		}
	} elseif (($mode=='review') and ($productid)) {
		$result = func_query_first ("SELECT * FROM product_reviews WHERE remote_ip='$REMOTE_ADDR' AND productid='$productid'");
		if ($result) {
			header ("Location: error_message.php?error_review_exists");
			exit;
		} else {
			$review_author = addslashes (htmlentities ($review_author));
			$review_message = addslashes (htmlentities ($review_message));
			db_query ("INSERT INTO product_reviews (remote_ip, email, message, productid) VALUES ('$REMOTE_ADDR', '$review_author', '$review_message', '$productid')");
			header ("Location: product.php?productid=$productid");
			exit;
		}
	}

	$vote_result = func_query_first ("SELECT COUNT(remote_ip) as total, AVG(vote_value) as rating FROM product_votes WHERE productid='$productid'");
	if ($vote_result["total"] == 0)
		$vote_result["rating"] = 0;
	$smarty->assign ("vote_result", $vote_result);
	
	$vote_max_cows = floor ($vote_result["rating"]);
	$vote_little_cow = round (($vote_result["rating"]-$vote_max_cows) * 4);
	$vote_free_cows = 5 - $vote_max_cows - (($vote_little_cow==0) ? 0 : 1);
	$smarty->assign ("vote_max_cows", $vote_max_cows);
	$smarty->assign ("vote_little_cow", $vote_little_cow);
	$smarty->assign ("vote_free_cows", $vote_free_cows);

	if(!empty($login)) {
		$customer_info = func_userinfo($login,$login_type);
		$smarty->assign ("customer_info", $customer_info);
	}

	$reviews = func_query ("SELECT * FROM product_reviews WHERE productid='$productid'");
	$smarty->assign ("reviews", $reviews);
?>
