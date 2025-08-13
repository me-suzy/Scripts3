		==============================================================
			    PHP Advanced Transfer Manager 
				  (C)2002, 
		==============================================================
			    remotely based upon PHP Upload Center v2.0


#########################################################
#                                                       #
# This script was provided by:                          #
#                                                       #
# PHPSelect Web Development Division.                   #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are © 2004                      #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated in the script.                       #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the authors at       #
# admin@phpselect.com or info@phpselect.com             #
#                                                       #
#########################################################


Requirements
------------

	Windows/Linux,
	a web server (Apache 1.3+ recommended)
	PHP 4.0.0+

History
-------

	See History.txt

Main features
-------------

	 1. Multilangual
	 2. Time zone offset (depends on selected language).
	 3. User can give description to file.
	 4. Download counter.
	 5. User can sort files by name, upload date, downloads.
	 5. User can delete o modify its own files
	 6. Administrator and Poweruser can delete all files and
	    create/delete directories
	 7. Filename length limit.
	 9. Appearance tuning (colors, font)
	10. Is is possible to define header and footer.
	12. Possibility to view contents of ZIP archives without downloading.
	13. Possibility to view images and other type of files on the fly.
	14  Recent file list and top downloads list
	14. User acccounts support.
	15. IP Logging and Blocking.
	16. Account management system.
	17. In order to check user e-mail addresses you can enable user
	    activation system.
	18. User can subscrbe on everyday digest of uploaded files list
	    via e-mail (optional).
	19. Users can get small files by e-mail (optional).
	21. User statistics (uploaded/downloaded/e-mailed files, last access).
	22. Administrator can show info message over the file list.
	23. Highly configurable (see include/conf.php)
	24. Possibility to use server mailer or your own SMTP
	25. No cookies needed (but if present used)


How to install PHP Advanced Transfer Manager
--------------------------------------------

	 -  Create folder for php script on the server.
	 -  Customize, values of variables in file include/conf.php
	    See comments in file.
	 -  Customize files nn.php (where nn - is language code).
	 -  If your server require a particular extension to recognize php scripts
	    (eg '.php4') you have to set correctly the $phpExt in conf.php and
	    change the extension of all files EXCLUDING conf.php
	 -  Copy all files and subfolders to the server.
	 -  Chmod 777 all files and directories copied (this is very important)
	 -  Register your own new administrator account.
	 -  Activate your new account (needed, if request activation is "on")
	 -  Log in as user "Admin", password "test".
	 -  Go to the administrator console and set to the new account an
	    administrator rights.
	 -  Log in using new account, go to the administrator console and delete
	    "Admin" account. DO NOT FORGET DO THIS!!! This can be a serious
	    security hole!
	 -  As administrator customize header, footer and infos html-files.
	 -  Be sure, that user accounts is not visible, when you typing in browser
	    the address www.myserver.com/upload/users/username (where
	    www.myserver.com - is the server name where you installed your script,
	    username - the name of any registered user)
	    If visible, you have to configure server!


How to configure PHP Advanced Transfer Manager
----------------------------------------------

	 1. You can add your custom page header (i.e. banner at the top or at
	    the bottom of page, or site menu & copyrights) by changing files
	    header.htm & footer.htm, which placed in include folder.
	 2. You can change info, modifying file info.htm, which placed in
	    include folder. If you wish place different info's for different
	    languages you can assign different .htm filenames in language
	    files ($infopage variable).
	 3. You can change header of digest letter by editing mailinfo.htm file.
	 4. You can easy edit all above files using configuration panel.
	 5. If you wish change default folder where files will be stored, you can
	    edit variable $uploads_folder_name in file conf.php.
	    You have to set the relative path without trailing slash
	    (e.g. $uploads_folder_name = "files/uploads";)
	 6. Highly recommended change default user folder by changing
	    $users_folder_name variable. This improve your security.


How to create a new translation
-------------------------------

	 1. Translate an existing language file (stored in 'languages' folder)
	    and save it with name 'NN.php', where NN is the country code.
	 2. Create a new text file called 'NN.lang'.
	    - in the first line of this file insert the country code (NN)
	    - in the second the language name followed by timezone name (CET, GMT, etc)
	    - in the third line insert the timezone offset without the plus character if
	      positive and with the minus character if negative.
	 2. Copy 'NN.php' and 'NN.lang' to the 'languages' directory on the server
	 3. Chmod 777 these two new files.
	 4. Please, don't forget send us your translation. We include it in the new
	    release of phpTransferManager.


Description file structure
--------------------------

	 All file descriptions stored in files with .dlcnt extension.

	 1st line - the user name who uploaded file
	 2nd line - the IP of user
	 all other lines - the file description


User account file structure
---------------------------

	 The name of file - is the user name.

	 1st line: Encrypted user password
	 2nd line: Encrypted user session ID, 0 - if user logged out
	 3rd line: User E-Mail address
	 4th line: Account status: 0 - Administrator, 1 - Power User,
	           2 - Normal User, 3 - Viewer (view only), 4 - Uploader (upload only)
	 5th line: 1 - account active, 0 - disabled, other value - activation code
	 6th line: Any temporary information (i.e. unconfirmed new user mail)
	 7th line: 1 if user wish to receive files digest via e-mail, else 0
	 8th line: The unix time when user account created
	 9th line: preferred language (country code)


Language file structure
-----------------------
	All language data is stored in files with .lang extension
	into 'languages' folder. There should be a relative .php file
	containg translation strings-

	1st line: country code
	2nd line: language and timezone description
	2rd line: numerical timezone offset