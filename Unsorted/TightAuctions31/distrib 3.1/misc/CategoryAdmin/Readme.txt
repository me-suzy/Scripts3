
Readme for CategoryAdmin

CategoryAdmin allows you to easily manage your categories.  The categories
are used for both auctions and classifieds.  Note that you should use 
CategoryAdmin to setup your categories in the beginning.  Until the 
import/export scripts are fixed, any current ads may be associated with
different categories.  See below for details.

Using CategoryAdmin
-------------------
First you need to set your server name and paths to the import/export scripts.
Do this via going to Edit | Preferences.  After setting that up you can use
the File | Import and File | Export commands to import and export your 
categories.  Use File | Open and File | Save to open and save the category
files locally on your machine.  

The "New Root" button creates a new root.  To add another category, right-click on
the root node that you wish to create the category underneath and select "Add"
from the context menu.  To delete a category (and subcategories, if any), select
"Delete" from the context menu.


FILES YOU'LL NEED TO MODIFY
---------------------------

To get everything running you'll need to appropriately modify the 
config.php file and set the variables.

You may also want to setup .htaccess appropriately for /admin and /cat_admin.  
NOTE: You'll want to disable .htaccess for /cat_admin when you're using the
CategoryAdmin Windows program since it currently doesn't support authorization
logins.  After you're finished with updating the categories then reenable 
.htaccess for /cat_admin.

You will also need to temporarily enable the root directory in which the 
files are stored to be writeable when updating the categories since it writes
a file that is to be included on the home pages.


NOTE ON CATEGORIES ( applies to the /cat_admin and /misc/CategoryAdmin directories )
------------------------------------------------------------------------------------

These files are used by the CategoryAdmin Windows program to
update the categories.  Please note that you should have your
categories updated and all set before you allow users to post
ads on your website.  The software currently does not reconcile
changes to the categories.  e.g. An ad that was placed in one
category could be placed into another category after the categories
have been updated due to the reassignments of the primary key
CategoryID.  

This could easily be fixed to resolve this issue by modifying 
the import/export scripts to reassign the foreign key referenced 
to a category in the ads tables.  I'll probably add this 
functionality in the next release but ran out of time for this 
version.  In the meantime, just setup your categories first 
beforehand and keep them fixed unless you can modify the
scripts to fix the problem mentioned.


Feedback and/or Questions
-------------------------

Questions regarding TightAuctions?  Your question may have already been
answered, or you can post a new one if it hasn't already been answered.

  http://www.tightprices.com/forums

You can also send feedback, questions, etc. to tightauctions@tightprices.com


Cheers,

TightPrices Team


