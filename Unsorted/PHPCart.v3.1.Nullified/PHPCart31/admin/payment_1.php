<?php
/////////////////////////////////////////////////////////////////////////////
//                                                                         //
// Program Name         : PHPCart                                          //
// Release Version      : 3.1                                              //
// Program Author       : Mr B Wiencierz                                   //
// Home Page            : http://www.phpcart.co.uk                         //
// Supplied by          : CyKuH [WTN]                                      //
// Nullified by         : CyKuH [WTN]                                      //
// Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                         //
//                All code is ©2000-2004 Barry Wiencierz.                  //
//                                                                         //
/////////////////////////////////////////////////////////////////////////////
// Fill in the details below for each account you will be using:

// +----------------+
// | PayStamp Setup |
// +----------------+
$paystamp_active = "No";
$affid = "xx-xx-0"; // Your Affiliate ID supplyed by PayStamp.com on signing up
$returnurl = "http://www.yourDomain.com/done.php"; // Return Address after payment has been made


// +-----------------+
// | 2Checkout Setup |
// +-----------------+
$twocheckout_active = "Yes";
$sid = "00000"; // Your Store ID supplyed by 2Checkout.com on signing up


// +--------------+
// | Nochex Setup |
// +--------------+
$nochex_active = "Yes";
$nochex_email = "mail@yourdomain.com"; // Your Nochex Username
$nochex_logo = "http://www.yourdomain.com/logo.jpg"; // Your company logo - If not using leave blank!
$nochex_returnurl = "http://www.yourdomain.com/thanks.php"; // Return Address after payment has been made


// +--------------+
// | PayPal Setup |
// +--------------+
$paypal_active = "Yes";
$paypal_email = "support@yourdomain.com"; // Your Paypal email username
$paypal_currency = "USD"; // Set the currency you charge in


// +---------------+
// | FastPay Setup |
// +---------------+
$fastpay_active = "Yes";
$fastpay_email = "mail@yourdomain.com"; // Your FastPay email username


// +----------------+
// | WorldPay Setup |
// +----------------+
$worldpay_active = "No";
$instid = "0000000"; // Your WorldPay Installation ID
$worldpay_currency = "GBP"; //Currency you would like to bill in


// +------------+
// | eWay Setup |
// +------------+
$eway_active = "No";
$ewayCustomerID = "00000000"; // Your eWay Customer Number
$ewayRURL = "http://www.yourdomain.com/thanks.php"; // Return URL after payment has been made


// +-----------------+
// | Authorize Setup |
// +-----------------+
$auth_active = "No";
$x_login = "00000000"; // Your Authorize Login Number


// +---------------------+
// | Money Bookers Setup |
// +---------------------+
$moneyb_active = "No";
$moneyb_merchant_id = "00000000"; // Your MoneyBookers Login Number
$moneyb_currency = "EUR"; // Currency you charge in
$moneyb_return_url = "http://www.domain.com"; // Return URL
$moneyb_status_url = "http://www.domain.com"; // Status URL
$moneyb_cancel_url = "http://www.domain.com"; // Cancel URL


// +----------------+
// | eMatters Setup |
// +----------------+
$ematters_active = "No";
$em_username = "aBasicTemplate"; // Your Unique ID
$em_name = "Smith"; // Your registered name
$em_email = "test@yourdomain.com"; // Your registered email
$em_returnurl = "http://www.domain.com"; // Status URL


// +-----------------+
// | AsiaDebit Setup |
// +-----------------+
$asiadebit_active = "No";
$asiadebit_shopid = "0000000"; // Your AsiaDebit Installation ID
$asiadebit_currency = "USD"; // Currency you would like to bill in


// +------------------+
// | Paysystems Setup |
// +------------------+
$paysystems_active = "No";
$paysystems_id = "0000000"; // Your Paysystems Company ID
$paysystems_redirect_done = "http://www.domain.com";
$paysystems_redirect_fail = "http://www.domain.com";


// +----------------+
// | Verisign Setup |
// +----------------+
$verisign_active = "No";
$verisign_login = "0000000"; // Your Verisign Company ID
$verisign_returnurl = "http://www.domain.com";


// +---------------+
// | Offline Setup |
// +---------------+
$offline_active = "Yes";
$offlinename = "MR C ART"; // Name for customers to address Cheques/PO
$offlineaddress = "Street
Cart-land
England
United Kingdom"; // Address to send Cheques/PO


?>