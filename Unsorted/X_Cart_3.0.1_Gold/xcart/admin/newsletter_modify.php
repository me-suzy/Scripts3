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
# $Id: newsletter_modify.php,v 1.18 2002/04/22 17:10:28 mav Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";



#
# Generate timestamp
#
    $send_date=mktime(date("H",time()),date("i",time()),date("s",time()),$SendMonth,$SendDay,$SendYear);


if($REQUEST_METHOD=="POST") {

	if(!empty($newsid)) {
#
# Update newsletter
#

		db_query("update newsletter set send_date='$send_date', subject='$subject', body='$body', email1='$email1', email2='$email2', email3='$email3' where newsid='$newsid'");
		$smarty->assign("main","newsletter_modify_message");

	} else {
#   
# Add new newsletter
#
		db_query("insert into newsletter (send_date, subject, body, email1, email2, email3, status) values ('$send_date', '$subject', '$body', '$email1', '$email2', '$email3', 'N')");
		$newsid = db_insert_id();
		$smarty->assign("main","newsletter_add_message");
	}

#
# Send test messages to test email(s)
#
	$newsletter = func_query_first("select * from newsletter where newsid='$newsid'");

	func_spam(array($newsletter["email1"],$newsletter["email2"],$newsletter["email3"]),$newsletter["subject"],$newsletter["body"]);
	@include "../modules/gold_display.php";
	$smarty->display("admin/home.tpl");
	exit;
} 

if (!empty($newsid)) $newsletter = func_query_first("select * from newsletter where newsid='$newsid'");

if ($mode=="send") {
	include "./safe_mode.php";
#
# Generate targets list
#
	$targets=array();
	$targets_data=func_query("select email from maillist");

	if($targets_data) {
		foreach($targets_data as $target_data)
			$targets[]=$target_data["email"];
#
# Send mass mail
#
		func_spam($targets, $newsletter["subject"], $newsletter["body"]);
		# exec("NEWSID=".$newsid." php ../spam.php &");
		db_query("update newsletter set status='S' where newsid='$newsid'");
	}

	$smarty->assign("main","newsletter_send_message");

	@include "../modules/gold_display.php";
	$smarty->display("admin/home.tpl");
	exit;
}

$smarty->assign("newsletter",$newsletter);
$smarty->assign("main","newsletter_modify");

@include "../modules/gold_display.php";
$smarty->display("admin/home.tpl");
?>
