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
# $Id: register.php,v 1.45 2002/05/29 10:37:11 mav Exp $
#

if ($REQUEST_METHOD == "POST") {
#
# Do not check password mismatch
#
#$passwd2 = $passwd1;

#
# Anonymous registration (x-cart generates username by itself)
#
$anonymous_user=false;

if($anonymous && empty($uname)) {
	$max_anonimous = func_query_first("select max(replace(login, '$anonymous_username_prefix', '')-0) from customers where login like '$anonymous_username_prefix%'");

	if($max_anonimous) {
		$next_anonimous_number = array_pop($max_anonimous)+1;
		$uname = $anonymous_username_prefix.$next_anonimous_number;
	}
	else
		$uname = $anonymous_username_prefix."1";

#
# All anonymous accounts must be customers
#
	$usertype = "C";
	$passwd1 = $anonymous_password;
	$passwd2 = $anonymous_password;

	$anonymous_user=true;
}
#
# User registration info passed to register.php via POST method
#
	$existing_user = func_query_first("select password from customers where login='$uname' and usertype='$usertype'");

	if ($mode=="update") $uerror = false; else $uerror = !(empty($uname)) && !empty($existing_user);
#
# Check for errors
#
	$fillerror = (empty($uname) || empty($passwd1) || empty($passwd2) || ($passwd1 != $passwd2) || empty($firstname) || empty($lastname) || empty($b_address) || empty($b_city) || empty($b_state) || empty($b_country) || empty($b_zipcode) || empty($phone) || empty($email));

	if (!($uerror || $eerror || $fillerror)) {
#
# Fields filled without errors. User registered successfully
#

		$crypted = text_crypt($passwd1);
    	if (empty($s_address) && empty($s_city) && empty($s_zipcode)) {
        	$s_state = $b_state;
        	$s_country = $b_country;
    	}
    	if (empty($s_address)) $s_address = $b_address;
    	if (empty($s_city)) $s_city = $b_city;
    	if (empty($s_zipcode)) $s_zipcode = $b_zipcode;
#
# Add new member to newsletter list
#
	db_query("delete from maillist where email='$email'");
	if($newsletter=="on") db_query("insert into maillist (email, since_date) values ('$email',now())");

#
# Update/Insert user info
#

if ($mode=="update") {
	db_query("update customers set password='$crypted', password_hint='$password_hint', password_hint_answer='$password_hint_answer', title='$title', firstname='$firstname', lastname='$lastname', company='$company', b_address='$b_address', b_city='$b_city', b_state='$b_state', b_country='$b_country', b_zipcode='$b_zipcode', s_address='$s_address', s_city='$s_city', s_state='$s_state', s_country='$s_country', s_zipcode='$s_zipcode', phone='$phone', email='$email', fax='$fax', url='$url', card_name='$card_name', card_type='$card_type', card_number='".text_crypt($card_number)."', card_expire='$card_expire', pending_membership='$pending_membership', ssn='$ssn' where login='$login' and usertype='$login_type'");

#
# Update membership
#
	if($current_area=="A" || ($active_modules["Simple_Mode"] && $current_area=="P")) db_query("update customers set membership='$membership' where login='$login' and usertype='$login_type'");

	$registered="Y";

#
# Send mail notifications to customer department and signed customer
#
	$newuser_info = func_userinfo($login,$login_type);
	$mail_smarty->assign("userinfo",$newuser_info);

#
# Send mail to registered user
#
	$customer_language = func_get_language ($newuser_info["language"]);

	func_send_mail($newuser_info["email"], "mail/profile_modified_subj.tpl", "mail/profile_modified.tpl", $users_department, false);
#
# Send mail to customers department
#
	func_send_mail($users_department, "mail/profile_admin_modified_subj.tpl", "mail/profile_admin_modified.tpl", $newuser_info["email"], true);


} else {
#
# Add new person to customers table
#
	db_query("insert into customers (login,usertype,membership,password,password_hint,password_hint_answer,title,firstname,lastname,company,b_address,b_city,b_state,b_country,b_zipcode,s_address,s_city,s_state,s_country,s_zipcode,phone,email,fax,url,card_name,card_type,card_number,card_expire,first_login,status,referer,pending_membership,ssn) values ('$uname','$usertype','$membership','$crypted','$password_hint','$password_hint_answer','$title','$firstname','$lastname','$company','$b_address','$b_city','$b_state','$b_country','$b_zipcode','$s_address','$s_city','$s_state','$s_country','$s_zipcode','$phone','$email','$fax','$url','$card_name','$card_type','".text_crypt($card_number)."','$card_expire','".time()."','Y','$RefererCookie','$pending_membership','$ssn')");

#
# If it is partner, add his information
#
	if ($usertype == "B") {
		db_query ("INSERT INTO partner_commitions (login, commition) VALUES ('$uname','0.00')");
	}

#
# Set A-status
#
	if($anonymous_user) db_query("update customers set status='A' where login='$uname' and usertype='$usertype'");

	$registered="Y";

#
# Send mail notifications to customer department and signed customer
#
	$newuser_info = func_userinfo($uname,$usertype);
	$mail_smarty->assign("userinfo",$newuser_info);

#
# Send mail to registered user (do not send to anonymous)
#
	if(!$anonymous_user)
		func_send_mail($email, "mail/signin_notification_subj.tpl", "mail/signin_notification.tpl", $users_department, false);
#
# Send mail to customers department
#
	func_send_mail($users_department, "mail/signin_admin_notif_subj.tpl", "mail/signin_admin_notification.tpl", $email, true);

#
# Auto-log in
#
	#if($anonymous_user && $usertype=="C") {
	if($usertype=="C") {
		$auto_login = true;
		$login = $uname;
		$login_type = $usertype;
		$logged = "";
	}

}
	} else {
#
# Fields filled with errors
#
	if ($fillerror) $reg_error="F";
	if ($eerror) $reg_error="E";
	if ($uerror) $reg_error="U";
	}
if($anonymous_user) {
	$uname="";
	$passwd1="";
	$passwd2="";
}
#
# Fill $userinfo array if error occured
#
$userinfo=$HTTP_POST_VARS;
$userinfo["login"] = $uname;
$userinfo["newsletter"] = ($newsletter=="on"?"Y":"");

}
else {
#
# REQUEST_METHOD = GET
#
if ($mode=="update") {
	$userinfo = func_userinfo($login,$login_type);
}
elseif ($mode=="delete" && $confirmed=="Y") {

	$olduser_info = func_userinfo($login,$login_type);

	$customer_language = func_get_language ($olduser_info);

	func_delete_profile($login,$login_type);
	$login="";
	$login_type="";
	$smarty->clear_assign("login");
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

}
}

require "../include/countries.php";
require "../include/states.php";

#$smarty->assign("current_category",$current_category);
$smarty->assign("userinfo",$userinfo);
$smarty->assign("registered",$registered);
$smarty->assign("reg_error",$reg_error);

if($mode=="delete") {
	$smarty->assign("main","profile_delete");
}
elseif($mode=="notdelete") {
	$smarty->assign("main","profile_notdelete");
}
else 
	$smarty->assign("main","register");
?>
