***********************
*****WhatDaFaq?!?******
***********************

#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################

USE AT YOUR OWN RISK

Your welcome to change these scripts as much as your like, but
please send any changes you make to them to me at the above email.
[Note: it's not necessary to send me changes you make the the HTML - just the PHP/MySQL]

Release under GNU Public License
Please read the license file that came with this.

+Requirements:
PHP 4.x +, MySQL 3.2x +

+Introduction:
WhatDaFaq is a FAQ application, allowing you to update FAQ pages from your web browser.
FAQs come under two groupings;
-categories: defined by the $faqcategory variable. This allows you to have seperate "pages"
			 of FAQs, using the same script
-headings: within a sinlge category, headings are "titles" on the FAQ page to group the FAQs visually

Scriptwise, there's;
-whatdaadmin.php: this is your admin interface
-index.php: the page for people reading the FAQs
-auth.php: a simple user login script used by whatdaadmin.php
-header / footer.php: top and bottom of each page
-loginform.php: what people get when not logged in
When you customise this to your own site, all you really need is index.php and whatdaadmin.php

Notes:
1. There's no admin interface for maintaining the whatdafaqcat table. This is to prevent destruction
   of data integrity - delete a category - what happens to all the FAQs in that category?
   This may change in a future version. For now you will have to add categories manually.
2. A faq table export script is planned.

+Installation (To Re-create the demo)
1. Extract the Zip file to a web directory. It will create a subdirector whatdafaq
2. Load whatdafaq.sql into your MySQL database
3. Edit dbconnect.php to match your MySQL setting
4. Point your browser at http//www.yourdomain.com/whatdafaq (or whatever directory you used).
