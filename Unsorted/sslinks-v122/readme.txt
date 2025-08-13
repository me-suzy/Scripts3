ssLinks v1.2 - by Simon Willison
================================



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



Overview:
---------

ssLinks is an open source PHP and mySQL powered links database which allows 
users to run a database driven categorised links section on a web site - 
similar in structure to Yahoo or any other categorised links collection.

Features include:

* Fully database driven utilising mySQL. 
* Full template system allowing you to control the HTML output of the script 
  without needing to alter any of the PHP code.
* Easily customisable if you wish to alter the script in any way.
* Very easy to admin via the web - log in and add / edit categories and links. 
* Visitors to your site can recommend new links, which you can then validate. 
* Allows visitors to rate your links from 1-10, and displays average rating. 
* Rating a link more than once is discouraged using cookies. 
* Number of hits each link has received is recorded and displayed. 
* Very easy to set up and use, thanks to an installation script and config file. 
* Built in Search Feature.
* Most popular, top rated and newest links pages. 
* It's Open Sourced under the GPL :o)

Installation:
-------------

To install the script simply upload all of the files in the archive to your
web server.  You do not need to preserve the directory structure but it is
advisable to keep the templates in a separate directory from the script files.

You will need to modify the file global.inc.php before uploading.  You should
insert your mySQL database details and also change the admin username and 
password.  Failing to do so will leave your site wide open to hackers.

You should not upload upgrade.php unless you intend to upgrade from an existing
installation of ssLinks v1.1.  If you wish to upgrade, read the notes contained
in upgrade.txt.

Once you have edited the global.inc.php file and uploaded the scripts and 
templates you should run install.php by loading it with your web browser.  You
will need to enter your database details in the form - the script will then 
create the necessary database tables needed to run ssLinks.

Once you have installed the script it is advisable to delete install.php from 
your web server.

Having installed the script you should run it via your browser (surf to links.php
in the directory on your web server where the script was installed).  You can
then log on as an admin and start adding links.

Customisation:
--------------

Customising the output of the script should be very simple.  All HTML output by
the script is controlled by template files, stored in the template directory.
These files can be edited with a normal text editor, and consist of HTML and 
special replacement variables which look like this: %variable%

When the script generates pages it replaces these variables with dynamically
generated content.

If you are having trouble understanding which template is used for what there
is an easy way of finding out.  Edit the global.inc.php file and alter the 
following line:

$template_comments = false; 

Change this to:

$template_comments = true;

When you run the links.php script it will look the same as normal.  However, 
view the source code of the page using your browser and you will see HTML
comments added to the page outlining where the different template files are
being used.

You should normally have this value set to false as it can generate a lot of
un-necessary HTML.

More advanced customisation is beyond the scope of this readme file.  Feel 
free to ask for help on the ssLinks forum on the official web site.


Change Log:
-----------

/***********************************************************
*
* Change log for v1.2 (improvements since 1.1) - released 10th May 2001
*
* i.   Fixed the problem with submitted links that had not yet been validated
*      being included in the routine that counts the number of links in each
*      category.
* ii.  New template system has eliminated virtually all hard coded HTML from
*      the script.
* iii. ssLinks can now display the number of links in a category including
*      all links in sub categories of that category.
* iv.  Various minor bug fixes.
*
***********************************************************/

/***********************************************************
*
* Change log for v1.1 (improvements since 1.0) - released 24th Jan 2001
*
* i.   Fixed 'FLF Central' bug where some error messages and the login screen
*      contained a reference to 'FLF Central' - they now show the site name as
*      defined by the $sitename variable. :o)
* ii.  Added 'Top Rated Links', 'Most Popular Links' and 'New Links' pages.
* iii. Made it possible to edit categories (omission from ssLinks v1.0).
* iv.  Added number of links in this category - appears next to the category name.
* v.   Added alternative header / footer functions allowing proper PHP includes.
*
***********************************************************/