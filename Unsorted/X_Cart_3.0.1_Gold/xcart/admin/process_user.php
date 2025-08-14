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
# $Id: process_user.php,v 1.17 2002/05/20 06:55:18 lucky Exp $
#

require "../smarty.php";
require "../config.php";
require "./auth.php";
require "../include/security.php";

if ($mode=="add") {
#
# Add new user profile
#
	header("Location: user_add.php?$QUERY_STRING");
	exit;

}
elseif($mode=="update") {
#
# Modify user profile
#
	header("Location: user_modify.php?$QUERY_STRING");
	exit;
} 
elseif($mode=="delete") {
#
# Delete user
#
	if($confirmed=="Y") {
		include "./safe_mode.php";
#
# Delete user from database
#
		$olduser_info = func_userinfo($user,$usertype);
		$customer_language = func_get_language ($olduser_info[language]);
		func_delete_profile($user,$usertype);
#
# Send mail notifications to customer department and signed customer
#
    	$mail_smarty->assign("userinfo",$olduser_info);

#
# Send mail to registered user
#
    	func_send_mail($olduser_info["email"], "mail/profile_deleted_subj.tpl", "mail/profile_deleted.tpl", $users_department, false);
#
# Send mail to customers department
#
    	func_send_mail($users_department, "mail/profile_admin_deleted_subj.tpl", "mail/profile_admin_deleted.tpl", $olduser_info["email"], true);

        $smarty->assign("main","user_delete_message");

		@include "../modules/gold_display.php";
        $smarty->display("admin/home.tpl");
		exit;

	} else {

		require "../include/countries.php";
		require "../include/states.php";

		$smarty->assign("userinfo",func_userinfo($user,$usertype));
		$smarty->assign("main","user_delete_confirmation");

		@include "../modules/gold_display.php";
		$smarty->display("admin/home.tpl");
	}
} 
?>
