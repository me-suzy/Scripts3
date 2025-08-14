<?php

/**************************************************************
 * File: 		Language- Command Center Module
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
	IF ( eregi("lang_cc.php", $_SERVER["PHP_SELF"]) )
		{
			require_once ('../../coin_includes/session_set.php');
			require_once ($_CCFG['_PKG_PATH_INCL'].'redirect.php');
			html_header_location('error.php?err=01');
			exit;
		}

/**************************************************************
 * Language Variables
**************************************************************/
# Language Variables: Common
		$_LANG['_CC']['Administrator_Command_Center']			= 'Administrator Command Center';
		$_LANG['_CC']['l_Amount']								= 'Amount:';
		$_LANG['_CC']['AND']									= 'AND';
		$_LANG['_CC']['Balance_Due']							= 'Balance Due';
		$_LANG['_CC']['Clients']								= 'Clients';
		$_LANG['_CC']['lc_client']								= 'client';
		$_LANG['_CC']['lc_clients']								= 'clients';
		$_LANG['_CC']['lc_client_s']							= 'client(s)';
		$_LANG['_CC']['Closed']									= 'Closed';
		$_LANG['_CC']['lc_closed']								= 'closed';
		$_LANG['_CC']['days']									= 'days';
		$_LANG['_CC']['l_Date']									= 'Date:';
		$_LANG['_CC']['l_Description']							= 'Description:';
		$_LANG['_CC']['Domains']								= 'Domains';
		$_LANG['_CC']['lc_domain']								= 'domain';
		$_LANG['_CC']['lc_domains']								= 'domains';
		$_LANG['_CC']['lc_domain_s']							= 'domain(s)';
		$_LANG['_CC']['Expired']								= 'Expired';
		$_LANG['_CC']['Expired']								= 'Expired';
		$_LANG['_CC']['Expiring_In']							= 'Expiring In';
		$_LANG['_CC']['Found_Items']							= 'Found Items';
		$_LANG['_CC']['HelpDesk']								= 'HelpDesk';
		$_LANG['_CC']['Invoices']								= 'Invoices';
		$_LANG['_CC']['lc_invoice']								= 'invoice';
		$_LANG['_CC']['lc_invoices']							= 'invoices';
		$_LANG['_CC']['lc_invoice_s']							= 'invoice(s)';
		$_LANG['_CC']['No_Items_Found']							= 'No items found for criteria entered.';
		$_LANG['_CC']['None']									= 'None';
		$_LANG['_CC']['Open']									= 'Open';
		$_LANG['_CC']['lc_open']								= 'open';
		$_LANG['_CC']['on']										= 'on';
		$_LANG['_CC']['or']										= 'or';
		$_LANG['_CC']['OR']										= 'OR';
		$_LANG['_CC']['Orders']									= 'Orders';
		$_LANG['_CC']['lc_order']								= 'order';
		$_LANG['_CC']['lc_orders']								= 'orders';
		$_LANG['_CC']['lc_order_s']								= 'order(s)';
		$_LANG['_CC']['Please_Select']							= 'Please Select';
		$_LANG['_CC']['Active_Products']						= 'Active Product Orders';
		$_LANG['_CC']['lc_products']							= 'products';
		$_LANG['_CC']['Search_Clients']							= 'Search Clients';
		$_LANG['_CC']['Search_Domains']							= 'Search Domains';
		$_LANG['_CC']['Search_Helpdesk']						= 'Search Helpdesk';
		$_LANG['_CC']['Search_Invoices']						= 'Search Invoices';
		$_LANG['_CC']['Search_Options']							= 'Search Options';
		$_LANG['_CC']['Search_Orders']							= 'Search Orders';
		$_LANG['_CC']['Search_Transactions']					= 'Search Transactions';
		$_LANG['_CC']['lc_server_s']							= 'server(s)';
		$_LANG['_CC']['Sent_And_After']							= 'And After';
		$_LANG['_CC']['Sent_And_Before']						= 'And Before';
		$_LANG['_CC']['Servers']								= 'Servers';
		$_LANG['_CC']['Server_Accounts']						= 'Server Accounts (SACC)';
		$_LANG['_CC']['Sorry_Administrative_Function_Only']		= 'Sorry- Administrative Function Only';
		$_LANG['_CC']['Summary']								= 'Summary';
		$_LANG['_CC']['lc_support_ticket']						= 'support ticket';
		$_LANG['_CC']['lc_support_tickets']						= 'support tickets';
		$_LANG['_CC']['lc_support_ticket_s']					= 'support ticket(s)';
		$_LANG['_CC']['Ticket']									= 'Ticket';
		$_LANG['_CC']['Total']									= 'Total';
		$_LANG['_CC']['Total_of']								= 'Total of:';
		$_LANG['_CC']['totalling']								= 'totalling';
		$_LANG['_CC']['Welcome']								= 'Welcome';
		$_LANG['_CC']['Within']									= 'Within';

# Language Variables: Some Buttons
		$_LANG['_CC']['B_Reset']								= 'Reset';
		$_LANG['_CC']['B_Search']								= 'Search';

# Language Variables: Common Labels (note : on end)
		$_LANG['_CC']['l_Actions']								= 'Actions:';
		$_LANG['_CC']['l_Client_ID']							= 'Client ID:';
		$_LANG['_CC']['l_Company']								= 'Company:';
		$_LANG['_CC']['l_Domain_Name']							= 'Domain Name:';
		$_LANG['_CC']['l_Domain_Expiration']					= 'Domain Expiration:';
		$_LANG['_CC']['l_Email']								= 'Email:';
		$_LANG['_CC']['l_First_Name']							= 'First Name:';
		$_LANG['_CC']['l_Id']									= 'Id:';
		$_LANG['_CC']['l_Invoice_ID']							= 'Invoice ID:';
		$_LANG['_CC']['l_Last_Name']							= 'Last Name:';
		$_LANG['_CC']['l_Name']									= 'Name:';
		$_LANG['_CC']['l_Order_ID']								= 'Order ID:';
		$_LANG['_CC']['l_Origin']								= 'Origin:';
		$_LANG['_CC']['l_Pages']								= 'Page(s):';
		$_LANG['_CC']['l_Product']								= 'Product:';
		$_LANG['_CC']['l_Referred_By']							= 'Referred By:';
		$_LANG['_CC']['l_SACC_Expiration']						= 'SACC Expiration:';
		$_LANG['_CC']['l_Search_Type']							= 'Search Type:';
		$_LANG['_CC']['l_Subject']								= 'Subject:';
		$_LANG['_CC']['l_Ticket_ID']							= 'Ticket ID:';
		$_LANG['_CC']['l_Type']									= 'Type:';
		$_LANG['_CC']['l_User_Name']							= 'User Name:';
		$_LANG['_CC']['l_Vendor']								= 'Vendor:';

?>
