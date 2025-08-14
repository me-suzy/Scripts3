<?php

/**************************************************************
 * File: 		Language- Config Vars for Package
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-08 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Notes:	- Global language ($_LANG) vars.
 *		- Language: 		English (USA)
 *		- Translation By:	Mike Lansberry
 *		- Translator Email:	webcontact@phpcoin.com
**************************************************************/
# Code to handle file being loaded by URL
	IF (!isset($_SERVER))	{ $_SERVER = $HTTP_SERVER_VARS; }
	IF ( eregi("lang_config.php", $_SERVER["PHP_SELF"]) )
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}

/**************************************************************
 * Configuration Variables
**************************************************************/
# Language Variables: Base Common Set
	$_LANG['_CCFG']['All_Active_Clients']		= 'All Active Clients';
	$_LANG['_CCFG']['Please_Select']			= 'Please Select';
	$_LANG['_CCFG']['Welcome']				= 'Welcome';

# Language Variables: Some Common Buttons Text.
	$_LANG['_CCFG']['B_Log_In']				= 'Log In';
	$_LANG['_CCFG']['B_Log_Out']				= 'Log Out';
	$_LANG['_CCFG']['B_Reset']				= 'Reset';

##################################################################################################
# System Status Strings- do not edit array except for text for language
# Clients Status Select List Params
	$_CCFG['CL_STATUS'][1]			= 'active';			# For Active Clients
	$_CCFG['CL_STATUS'][2]			= 'banned';			# For Banned Clients
	$_CCFG['CL_STATUS'][3]			= 'inactive';			# For In-Active Clients
	$_CCFG['CL_STATUS'][4]			= 'pending';			# For New Clients (pending)
	$_CCFG['CL_STATUS'][5]			= 'void';				# For Void Clients

# Domain Status Select List Params (array position stored)
	$_CCFG['DOM_STATUS'][0]			= 'n/a';
	$_CCFG['DOM_STATUS'][1]			= 'Hosted';
	$_CCFG['DOM_STATUS'][2]			= 'Parked';
	$_CCFG['DOM_STATUS'][3]			= 'Other';

# Domain Type Select List Params (array position stored)
	$_CCFG['DOM_TYPE'][0]			= 'n/a';
	$_CCFG['DOM_TYPE'][1]			= 'Domain- Hosted';
	$_CCFG['DOM_TYPE'][2]			= 'Domain- Parked';
	$_CCFG['DOM_TYPE'][3]			= 'Domain- Redirect';
	$_CCFG['DOM_TYPE'][4]			= 'SubDomain- Hosted';
	$_CCFG['DOM_TYPE'][5]			= 'SubDomain- Redirect';
	$_CCFG['DOM_TYPE'][6]			= 'Other';

# Help Desk Status Select List Params
	$_CCFG['HD_TT_STATUS'][1]		= 'Answered';			# For Answered
	$_CCFG['HD_TT_STATUS'][2]		= 'Awaiting Client';	# For Awaiting Client
	$_CCFG['HD_TT_STATUS'][3]		= 'Awaiting Support';	# For Awaiting Support
	$_CCFG['HD_TT_STATUS'][4]		= 'In Progress';		# For In-Progress
	$_CCFG['HD_TT_STATUS'][5]		= 'Unanswered';		# For Unanswered

# Invoices Status Select List Params
	$_CCFG['INV_STATUS'][0]			= 'due';				# For Due (sent and waiting) Invoices
	$_CCFG['INV_STATUS'][1]			= 'draft';			# For Draft Version of Invoice
	$_CCFG['INV_STATUS'][2]			= 'overdue';			# For Overdue Invoices
	$_CCFG['INV_STATUS'][3]			= 'paid';				# For Paid Invoices
	$_CCFG['INV_STATUS'][4]			= 'pending';			# For Pending (To Be Sent) Invoices
	$_CCFG['INV_STATUS'][5]			= 'void';				# For Void Invoices

# Invoices Delivery Method Select List Params
	$_CCFG['INV_DELIVERY'][0]		= 'email';			# For eMail delivery
	$_CCFG['INV_DELIVERY'][1]		= 'mail';				# For Mail delivery
	$_CCFG['INV_DELIVERY'][2]		= 'online';			# For Online delivery

# Invoices Billing Cycle Select List Params  (array position stored)
# Array element zero MUST be days, even if you don't use it.
# Other elements can be removed, and the remainder renumbered sequentially
	$_CCFG['INVC_BILL_CYCLE'][0]		= 'Daily';			# For Daily
	$_CCFG['INVC_BILL_CYCLE'][1]		= 'Monthly';			# For Monthly
	$_CCFG['INVC_BILL_CYCLE'][2]		= 'Quarterly';			# For Quarterly
	$_CCFG['INVC_BILL_CYCLE'][3]		= 'Semi-Annual';		# For Semi-Annual
	$_CCFG['INVC_BILL_CYCLE'][4]		= 'Annual';			# For Annual
	$_CCFG['INVC_BILL_CYCLE'][5]		= 'One-Time';			# For one-time


# Invoices Billing Cycle Params Values (must correspond to list params)
# Array element zero MUST be days, even if you don't use it.
# Other elements can be removed, and the remainder renumbered sequentially
	$_CCFG['INVC_BILL_CYCLE_VAL'][0]	= 7;					# Only one in days
	$_CCFG['INVC_BILL_CYCLE_VAL'][1]	= 1;					# Months
	$_CCFG['INVC_BILL_CYCLE_VAL'][2]	= 3;					# Months
	$_CCFG['INVC_BILL_CYCLE_VAL'][3]	= 6;					# Months
	$_CCFG['INVC_BILL_CYCLE_VAL'][4]	= 12;				# Months
	$_CCFG['INVC_BILL_CYCLE_VAL'][5]	= 24;				# Months

# Invoices Transaction Type Select List Params
	$_CCFG['INV_TRANS_TYPE'][0]		= 'Debit';			# For Debit
	$_CCFG['INV_TRANS_TYPE'][1]		= 'Credit';			# For Credit
	$_CCFG['INV_TRANS_TYPE'][2]		= 'Payment';			# For Payment

# Invoices Transaction Origin Select List Params
	$_CCFG['INV_TRANS_ORIGIN'][0]		= 'Invoice';			# For Invoice
	$_CCFG['INV_TRANS_ORIGIN'][1]		= 'Vendor';
	$_CCFG['INV_TRANS_ORIGIN'][2]		= 'Credit Card';
	$_CCFG['INV_TRANS_ORIGIN'][3]		= 'Check';
	$_CCFG['INV_TRANS_ORIGIN'][4]		= 'Money Order';
	$_CCFG['INV_TRANS_ORIGIN'][5]		= 'Other';

# Orders Status Select List Params
	$_CCFG['ORD_STATUS'][0]			= 'active';			# For Active Orders
	$_CCFG['ORD_STATUS'][1]			= 'cancelled';			# For Cancelled Orders
	$_CCFG['ORD_STATUS'][2]			= 'confirmed';			# For Confirmed Orders
	$_CCFG['ORD_STATUS'][3]			= 'duplicate';			# For Duplicate Orders
	$_CCFG['ORD_STATUS'][4]			= 'pending';			# For New Orders (pending)
	$_CCFG['ORD_STATUS'][5]			= 'void';				# For Void Orders
	$_CCFG['ORD_STATUS'][6]     		= 'completed';			# For completed orders


# Orders Domain Action Select List Params (array position stored)
	$_CCFG['ORD_DOM_ACT'][0]	= 'Existing Domain';			# For Existing Domain
	$_CCFG['ORD_DOM_ACT'][1]	= 'Register Domain For Me';		# For New Domains From Here
	$_CCFG['ORD_DOM_ACT'][2]	= 'I Will Register Domain';		# For New Domains From Others
	$_CCFG['ORD_DOM_ACT'][3]	= 'Transferring Domain';			# For Transferring Domains
	$_CCFG['ORD_DOM_ACT'][4]	= 'Other- Need Help';			# For Other- Need Help
	$_CCFG['ORD_DOM_ACT'][5]	= 'Not Relevant';				# For Other- Need Help

# Order Form Optional Field Label Text Params
	$_CCFG['ORD_LABEL_OPTFLD_01']	= 'Option 1:';				# Optional Field 1
	$_CCFG['ORD_LABEL_OPTFLD_02']	= 'Option 2:';				# Optional Field 2
	$_CCFG['ORD_LABEL_OPTFLD_03']	= 'Option 3:';				# Optional Field 3
	$_CCFG['ORD_LABEL_OPTFLD_04']	= 'Option 4:';				# Optional Field 4
	$_CCFG['ORD_LABEL_OPTFLD_05']	= 'Option 5:';				# Optional Field 5

# Order Form COR Optional Field Label Text Params
	$_CCFG['COR_LABEL_OPTFLD_01']	= 'Option 1:';				# Optional Field 1
	$_CCFG['COR_LABEL_OPTFLD_02']	= 'Option 2:';				# Optional Field 2
	$_CCFG['COR_LABEL_OPTFLD_03']	= 'Option 3:';				# Optional Field 3
	$_CCFG['COR_LABEL_OPTFLD_04']	= 'Option 4:';				# Optional Field 4
	$_CCFG['COR_LABEL_OPTFLD_05']	= 'Option 5:';				# Optional Field 5

# End System Status Strings- do not edit array except for text for language
##################################################################################################

# Invoices Default Terms String (inserted on new if enabled)
	$_CCFG['INV_TERMS_DEF_LINE_01']	= 'Payment required to be posted / received by closing of due date.';
	$_CCFG['INV_TERMS_DEF_LINE_01']	.= '';
	$_CCFG['INV_TERMS_DEF_LINE_01']	.= '';
	$_CCFG['INV_TERMS_DEF_LINE_02']	= 'All late payments will be subjected to a 2% late charge.';
	$_CCFG['INV_TERMS_DEF_LINE_02']	.= '';
	$_CCFG['INV_TERMS_DEF_LINE_02']	.= '';
	$_CCFG['INV_TERMS_DEF_LINE_03']	= '';
	$_CCFG['INV_TERMS_DEF_LINE_03']	.= '';
	$_CCFG['INV_TERMS_DEF_LINE_03']	.= '';

# Help Desk Priority Select List Params
# (to add just copy line and change value- array sorted for list)
	$_CCFG['HD_TT_PRIORITY'][1]	= 'Urgent';
	$_CCFG['HD_TT_PRIORITY'][2]	= 'High Priority';
	$_CCFG['HD_TT_PRIORITY'][3]	= 'Medium Priority';
	$_CCFG['HD_TT_PRIORITY'][4]	= 'Low Priority';

# Help Desk Category Select List Params
# (to add just copy line and change value- array sorted for list)
	$_CCFG['HD_TT_CATEGORY'][1]		= 'Billing';
	$_CCFG['HD_TT_CATEGORY'][2]		= 'Database Issue';
	$_CCFG['HD_TT_CATEGORY'][3]		= 'Email Issue';
	$_CCFG['HD_TT_CATEGORY'][4]		= 'Feedback';
	$_CCFG['HD_TT_CATEGORY'][5]		= 'FTP Issue';
	$_CCFG['HD_TT_CATEGORY'][6]		= 'General';
	$_CCFG['HD_TT_CATEGORY'][7]		= 'Other Issues';
	$_CCFG['HD_TT_CATEGORY'][8]		= 'Script(s) Issue';
	$_CCFG['HD_TT_CATEGORY'][9]		= 'Server Issue';
	$_CCFG['HD_TT_CATEGORY'][10]	= 'Sub-Domains';

# COR Request Type Select List Params
# (to add just copy line and change value- array sorted for list)
	$_CCFG['COR_REQ_TYPE'][]	= 'Email Only Services';
	$_CCFG['COR_REQ_TYPE'][]	= 'Hosting Services';
	$_CCFG['COR_REQ_TYPE'][]	= 'Other';
	$_CCFG['COR_REQ_TYPE'][]	= 'Webmaster Services';

# COR Option Billing Cycle Select List Params
# (to add just copy line and change value- array NOT sorted for list)
	$_CCFG['COR_OPT_BILL_CYCLE'][]	= 'Monthly';
	$_CCFG['COR_OPT_BILL_CYCLE'][]	= 'Quarterly';
	$_CCFG['COR_OPT_BILL_CYCLE'][]	= 'Semi-Annual';
	$_CCFG['COR_OPT_BILL_CYCLE'][]	= 'Annual';

# COR Option Payment Select List Params
# (to add just copy line and change value- array sorted for list)
	$_CCFG['COR_OPT_PAYMENT'][]	= 'Check';
	$_CCFG['COR_OPT_PAYMENT'][]	= 'Money-Order';
	$_CCFG['COR_OPT_PAYMENT'][]	= 'Wire Transfer';

# Title for "admin" of default page to display to client upon login
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][1]    = 'Summary Page';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][2]    = 'My Account';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][3]    = 'Domains';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][4]    = 'Invoices';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][5]    = 'HelpDesk';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_DISPLAY'][6]    = 'Orders';

# Titles for "admin" for desired columns to display on client listing
	$_CCFG['CLIENT_LIST_DISPLAY_TEXT'][1]                = 'Full Name and UserName';
	$_CCFG['CLIENT_LIST_DISPLAY_TEXT'][2]                = 'Full Name and Email Address';
	$_CCFG['CLIENT_LIST_DISPLAY_TEXT'][3]                = 'UserName and Email Address';

# Product Info desiplay sequence for order form
	$_CCFG['ORD_PROD_SEQUENCE'][1]                      = 'Description - Price';
	$_CCFG['ORD_PROD_SEQUENCE'][2]                      = 'Price - Description';
	$_CCFG['ORD_PROD_SEQUENCE'][3]                      = 'Name - Price';
	$_CCFG['ORD_PROD_SEQUENCE'][4]                      = 'Price - Name';
	$_CCFG['ORD_PROD_SEQUENCE'][5]                      = 'Name - Description - Price';
	$_CCFG['ORD_PROD_SEQUENCE'][6]                      = 'Description - Name - Price';
   	$_CCFG['ORD_PROD_SEQUENCE'][7]                      = 'Price - Name - Description';
	$_CCFG['ORD_PROD_SEQUENCE'][8]                      = 'Price - Description - Name';

	$_CCFG['SITE_FOOTER_EMAIL_WEBMASTER'] = 'Email <a href="mailto:webmaster@phpcoin.com">webmaster@phpcoin.com</a> with questions or comments about this site';

?>