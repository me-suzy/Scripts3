
     PPPPPPPPPP      OOOOOO        SSSSSSS      TTTTTTTTTTT
    PPP     PPP    OOO   OOO     SSSS    SS    T   TTT   T
   PPP     PPP    OOO   OOO      SSS              TTT
  PPPPPPPPPP     OOO   OOO         SSSS          TTT
 PPP            OOO   OOO     SS    SSSS        TTT
PPP              OOOOOO       SSSSSSSS         TTT

                      AFFILIATE
                      
===========================================================
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





UPGRADING FROM PHP AFFILIATE v1.2
There was no change in database so far, so just copy the scripts. Of course you 
should backup first.
The header and footer files were renamed from header.290, footer.290 to
standard header.php, footer.php. Admin has its own set of header and footer files.
There will be some changes in database in the next release in order to make some 
new features (see TODO)


------------------------------------------------------------------------------

TODO
- history of sales and click throughs
- confirmation on serious actions (such as delete)
- better security
- multi language support
- admin can change password
- mass emails to affiliates
- dynamic configuration of amounts, etc.


------------------------------------------------------------------------------

REQUIREMENTS
- PHP 4 or higher 
- PERL regular expressions support for PHP - it is part of PHP installation in 
  majority of cases
- MySQL


The program has been tested on UNIX (BSD) and Windows plaforms, and should 
work on all platforms where PHP and MySQL are running.


------------------------------------------------------------------------------

INSTALLATION
- download and unzip the scripts

- create MySQL database and it's contents.
  Assuming you have phpMyAdmin installed:
  - Copy the contents of database.sql
  - Paste it into the text box on phpMyAdmin underneath "Run SQL query/queries on database affiliates:"
  - Press 'Go' and the tables will be created for you.
  - Change password of admin;
    -- type the following into the same box: ('newpassword' should be your new admin password
      UPDATE admin set pass = 'newpassword' where user = 'admin';
  - Press 'Go' again and the password will update
  
- open the 'affconfig.php' file in your favourite text editor 

	- For $domain, replace "www.domain.com" with the web address to your site.
	  eg. We would put "www.organicphp.com", you could be "example.domain.com"
	  or "www.domain.com/~you". Do not end with a forward slash (/) or start with
	  "http://"

	- For $server, replace "localhost" with the address to your MySQL server.
	  It is nearly always "localhost", although check with your host if unsure.

	- For $db_user, replace "username" with the username you have made or have been
	  given for your MySQL database.

	- For $db_pass, replace "password" with the password you need to access your MySQL
	  database. Make sure you give the username a password, or anyone can enter and
	  change your database.

	- For $database, replace "affiliates" with the name of your MySQL database, this
	  could be anything, it's up to you (or your host!)

	- For $currency, replace "UK Pounds" with the currency that you will be paying your
	  affiliates in. e.g. US sites may enter "US Dollars".

	- For $emailinfo, replace with your email address.

	- $yoursitename is simply your site name

  - $lang is 'eng.php' - there will be support for multiple languages later
  
	- Save these changes and exit the file

  
- Next open the file 'check1.php' in your favourite text editor
	- For $payment, replace "5.00" in variable $payment with the amount you would like to pay 
    your affiliates for every successful sale that they have sent you.
	  If you would like to pay different amounts for different products, duplicate the
	  'check1.php' file making files 'check2.php', 'check3.php' etc. and enter the
	  different payment amounts in each.
  - !!!SECURITY WARNING!!!  for security reasons, you should rename files check1.php, check2.php 
    etc. into meaningless random generated names (for example v1df23gfd3.php). It will protect you 
    from too smart     affiliates that would like to add more sales than they originally make. 
    If your affiliates know that you are using POST Affiliate, or PHP Affiliate!, they can guess 
    you are using files check1.php, etc. from standard installation, and invoke 
    script check1.php manually. The script will add non existing sales to the database.
    That's why you should rename those files (only check1, check2, ...) into hard-to-guess meaningless names.
    

- Open your main page (most probably 'index.php') in your favourite text editor
  - main page must be PHP script

	- Enter the following code above the <html> tag and any other code:
	  <?PHP include "affiliate.php"; ?>

	- Save the changes and exit the file

- Upload all of the files in their present directory structure to your hosting account,
  using your preferred FTP program. The files and folders must be uploaded into the
  Document Root where your HTML files are.

- To reward your affiliates you need to add the following line to the top of your order confirmation page:
  <?PHP include "check1.php"; ?>
  
  !!!SECURITY WARNING!!! as mentioned before you should rename file check1.php into meaningless name, lets 
  say 'hfgjsdgfjhsdg.php'. Then you should add line <?PHP include "hfgjsdgfjhsdg.php"; ?> not 
  <?PHP include "check1.php"; ?>
  
  Dont copy this example of meaningless name. Make your own!!!
  
  
  By adding this code to the page after an order has been submitted, you prevent affiliates
  from being awarded from people just going to the order page.
  Remember that you will need seperate order forms or confirmation pages if you want to
  award affiliates with different amounts.

- If you wish to customise the look of POST Affiliate, replace the HTML in the files
  header.php and footer.php from the 'user' directory, in your favourite text editor.

- Installation is now complete!


------------------------------------------------------------------------------

THE USE OF POST Affiliate


Users can sign-up to your affiliate program by going to:
http://www.yourdomain.com/user/signup.php

They can view their statistics at:
http://www.yourdomain.com/user/index.php

Their links to your site will be as follows:
http://www.yourdomain.com/index.php?ref=username (where 'username' is their username)

You can manage your affiliate system at:
http://www.yourdomain.com/user/admin/index.php

If you have not changed the password in the MySQL database yet, the password is 'pass456'.











