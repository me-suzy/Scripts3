<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.9
// Supplied by          : CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////
require("global.php");

if(!(session_is_registered("total_items"))) {
   session_register("total_items");
   session_register("item_tray");
   session_register("options_only");
   $total_items=0; $item_tray=""; $options_only="";
   header("location:index.php?action=$action&id=$id&subid=$subid&PHPSESSID=$PHPSESSID");
   exit;
}

elseif ($action == "") {
    $show_categories = showcategories ("", "", "");
	$toptitle = preg_replace("/<!-- title -->/", $title, $lang_index[welcome]);
	if ($showintro == "No") {
		$message = $lang_index[best].":";
		$main_content = showitems("bestsellers", "", "");
	} else {
	    $message = preg_replace("/<!-- title -->/", $title, $lang_index[welcome]);
		eval("\$welcome_message = \"".loadtemplate("welcome_message")."\";");
		$main_content = stripslashes($welcome_message);
	}
	start();
}

elseif ($action == "tracking") {
	showtracking();
}

elseif ($action == "pricelist") {
    $toptitle = $lang_index[pricelist];
    $quitearly = 1;
    start();
	pricelist();
}

elseif ($action == "shipping") {
    $toptitle = $lang_index[ship];
	$message = $lang_index[proceed].":";
	$show_categories = showcategories ("", "", "");
	$main_content = selectshipping();
	start();
}

elseif ($action == "addtocart") {
   $toptitle = $lang_index[your_cart]." ".$total_items." ".$lang_index[items];
   additem($item, $quantity, $option, $fields, $fnames);
   $message = $lang_index[your_cart].":";
   $show_categories = showcategories ("", "", "");
   $main_content = showcart();
   start();
}

elseif ($action == "update") {
   $toptitle = $lang_index[acc_update];
   $message = $lang_index[update].":";
   $show_categories = showcategories ("", "", "");
   $main_content = update();
   start();
}

elseif ($action == "logout") {
   session_unset();
   session_destroy();
   header("location:index.php");
}

elseif ($action == "tellafriend") {
    $toptitle = $lang_index[friend];
    $message = $lang_index[friend].":";
	$show_categories = showcategories ("", "", "");
	$userinfo = getuser();
	
	$messageto = $lang_index[friend_mess];
	$messageto = preg_replace("/<!-- shopurl -->/", $shopurl, $messageto);
	$messageto = preg_replace("/<!-- item -->/", $item, $messageto);
	$messageto = preg_replace("/<!-- name -->/", $userinfo[name], $messageto);
	
	$main_content = "$lang_index[fform_mess]<br><br>
		<strong>$lang_index[fmess]:</strong>
		<form action=\"index.php?action=mailtofriend\" method=\"post\"><blockquote>
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td>$lang_index[femail]:</td>
				<td><input type=\"text\" name=\"email\" size=\"45\" class=\"input_box\"></td>
			</tr>
			<tr>
				<td valign=\"top\">$lang_index[fmess]:</td>
				<td><textarea cols=\"45\" rows=\"10\" name=\"message\" class=\"input_box\">$messageto</textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type=\"submit\" name=\"Submit\" value=\"$lang_index[send]\" class=\"submit_button\"></td>
			</tr>
		</table>				
		</blockquote></form>";
	start();
}

elseif ($action == "mailtofriend") {
    $toptitle = $lang_index[fsent];
    $userinfo = getuser();
	mail($email, $lang_index[fsubject], $message, "From: \"".$userinfo[name]."\" <" . $userinfo[email] . ">");
	$message = $lang_index[fsent].":";
	$show_categories = showcategories ("", "", "");
	$messageto = preg_replace("/<!-- email -->/", $email, $lang_index[fsent_mess]);
	$main_content = $messageto;
	start();
}

elseif ($action == "mail") {
    $toptitle = $lang_index[lost_pass_head];
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."user where email='$email'");
	while ($row=$DB_site->fetch_array($temp)) {
	    $body = $lang_index[lost_pass_mess];
		$body = preg_replace("/<!-- title -->/", $title, $body);
		$body = preg_replace("/<!-- username -->/", $row[username], $body);
		$body = preg_replace("/<!-- password -->/", $row[password], $body);
		$body = preg_replace("/<!-- contactemail -->/", $contactemail, $body);
		$subject = $lang_index[lost_pass_sub];
		$subject = preg_replace("/<!-- title -->/", $title, $subject);
		mail($row[email], $subject, $body, "From: \"".$title."\" <" . $contactemail . ">");
	    $sent = 1;
	}
	$show_categories = showcategories ("", "", "");
	if ($sent == 1) {
	    $message = $lang_index[lost_pass_sent].":";
		$main_content = preg_replace("/<!-- email -->/", $email, $lang_index[pass_sent_mess]);
	} else {
		$message = $lang_index[lost_pass_error].":";
		$main_content = preg_replace("/<!-- email -->/", $email, $lang_index[pass_sent_error]);
	}
	start();
}

elseif ($action == "login") {
   $result = authenticate ($login, $password);
   if ($result == "valid") {
       $date = date("m/d/y h:ia");
	   $DB_site->query("UPDATE ".$dbprefix."user set lastvisit='$date' where username='$login'");
	   header("location:index.php?action=$paction&id=$pid&subid=$psubid&substart=$psubstart");
   } else {
       $toptitle = $lang_index[invalid_login];
	   $message = $lang_index[invalid_login_head].":";
	   $show_categories = showcategories ("", "", "");
	   $action=$paction;
	   $id=$pid;
	   $body = preg_replace("/<!-- contactemail -->/", $contactemail, $lang_index[invalid_login_mess]);
	   $main_content = "$body<br><br>
		   <strong>$lang_index[forgot_pass]</strong>
		   <form action=\"index.php?action=mail\" method=\"post\"><blockquote>
		   $lang_index[email]: <input type=\"text\" name=\"email\" size=\"30\" class=\"input_box\"><br><br>
		   <input type=\"submit\" name=\"Submit\" value=\"$lang_index[email_button]\" class=\"submit_button\">
		   </blockquote></form>";
	   start();
   }
}

elseif ($action == "newaccount") {
    $toptitle = $lang_index[new_account];
	$message = $lang_index[new_account].":";
	$show_categories = showcategories ("", "", "");
	$main_content = displaynew();
	start();
}

elseif ($action == "tempaccount") {
    $toptitle = $lang_no_acc[personal];
	$message = $lang_no_acc[personal].":";
	$show_categories = showcategories ("", "", "");
	$mode = 1;
	$main_content = displaynew();
	start();
}

elseif ($action == "storenew") {
    $toptitle = $lang_index[new_account];
	$message = $lang_index[new_account];
	$show_categories = showcategories ("", "", "");
	$main_content = storenew();
	start();
}

elseif ($action == "storenewuser") {
	$toptitle = $lang_index[error];
	$message = $lang_index[error].":";
	$show_categories = showcategories ("", "", "");
	$main_content = storetempnew();
	start();
}

elseif ($action == "updateerror") {
    $toptitle = $lang_index[update_account];
	$message = $lang_index[error].":";
	$show_categories = showcategories ("", "", "");
	$main_content = $lang_index[update_acc_error];
	start();
}

elseif ($action == "search") {
    $toptitle = $lang_index[search]." \"" . $search. "\"";
	$message = $lang_index[search_res].": \"" . $search. "\"";
	$show_categories = showcategories ("", "", "");
	$main_content = search($search, $in);
	start();
}

elseif ($action == "help") {
    $toptitle = $lang_index[help];
    $message = "$title ".$lang_index[help].":";
	$show_categories = showcategories ("", "", "");
	$main_content = showfaqhelp();
    start();
}

elseif ($action == "account") {
	$answer = islogged();
	$toptitle = $lang_index[account];
	if ($answer == "Yes") {
	    $message = $lang_index[account].":";
		$main_content = displayaccount ();
	} else {
	    if ($mustsignup == "No") {
		    $message = $lang_login[acc].":";
		    $main_content = noaccountlogin ();
		} else {
		    $message = $lang_index[not_logged].":";
		    $main_content = loginorsignup ();
		}
	}
	$show_categories = showcategories ("", "", "");
	start();
}

elseif ($action == "editaccount") {
    $toptitle = $lang_index[edit];
	$show_categories = showcategories ("", "", "");
	$answer = islogged();
	if ($answer == "Yes") {
	    $message = $lang_index[edit].":";
		$main_content = editaccount ();
		start();
	}
}

elseif ($action == "viewcart") {
    $toptitle = $lang_index[your_cart]." ".$total_items." ".$lang_index[items];
	$message = $lang_index[your_cart].":";
	$show_categories = showcategories ("", "", "");
	$main_content = showcart();
	start();
}

elseif ($action == "item") {
    $iteminfo = iteminfo($id);
	$toptitle = $lang_index[view_item]." ".$iteminfo[title];
    if ($substart == "") { $substart = 0; }
	$cat = getcategory($id);
	$subcat = getsubcategory($id);
	$message = $lang_index[view_item].": ".$iteminfo[title];
	$show_categories = showcategories ($substart, $cat, $subcat);
	$main_content = listitem($id);
	start();
}

elseif ($action == "category") {
    if ($start == "") { $start = 0; }
	$temp = getcategoryname($id);
	$temp2 = getsubcategoryname($subid);
	if ($id == "bestsellers") {
		$temp = $lang_cat[best];
	}
	if ($id == "specials") {
		$temp = $lang_cat[spec];
	}	
	$toptitle = $lang_index[view_cat]." ".$temp;
	if ($subid == "") {
		$message = $lang_index[view_cat].": ".$temp;
	} else {
		$message = $lang_index[prod].": $temp: $temp2";
	}
    $show_categories = showcategories ($substart, $id, $subid);
	$main_content = showitems($id, $start, $subid);
	start();
}

elseif ($action == "verify") {
	verify();
	exit;
}

else {
	$message = $lang_index[not_found];
	$show_categories = showcategories ("", "", "");
	$main_content = $lang_index[not_mess];
	start();
}

showend();
$DB_site->close();
?>
