<?php
/**************************************************************
 * File: 		Global Configuration File
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-030-8 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:	- Global config vars.
 * 			- do not change unless knowledgeable.
**************************************************************/

# Code to handle file being loaded by URL
	IF (!isset($_SERVER)) {$_SERVER = $HTTP_SERVER_VARS;}
	IF (eregi("config.php", $_SERVER["PHP_SELF"])) {
		require_once ('coin_includes/session_set.php');
		require_once ('coin_includes/redirect.php');
		html_header_location('error.php?err=01');
		exit();
	}


/**************************************************************
 * Configuration Variables That MUST be edited
**************************************************************/

# Database Configuration Array
	$_DBCFG['dbms'] 			= "mysql";		// Database System (type)
	$_DBCFG['dbhost'] 			= "localhost";	// Database Host
	$_DBCFG['dbuname'] 			= "username";	// User
	$_DBCFG['dbpass'] 			= "password";	// Password
	$_DBCFG['dbname'] 			= "database";	// Database Name
	$_DBCFG['table_prefix'] 		= "phpcoin_";	// Database table prefix


# Mailserver settings
	$_SMTP['AUTHENTICATED']	= 0;		// 1 = mailserver requires authenticated session,
								// 0 = does NOT require authentication
	# The following need to be set ONLY if $_SMTP[AUTHENTICATION] = 1
	$_SMTP['HOST']			= 'mail.yourdomain.com';		// FQDN or IP Address of your mailserver
	$_SMTP['LOCALHOST']		= 'www.yourdomain.com';		// name of machine sending email, for HELO
	$_SMTP['ACC']			= 'support@yourdomain.com';	// mailserver user name
	$_SMTP['PASS']			= 'yourpassword';			// mailserver user password


# Offline flag to initiate redirect in core load (1=offline,0=normal)
	$_CCFG['_PKG_MODE_OFFLINE'] 	= 0;


# Define laguage file / decoding
	$_CCFG[_HC_PKG_LANG]		= 'lang_english';	// Use this lang if the next line is 0
	$_CCFG[_DB_PKG_LANG_ENABLE]	= 1;				// Use the lang in the database

	$_CCFG[_PKG_DOC_META_TAG]	= '<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">';


# Client 'Active' Status string. MUST match $_CCFG['CL_STATUS'][1] in the lang_config.php file
	$_CCFG['_PKG_STR_ACTIVE'] 	= 'active';


# Theme settings
	$_CCFG[_HC_PKG_THEME]		= 'earthtone';	// Use this theme if the next line is 0
	$_CCFG[_DB_PKG_THEME_ENABLE]	= 1;			// Use the theme in the database




/**************************************************************
 * Misc Vars
 * There is normally NO need to edit these,
 * unless specifically instructed to do so
 **************************************************************/
# Parameter Group Select List Params (do not change )
	$_CCFG['_PARM_GROUP'][]		= 'automation';
	$_CCFG['_PARM_GROUP'][]		= 'common';
	$_CCFG['_PARM_GROUP'][]		= 'theme';
	$_CCFG['_PARM_GROUP'][]		= 'user';
	$_CCFG['_PARM_GROUP'][]		= 'undefined';

# Parameter Sub-Group Select List Params (no delete or edit)
	$_CCFG['_PARM_GROUP_SUB'][]	= 'common';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'layout';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'module';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'buttons';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'operation';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'package';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'style';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'undefined';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'admin';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'API';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'articles';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'clients';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'contacts';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'domains';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'faq';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'helpdesk';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'invoices';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'orders';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'pages';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'summary';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'siteinfo';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'whois';
	$_CCFG['_PARM_GROUP_SUB'][]	= 'backup';

# Countries List
	$_Countries = array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua & Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia & Herzegowina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D\'Ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France, Metropolitan', 'French Guiana', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-bissau', 'Guyana', 'Haiti', 'Heard & McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran, Islamic Republic of', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, Democratic People\'s Republic of', 'Korea, Republic of', 'Kuwait', 'Kyrgyzstan', 'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia, (former) Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia, Federated States of', 'Moldova, Republic of', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russian Federation', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent & the Grenadines', 'Samoa', 'San Marino', 'Sao Tome & Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia (Slovak Republic)', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia & South Sandwich Islands', 'Spain', 'Sri Lanka', 'St. Helena', 'St. Pierre & Miquelon', 'Sudan', 'Suriname', 'Svalbard & Jan Mayen Islands', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic', 'Taiwan, Province of China', 'Tajikistan', 'Tanzania, United Republic of', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad & Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks & Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'US, Minor Outlying Islands', 'Uzbekistan', 'Vanuatu', 'Vatican City State (Holy See)', 'Venezuela', 'Viet Nam', 'Virgin Islands (British)', 'Virgin Islands (U.S.)', 'Wallis & Futuna Islands', 'Western Sahara', 'Yemen', 'Yugoslavia', 'Zaire', 'Zambia', 'Zimbabwe');




/**************************************************************
 * Other Vars Paths / Dirs
 * Do NOT edit these
 **************************************************************/
# Parse from user input array- gets stripped out of anonymous user input strings.
# Replaces with a **** string.
# (to add just copy line and change value)
	$_CCFG['PARSE_USER_ENTRY'][]	= '<a';
	$_CCFG['PARSE_USER_ENTRY'][]	= 'href';
	$_CCFG['PARSE_USER_ENTRY'][]	= '<img';
	$_CCFG['PARSE_USER_ENTRY'][]	= '<script';
	$_CCFG['PARSE_USER_ENTRY'][]	= '<iframe';
	$_CCFG['PARSE_USER_ENTRY'][]	= 'javascript';
	$_CCFG['PARSE_USER_ENTRY'][]	= 'vbscript';

# Some formatting strings
	global $_nl, $_sp;
	$_nl	= "\n";
	$_sp	= "&nbsp;";


# Common Config File Array for paths
	$_CCFG['_PKG_REDIRECT_ROOT']		= $_PACKAGE[URL];	# Site- Root used for redirect URL (must have http://)
	$_CCFG['_PKG_URL_BASE']			= $_PACKAGE[URL];
	$_CCFG['_PKG_URL_INCL']			= $_CCFG['_PKG_URL_BASE'].'coin_includes/';
	$_CCFG['_PKG_URL_THEME']			= $_CCFG['_PKG_URL_BASE'].'coin_themes/'.$_CCFG[_HC_PKG_THEME].'/';
	$_CCFG['_PKG_URL_THEME_IMGS']		= $_CCFG['_PKG_URL_BASE'].'coin_themes/'.$_CCFG[_HC_PKG_THEME].'/images/';
	$_CCFG['_PKG_URL_IMGS']			= $_CCFG['_PKG_URL_BASE'].'coin_images/';
	$_CCFG['_PKG_URL_ADDONS']		= $_CCFG['_PKG_URL_BASE'].'coin_addons/';
	$_CCFG['_PKG_URL_MDLS']			= $_CCFG['_PKG_URL_BASE'].'coin_modules/';

	$_CCFG['_PKG_PATH_BASE']			= $_PACKAGE[DIR];
	$_CCFG['_PKG_PATH_ADMN']			= $_CCFG['_PKG_PATH_BASE'].'coin_admin/';
	$_CCFG['_PKG_PATH_ADDONS']		= $_CCFG['_PKG_PATH_BASE'].'coin_addons/';
	$_CCFG['_PKG_PATH_AUXP']			= $_CCFG['_PKG_PATH_BASE'].'coin_auxpages/';
	$_CCFG['_PKG_PATH_DBSE']			= $_CCFG['_PKG_PATH_BASE'].'coin_database/';
	$_CCFG['_PKG_PATH_IMGS']			= $_CCFG['_PKG_PATH_BASE'].'coin_images/';
	$_CCFG['_PKG_PATH_INCL']			= $_CCFG['_PKG_PATH_BASE'].'coin_includes/';
	$_CCFG['_PKG_PATH_LANG']			= $_CCFG['_PKG_PATH_BASE'].'coin_lang/'.$_CCFG[_HC_PKG_LANG].'/';
	$_CCFG['_PKG_PATH_MDLS']			= $_CCFG['_PKG_PATH_BASE'].'coin_modules/';
	$_CCFG['_PKG_PATH_THEME']		= $_CCFG['_PKG_PATH_BASE'].'coin_themes/'.$_CCFG[_HC_PKG_THEME].'/';
	$_CCFG['_PKG_PATH_MDLS']			= $_CCFG['_PKG_PATH_BASE'].'coin_modules/';

# "action" of default page shown to clients upon login.
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][1]		= 'cc';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][2]		= 'clients';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][3]		= 'domains';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][4]		= 'invoices';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][5]		= 'helpdesk';
	$_CCFG['CLIENT_VIEW_PAGE_UPON_LOGIN_ACTION'][6]		= 'orders&mode=view';


/**************************************************************
 * Build Config Array Syntax
**************************************************************/
# Build a config array (concept example)
	#	global $_CCFG;
	#	$_CCFG = array('item_01'	=> $item_01_val,
	#			'item_02'			=> $item_02_val,
	#			'item_03'			=> $item_03_val,
	#			'item_04'			=> $item_04_cal
	#			);

?>