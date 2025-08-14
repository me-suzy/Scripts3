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
# $Id: help.php,v 1.19 2002/05/20 06:55:19 lucky Exp $
#

$userinfo = func_userinfo($login,$login_type);

if ($REQUEST_METHOD=="POST" and $action=="contactus") {
#
# Send mail to support
#
	while (list($key,$val) = each($HTTP_POST_VARS))
		$contact[$key]=$val;

    $fillerror = (empty($contact["firstname"]) || empty($contact["lastname"]) || empty($contact["b_address"]) || empty($contact["b_city"]) || empty($contact["b_country"]) || empty($contact["b_zipcode"]) || empty($contact["phone"]) || empty($contact["email"]) || empty($contact["subject"]) || empty($contact["body"]));

	if(!$fillerror) {
		$mail_smarty->assign("contact",$contact);

		func_send_mail($support_department, "mail/help_contactus_subj.tpl", "mail/help_contactus.tpl", $contact["email"], true);

		header("Location: help.php?section=contactus");
		exit;
	} else {
		$userinfo = $HTTP_POST_VARS;
		$userinfo["login"] = $userinfo["uname"];
	}
}
#
# Recover password
#
if ($REQUEST_METHOD=="POST" and $action=="recover_password") {

$accounts = func_query("select login, password, usertype from customers where email='$email' and status='Y'");

#
# Decrypt passwords
#
if($accounts) {
	foreach($accounts as $key=>$account)
		$accounts[$key]["password"]=text_decrypt($accounts[$key]["password"]);

        $mail_smarty->assign("accounts",$accounts);

        func_send_mail($email, "mail/password_recover_subj.tpl", "mail/password_recover.tpl", $support_department, false);

        header("Location: help.php?section=Password_Recovery_message&email=".urlencode($email));
}
else
        header("Location: help.php?section=Password_Recovery_error&email=".urlencode($email));

}

require "../include/states.php";
require "../include/countries.php";

$smarty->assign("userinfo",$userinfo);
$smarty->assign("fillerror",$fillerror);

$smarty->assign("main","help");
$smarty->assign("help_section",$section);

?>
