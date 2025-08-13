<?php
/* 
   this file handles basic visitor routines, but not administration and not payment. 
*/
require_once("configure.php");
$goal = $_REQUEST['goal'];
$productnumber = $_REQUEST['productnumber'];
$customeridCookie = !isset($_COOKIE["customeridCookie"])?NULL:$_COOKIE["customeridCookie"];
urldecode($goal);
switch($goal){
	case "Retrieve Product Data":
		// get the product details 
		$query = "SELECT availability, via, baseprice, productname, shortname, benefit, url FROM " . TABLEITEMS . " WHERE productnumber ='$productnumber'";
		// pconnect, select and query
		if ($link_identifier = mysql_pconnect(DBSERVERHOST, DBUSERNAME, DBPASSWORD)) {
			if ( mysql_select_db(DBNAME, $link_identifier)) {
				// run the query
		   	$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				$rows = mysql_num_rows($queryResultHandle); // get # of rows
				if ( $rows > 0 ) { // if a product actually exists
					$data = mysql_fetch_array( $queryResultHandle );
					$availability = $data["availability"];
					$via = $data["via"];
					$baseprice = $data["baseprice"];
					$productname = $data["productname"];
					$shortname = $data["shortname"];					
					$benefit = $data["benefit"];
					$url = $data["url"]; 
					// to tighten security
					$_POST["productnumber"] = $productnumber;
					$_POST["availability"] = $availability;
					$_POST["via"] = $via;					
					$_POST["baseprice"] = $baseprice;
					$_POST["productname"] = $productname;
					$_POST["shortname"] = $shortname;
					$_POST["benefit"] = $benefit;
					$_POST["url"] = $url;
				}elseif($rows == 0 ) {
					echo"<br>No product data.";
               exit;
				}

            /* at this point the visitor has selected some file to download 
               and we know if its a free or a retail product. 

               If the download is 'retail' then retreive the customer  
               details for use in the checkout - if a cookie exists ... 
					if there is no cookie then we will assume no data exists
					and its a new customer starting with a blank form
				*/
				if( $availability == "Retail" ) { // do this only for a retail download
					$customerid = isset($_COOKIE['customeridCookie'])?$_COOKIE['customeridCookie']:NULL; // capture the cookie
					if(!empty($customerid)) { // continue only if a valid cookie exists
                  // retrieve all customer data 
	               $query = "SELECT * FROM " . TABLECUSTOMER . " WHERE customerid = $customerid";
						// run the query
						$queryResultHandle = mysql_query($query, $link_identifier) or die( mysql_error() );
				   	$rows = mysql_num_rows($queryResultHandle);
					   if($rows > 0) { // then a customer record exists 
						   // retreive the data
							// customerid password email organization firstname lastname address city stateprov country postalcode areacode phone fax cellphone website customersince visits role privacy news lastupdate
					      $data = mysql_fetch_array($queryResultHandle);
	                  $customerid = stripslashes($data["customerid"]);
		               $password = stripslashes($data["password"]);
			            $email = stripslashes($data["email"]);
				         $organization = stripslashes($data["organization"]);
					      $firstname = stripslashes($data["firstname"]);
						   $lastname = stripslashes($data["lastname"]);
							$address = stripslashes($data["address"]);
                     $city = stripslashes($data["city"]);
	                  $stateprov = stripslashes($data["stateprov"]);
		               $country = stripslashes($data["country"]);
			            $postalcode = stripslashes($data["postalcode"]);
				         $areacode = stripslashes($data["areacode"]);
					      $phone = stripslashes($data["phone"]);
						   $fax = stripslashes($data["fax"]);
							$cellphone = stripslashes($data["cellphone"]);
                     $website = stripslashes($data["website"]);
	                  $customersince = stripslashes($data["customersince"]);
		               $visits = stripslashes($data["visits"]);
			            $role = stripslashes($data["role"]);
				         $privacy = stripslashes($data["privacy"]);
					      $news = stripslashes($data["news"]);
						   $lastupdate = stripslashes($data["lastupdate"]);

							// to tighten security
							$_POST["customerid"] = $customerid;
							$_POST["password"] = $password;
							$_POST["paypalEmail"] = $email;
							$_POST["organization"] = $organization;
							$_POST["firstname"] = $firstname;
							$_POST["lastname"] = $lastname;
							$_POST["address"] = $address;
							$_POST["city"] = $city;
							$_POST["stateprov"] = $stateprov;
							$_POST["country"] = $country;
							$_POST["postalcode"] = $postalcode;
							$_POST["areacode"] = $areacode;
							$_POST["phone"] = $phone;
							$_POST["fax"] = $fax;
							$_POST["cellphone"] = $cellphone;
							$_POST["website"] = $website;
							$_POST["customersince"] = $customersince;
							$_POST["visits"] = $visits;
							$_POST["role"] = $role;
							$_POST["privacy"] = $privacy;
							$_POST["news"] = $news;
							$_POST["lastupdate"] = $lastupdate;

						} // fetch customer data


					
						// make an update to number of visits by this customer
				      $totalVisits = $visits + 1;
				      $query = "UPDATE " . TABLECUSTOMER . " SET visits='$totalVisits' WHERE customerid=$customerid";
   				   $queryResultHandle = mysql_query($query, $link_identifier); 
					} // if(!empty($customeridCookie
				} // if( $availability == "Retail"

            if ( $availability == "Retail" ) { // if product is retail go to the checkout
	            $goal = "Route Payment Method";
					if(file_exists("proCheckout.php")) {
						include("proCheckout.php");
						exit;
					}else{
						require_once("functions.php");
						drawHeader();
						include_once("header.php");
						echo"<blockquote><h1>phpCheckout<sup>TM</sup></h1>";
						echo"<h2>Easily Showcase Your Digital Items</h2>";
						echo"<h3>phpCheckout Free Edition</h3>\n";
						echo"<ul>\n";
						echo"<li>the selected Retail item cannot be purchased online</li>\n";
						echo"<li>the Free Edition does not include files needed to complete an online transaction</li>\n";
						echo"<li>The Pro edition has the necessary Pro files</li>\n";
						echo"<li>Visit DreamRiver to <a href=\"http://www.dreamriver.com\">purchase the Pro Edition</a> and profit from your digital downloads</li>\n";
						echo"</ul>\n";
						echo"<h3>Wanna buy $productname?</h3>\n";
						$to = TO;
						$domain = DOMAIN;
						echo"<p>Send an email to <a href=\"email.php?to=$to&domain=$domain\">request $productname from " . ORGANIZATION . "</a></p>";
						echo"</blockquote>";
					} // if the checkout file doesn't exist ...
            }else{ // its a free item, so give it to them
					if(($via == "SMTP Attachment") || ($via == "SMTP Body")) { // get recipient email if item is sent by email
						$goal = "Validate Email Before Sending Attachment";
						include("downloadRequestEmail.php");
					}else{
						include("downloadFree.php");
					} // if(($via == "SMTP Attachment") || ($via == "SMTP Body"
            } // if ( $availability == "Retail"
			}else{ // select
				echo mysql_error();
			}
		}else{ // pconnect
			echo mysql_error();
		}
		break;
   
 


	case "Validate Email Before Sending Attachment":
		$recipient = $_POST['recipient'];
		// is it a usable address?
		//validateEmail - from the php developer's cookbook but never 100% foolproof
		if (!eregi ("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $recipient)) {
			$messageString = "<p class=\"required\">Invalid email address. Please provide a valid email address</p>";
			include("downloadRequestEmail.php");
		}else{
			include("downloadFree.php");
		}
		break;




	default:
      if(empty($goal)){
         echo"No goal defined in file runner.php.";
      }else{
         echo"The goal called &quot;$goal&quot; is not supported in runner.php";
      }
}