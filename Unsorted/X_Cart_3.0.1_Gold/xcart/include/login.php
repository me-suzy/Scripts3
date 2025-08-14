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
# $Id: login.php,v 1.24 2002/05/20 06:55:19 lucky Exp $
#

require "../smarty.php";
require "../config.php";

session_register("login");
session_register("login_type");
session_register("logged");

session_register("login_attempt");
session_register("cart");

$login_error=false;


if($REQUEST_METHOD=="POST") {
    if($mode=="login") {

	$username = $HTTP_POST_VARS["username"];
	$password = $HTTP_POST_VARS["password"];

    $user_data=func_query_first("select * from customers where login='$username' and usertype='$usertype' and status='Y'");

	if(!empty($user_data) && $password==text_decrypt($user_data["password"])) {
#
# Success login
#
		$login=$username;
		$login_type=$usertype;
		$logged="";
#   
# generate $last_login by current timestamp and update database
#   
		db_query("update customers set last_login='".time()."' where login='$login'");

#
# Set cookie with username if Greet visitor module enabled 
#

		if($active_modules["Greet_Visitor"])
    		include "../modules/Greet_Visitor/set_cookie.php";
	

#
# If shopping cart is not empty then user is redirected to cart.php
#
		if($login_type=="C" && !func_is_cart_empty($cart)) 
			header("Location: ../$redirect/cart.php");
		else
			header("Location: ../$redirect/home.php");
		exit;
	}
	else {
#
# Login incorrect
#
		if ($redirect=="admin") {
#
# Send security alert to website admin
#
        	func_send_mail($site_administrator, "mail/login_error_subj.tpl", "mail/login_error.tpl", $site_administrator, true);

		}
#
# After 3 failures redirects to Recover password page
#
		$login_attempt++;
		if($login_attempt>=3) {
			$login_attempt="";
			header("Location: ../$redirect/help.php?section=Password_Recovery");
		} else
		header("Location: ../$redirect/error_message.php?login_incorrect");
	}
	exit;
	}
}
if($mode=="logout") { $login=""; $login_type=""; }

header("Location: ../$redirect/home.php");
?>
