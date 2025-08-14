
/**************************************************************
 * File: 		Documentation: Read Me
 * Author:	Mike Lansberry (http://phpcoin.com)
 * Date:		2004-01-04 (V1.2.0)
 * Changed: 	Stephen M. Kitching, 2005-03-03 (v1.2.2)
 * License:	DO NOT Remove this text block. See /coin_docs/license.txt
 *			Copyright © 2003-2004-2005 phpCOIN.com
 * Schema:	See sql file for schema reference
 * Notes:
 *			- setup directory readme
**************************************************************/

Please see the following documents (in the /coin_docs directory):
- license.txt
	- Software License.

- phpcoin_about.txt
	- Package information.

- phpcoin_config_files.txt
	- Package Configuration Files information.

- phpcoin_config_params.txt
	- Package Configuration Parameters Table information.

- phpcoin_install.txt
	- Package Installation instructions

- phpcoin_nowwhat.txt
	- Some ideas on where to go after installation.

Additional Reference Documents:
- phpcoin_mail_templates.txt
	- Default email templates text (just in case)

- phpcoin_mtp_array.txt
	- Listing of variables that are "in scope" and can
	  be used in the email templates.



HOW AND WHEN TO RUN SETUP.PHP:
==============================

If you are installing for the first time, run setup.php

If you are upgrading from v1.1.1 or lower, run the appropriate
"upgrade_to_V1xx.php" file(s) first, then run setup.php and
follow the instructions for upgrading from v1.2.0 to v1.2.2

If you are upgrading from v1.2.0 BACKUP YOUR DATABASE FIRST,
then run setup.php

If you have already upgraded to v1.2.2 DO NOT RUN SETUP.PHP.
Simply copy the files,
