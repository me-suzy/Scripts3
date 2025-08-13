<?PHP
///////////////////////////////////////////////////////
// Program Name         : SunShop
// Program Author       : Turnkey Solutions 2001-2002.
// Program Version      : 2.6
// Supplied by          : Stive [WTN], CyKuH [WTN]                            
// Nullified by         : CyKuH [WTN]                                   
///////////////////////////////////////////////////////

require("global.php");

if(!isset($total_items)) {
   setcookie("total_items", 0);
   header("Location:index.php?action=$action&id=$id");
   exit;
}

elseif ($action == "") {
    start("Welcome to " . $title);
	showcategories ("", "", "");
	if ($showintro == "No") {
		startcenter("Today's Bestsellers:");
		displaytopten($numbern);
	} else {
	    startcenter("Welcome to " . $title);
		echo $shopintro;
	}
	endcenter();
}

elseif ($action == "shipping") {
	start("Select a shipping method");
	showcategories ("", "", "");
	startcenter("Proceed To Checkout:");
	selectshipping();
	endcenter();
}

elseif ($action == "addtocart") {
   additem($item, $quantity, $option1, $option2, $option3);
}

elseif ($action == "update") {
   start("Account Update");
   showcategories ("", "", "");
   startcenter("Updating Account:");
   update($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email);
   endcenter();
}

elseif ($action == "logout") {
   setcookie("USER_IN", '');
   setcookie("PASS_IN", '');
   setcookie("item_tray", '');
   setcookie("total_items", '');
   header("Location:index.php");
}

elseif ($action == "tellafriend") {
    start("Tell A Friend");
	showcategories ("", "", "");
	$userinfo = getuser();
	$messageto = "I was surfing the net and I found this product that you may be interested in. Just follow the link and it will take you right to the product. Hope you like it!\n\n$shopurl?action=item&id=$item\n\nRegards,\n$userinfo[name]"; 
	startcenter("Tell A Friend:");
	print("
		Please enter your friends email address below to tell them about the product you have selected. The email
		will be sent with the message you specify below.<br><br>
		<strong>Message:</strong>
		<form action=\"index.php?action=mailtofriend\" method=\"post\"><blockquote>
		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td>Friends Email:</td>
				<td><input type=\"text\" name=\"email\" size=\"30\" class=\"input_box\"></td>
			</tr>
			<tr>
				<td valign=\"top\">Message:</td>
				<td><textarea cols=\"35\" rows=\"10\" name=\"message\" class=\"input_box\">$messageto</textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type=\"submit\" name=\"Submit\" value=\"Send It\" class=\"submit_button\"></td>
			</tr>
		</table>				
		</blockquote></form>
	");
	endcenter();
}

elseif ($action == "mailtofriend") {
    $userinfo = getuser();
	mail($email, "Something You May Like", $message, "From: \"".$userinfo[name]."\" <" . $userinfo[email] . ">");
	start("Tell A Friend Email Sent");
	showcategories ("", "", "");
	$userinfo = getuser();
	startcenter("Message Sent:");
	print("
		Done! The email has been sent to <strong>$email</strong> with the product you selected. Thanks for taking the time to
		share our products with your firends.<br><br><br><br><br><br><br><br><br><br><br>
	");
	endcenter();
}

elseif ($action == "mail") {
	$temp=$DB_site->query("SELECT * FROM ".$dbprefix."user where email='$email'");
	while ($row=$DB_site->fetch_array($temp)) {
	    $body = "Your requested login information is as follows:\n\nLogin: " . $row[username] . "\nPassword: " . $row[password] . "\n\nIf you did not request this information please ignore it. Thank you for shopping at " . $title . "\n\n" . $contactemail;
		mail($row[email], $title . " Login Information", $body, "From: \"".$title."\" <" . $contactemail . ">");
	    $sent = 1;
	}
    start("Emailing Login Information");
	showcategories ("", "", "");
	if ($sent == 1) {
	    startcenter("Email Sent:");
		print("
			An email with your login information has been sent to your email address at \"$email\".<br><br><br><br><br><br><br><br><br><br><br><br><br><br> 
		");
	} else {
	    startcenter("Email Error:");
		print("
			The email address \"$email\" is not in our database. Please check the address and try again.<br><br><br><br><br><br><br><br><br><br><br><br><br><br> 
		");
	}
	endcenter();
}

elseif ($action == "login") {
   $result = authenticate ($login, $password);
   if ($result == "valid") {
       $date = date("m/d/y h:ia");
	   $DB_site->query("UPDATE ".$dbprefix."user set lastvisit='$date' where username='$login'");
	   header("Location:index.php?action=$paction&id=$pid");
   } else {
	   start("Invalid Login");
	   showcategories ("", "", "");
	   startcenter("Login Error:");
	   print("
		   You have submitted invalid login information. Please try your login again. If you continue to have problems please
		   <a href=\"mailto:$contactemail\">contact us</a>.<br><br>
		   <strong>Forgot your Password?</strong>
		   <form action=\"index.php?action=mail\" method=\"post\"><blockquote>
		   Email: <input type=\"text\" name=\"email\" size=\"30\" class=\"input_box\"><br><br>
		   <input type=\"submit\" name=\"Submit\" value=\"Send Login Info\" class=\"submit_button\">
		   </blockquote></form>
	   ");
	   endcenter();
   }
}

elseif ($action == "newaccount") {
	start("New Account");
	showcategories ("", "", "");
	startcenter("New Account:");
	displaynew("", "", "", "", "", "", "", "", "", "", "", "");
	endcenter();
}

elseif ($action == "storenew") {
	start("New Account");
	showcategories ("", "", "");
	storenew($username, $password, $password2, $name, $address_line1, $address_line2, $city, $state, $zip, $country, $phone, $email);
}

elseif ($action == "updateerror") {
	$act = "Update Account";
	start($act);
	showcategories ("", "", "");
	startcenter("Error:");
	print("
		There was an error while updating your information in our database. Please
		wait a few minutes and try your submission again. If you continue to have problems please
		let us know.<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	");
	endcenter();
}

elseif ($action == "search") {
	start("Search for \"" . $search. "\"");
	showcategories ("", "", "");
	startcenter("Search Results:");
	search($search, $in);
	endcenter();
}

elseif ($action == "help") {
    start("Help");
	showcategories ("", "", "");
	startcenter("$title Help:");
	?>
	<table width="440" border="0" cellspacing="0" cellpadding="2">
	    <div align="left"><a href="#faq">Frequently Asked Questions</a><br>
		<a href="#overview">Overview</a><br><br>
		<a name="faq"></a><strong>Frequently Asked Questions</strong></div>
		<div align="left"><ul>
		<li><u>When I add items to my cart they don't show up?</u> - Make sure that your browser has cookies enabled. Without this, items will
		not be saved in your shopping cart.</li>
		<li><u>What is a cookie?</u> - A cookie is information saved locally on your computer. Your personal
		information will never be stored in a cookie.</li>
		<li><u>Is my order secure?</u> - When you are in the final checkout mode, you should see a lock shown on your browser
		somewhere at the bottom of your screen. This means that the order is secure. This is only important when submitting credit card information.</li>
		<li><u>Can I view the status of my orders?</u> - Yes you can do this at anytime. Simply click the "View Account" button located on the
		top of each page.</li>
		<li><u>Who do I contact with my questions?</u> - You may email your questions to <a href="mailto:<?PHP echo $contactemail ?> "><?PHP echo $contactemail ?></a> anytime
		<?PHP if ($shopphone != "") {
			print("or call us at $shopphone.</li></ul>");
		} else {
		    print(".</li></ul>");
		}
		?>
		<a name="overview"></a><strong>Overview</strong><br><br>
		When you first enter the shop, you are assigned a shopping cart that holds your purchases. Whenever you see an item that you want to purchase, you can add it to your cart by entering the number of items to add and clicking on the "Add To Cart" button for that item. You will then see the contents of your shopping cart, including the item(s) that you just added. 
        <br><br>At any time, you can view or change the contents of your cart by clicking on the "View Shopping Cart" link at the top of the page. You can remove items from your cart by simply setting the quantity to zero and updating your cart. 
        <br><br>When you have finished collecting items, you can click on the "Checkout" button located in the "View Shopping Cart" area, assuming that you are logged in. You will be given the chance to verify your purchase before proceeding to checkout.<br>
		<br>If you are not logged in you will need to either create an account, or login in order to use the shopping cart.
        <br><br>
		</div>
	</table>
    <?PHP
    endcenter();
}

elseif ($action == "account") {
    start("Your Account");
	showcategories ("", "", "");
	$answer = islogged();
	if ($answer == "Yes") {
	    startcenter("Your Account:");
		displayaccount ();
		endcenter();
	} else {
	  startcenter("Not logged in:");
	  loginorsignup (); 
	  endcenter();
	}
}

elseif ($action == "editaccount") {
    start("Help");
	showcategories ("", "", "");
	$answer = islogged();
	if ($answer == "Yes") {
	    startcenter("Edit Account:");
		editaccount ("", "", "", "", "", "", "", "", "", "", "", "");
		endcenter();
	}
}

elseif ($action == "viewcart") {
	start("Your Cart Has " . $total_items . " Items");
	showcategories ("", "", "");
	startcenter("Your Cart:");
	showcart();
	endcenter();
}

elseif ($action == "item") {
	start("View Item " . $id);
	$cat = getcategory($id);
	$subcat = getsubcategory($id);
	showcategories ("", $cat, $subcat);
	startcenter("View Item:");
	listitem($id);
	endcenter();
}

elseif ($action == "category") {
	if ($start == "") { $start = 0; }
	$temp = getcategoryname($id);
	$temp2 = getsubcategoryname($subid);
	start("View Category ". $temp);
	showcategories ($substart, $id, $subid);
	if ($subid == "") {
		startcenter("Products: $temp");
	} else {
		startcenter("Products: $temp: $temp2");
	}
	if ($id == "specials") { 
	  showspecials ($start); 
	} elseif ($id == "bestsellers") { 
	  displaytopten($numbern); 
	} else {
	  showitems($id, $start, $subid);
	}
	
	endcenter();
}

else {
	start("Page not found!");
	showcategories ($start, $id);
	startcenter("Error:");
	print("Your requested query was not found please check the url and try again. Please <a href=\"index.php\">return</a> to the main page.<br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
	endcenter();
}

showend($action);

$DB_site->close();

?>
