----------------------
WSN Links Version 2.55
----------------------

See the WSN Links online manual at http://scripts.webmastersite.net/wsnlinks/wsnmanual/ for extensive details on usage of WSN Links. This readme file only contains the most basic introductory material.

Want to know what's changed since a previous version you used? See releasenotes.txt in this directory for a full list of changes for each version since 1.00.

----------------------
New Install (not uprade)
----------------------

To install WSN Links 2.55:

(1) Extract files from the ZIP.
(2) Upload all the extracted files to your hosting space.
(3) If you don't already have a database created, create one using your host's provided tools. (You may use an existing database if you like, if you have one.) Also be sure you've set up a username and given the username access to this database.
(4) Using your web browser, visit setup.php (in the directory you uploaded it to).
(5) If it is not done automatically in setup, you may need to rename config.php.txt to config.php and chmod it to 666.
(6) Follow the instructions on screen, supplying your database info and creating an administrative username and password -- be sure to remember your password as you'll need it to administrate your links.
(7) If it is not done automatically in setup (it will tell you), you may want to chmod your templates to 666 so that you can edit them through the online editor. Other items you may optionally wish to chmod if you will be using the related features: searchlog.txt to 666, /attachments/ and /admin/ and /languages/ to 777.
(8) If you are in safe mode, upload the languages you want from your languages/setup directory to /languages. Use englishonly.lng if you're doing an english-only install. Rename these languages to whatever you like after moving. *If you are running php in safe mode and forget to upload a language to /languages/, you will not be able to use your admin panel.* If you are *not* in safe mode, then this issue is doubly nonapplicable as it would work with that directory blank and also will properly copy over the language files you select during setup.
(9) Visit index.php to browse your links directory as a visitor, or /admin/ to administrate it. You may wish to update your settings (admin/prefs.php) to customize them to your site.


----------------------
Upgrading from previous versions (no data is lost in any of these conversions)
----------------------

If you are upgrading from WSN Links Basic Edition, scroll past the other upgrades to find instructions.

A database backup can always be useful before starting an upgrade in case you go wrong somewhere, although it should not be neccesary.

If you have made significant customizations, it's strongly recommended that you consider using the safe upgrade procedure described by Quantum: http://scripts.webmastersite.net/wsnlinks/wsnmanual/comments.php?id=60

Note regarding all upgrades: config.php is the one file you need to keep and not overwrite, as it contains the information for how to access your database. The file in the download is named config.php.txt specifically to prevent you from accidentally overwriting your config.php during an upgrade. If by chance you've renamed it to config.php to do some testing on localhost, be careful not to upload this file over your existing one. If you do overwrite your config.php, however, see the manual for information on how to re-create it.

You can do a simple and quick upgrade by overwriting all files except attachments (including templates (be sure to use /templates/default to overwrite your /templates directory if using english-only)) and then running the appropriate upgrade scripts in ascending order. (To upgrade from 2.10, for example, run upgrade2.1-2.2.php and then upgrade2.2-2.3.php and then upgrade.php. To upgrade from 2.40, just run upgrade.php.) However, if you've customized your templates a lot and wish to only lose the minimum number of templates which you have to lose, follow the procedure below to see which templates to overwrite. Also, as a rule, always go ahead and overwrite any template which you have not customized. There are often minor improvements in them for which overwriting is not expressly commanded.

To upgrade your templates, you can use a free program to highlight changed lines for you. See the manual for details.

Note: If you get errors in upgrade.php, this is likely just that it can't chmod or write to your templates, so don't worry.


To upgrade from 2.54:
(1) Upload to overwrite all files EXCEPT your /attachments and /templates directories.
(2) Overwrite or adapt searchall.tpl and searchmembers.tpl
(3) Upload the /admin/prefs.tpl template

To upgrade from 2.53:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Upload to overwrite all files EXCEPT your /attachments and /templates directories.
(4) Upload the /admin/ subdirectory of your templates directory.
(5) Overwrite the sponsor.tpl template.
(6) Upload the /images/help.gif image to your images directories.
(7) If you wish to control the ability of usergroups to delete their link, adapt or overwrite your edit.tpl template and update your usergroup permissions.
(8) Run upgrade.php

To upgrade from 2.52:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Overwrite all files EXCEPT your /attachments and /templates directories.
(4) Overwrite the /admin subdirectory of your templates directory.
(5) Overwrite the /custom/javascript export.tpl template.
(6) Run upgrade.php
(7) Go to 'manage usergroups' in your admin panel and set adminstrators to be able to alias and copy links

To upgrade from 2.51:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Overwrite all files EXCEPT your /attachments and /templates directories. If you use member system integration, please note that you will need to overwrite/recreate your integration file as the integration scripts have been altered.
(4) Overwrite the /admin subdirectory of your templates directory.
(5) Overwrite these templates: memberlist.tpl, editmembers.tpl, editcat.tpl, viewcomments.tpl
(6) If you want to let members create personal link lists (a sort of favorites list), overwrite/update your albums.tpl, footer.tpl, suggestlink.tpl, displaylinks.tpl and edit.tpl (otherwise don't bother).
(7) Run upgrade.php

To upgrade from 2.50:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Overwrite all files EXCEPT your /attachments/ and /templates/ directories.
(4) Overwrite the /admin subdirectory of your templates directory.
(5) Upload/overwrite these template files: viewprofile.tpl, viewcomments.tpl, noaccess.tpl, reportcomment.tpl, emailmember.tpl, editmembers.tpl, memberlist.tpl, editcat.tpl, register.tpl
(6) Change 'space' to 'comma' in suggestcat.tpl... or overwrite.
(7) Run upgrade.php

To upgrade from 2.3x or 2.4x:

(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Overwrite all files EXCEPT your attachments directory and config.php. You must replace all templates, although you may be able to adapt your header, footer and stylesheet into the new files fairly easily. Be sure to upload the styles directory.
(4) Run upgrade.php
(5) Re-do all WSN Codes in your admin panel -- you will now need to specify the [] and <> inside the codes instead of them being assumed as they were before. (This change makes an image tag possible, along with some other new types of tags.)


For all older versions you will need to download the 'old upgrades' package from http://scripts.webmastersite.net/wsnlinks/members/ and upload those files to your main WSN Links directory before continuing.

To upgrade from 2.2x:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) Overwrite all files EXCEPT your attachments directory and config.php. You must replace all templates, although you may be able to adapt your header, footer and stylesheet into the new files fairly easily.
(4) Run upgrade 2.2-2.3.php
(5) Run upgrade.php

To upgrade from 2.1x:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) CHMOD 666 your config.php file
(4) Overwrite all files EXCEPT your attachments directory and config.php. You must replace all templates, although you may be able to adapt your header, footer and stylesheet into the new files fairly easily.
(5) Run upgrade 2.1-2.2.php
(6) Run upgrade 2.2-2.3.php
(7) Run upgrade.php

To upgrade from 2.0x:
(1) Go to your admin panel's 'manage languages' section and download a copy of each of your languages.
(2) Save your language(es) in your /languages/ directory of WSN Links on your computer. Name the files using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc.
(3) CHMOD 666 your config.php file
(4) Overwrite all files EXCEPT your attachments directory and config.php. You must replace all templates, although you may be able to adapt your header, footer and stylesheet into the new files fairly easily.
(5) Run upgrade 2.0-2.1.php
(6) Run upgrade 2.1-2.2.php
(7) Run upgrade 2.2-2.3.php
(8) Run upgrade.php

Note: upgrading from 2.00 on up will not damage any custom fields you may have.

To upgrade from versions 1.x directly to the current version:
(1) You will need to download the 'old upgrades' package from http://scripts.webmastersite.net/wsnlinks/members/ and upload those files to your main WSN Links directory.
(2) Delete all old files **except config.php**. You must delete all of your templates.
(3) Upload all files and directories extracted from the zip file.
(4) Upload englishonly.lng to your /languages/ directory.
(5) Visit upgradefrom1.x.php in your web browser, to import your data to the new version.
(6) Update your settings through your admin section (now located at /admin/, instead of the old admin.php)... you will need to click 'regenerate counters' on your admin panel to get totals displayed correctly.
(7) Customize your templates and enjoy.


Upgrading from WSN Links Basic Edition:
(1) If you have customized your language at all, go to your admin panel's 'manage languages' section and download a copy of each of your languages. (The language system has changed -- you will need this download to avoid losing your changes.) Save these downloads to the new 'languages' subdirectory of WSN Links on your computer, using the same file name as the title you have been using for the language. For example, english-only users should download their language and save it as default.lng while multilingual users should save as English.lng or Deutsch (Du).lng or Nederlands.lng, etc. This should all be done *before* uploading anything.
(2) Overwrite all files EXCEPT your /templates/ directory (and /integration/ if customized).
(3) Overwrite the /admin subdirectory of your templates directory.
(4) Upload the templates/yourtemplateset/images/smilies directory.
(5) Upload these new templates which weren't in the basic edition: sponsor.tpl, sitemap.tpl, codes.tpl, albums.tpl
(6) Add the link list creation link into your footer if you want people to be able to create personal link lists.


---------------------------------------------------------
Important Note: Limiting Levels for the Category Selector
---------------------------------------------------------

If you decide to limit the number of levels in your category selector in order to prevent clutter (see the settings page in your admin section), be aware that you must change your 'suggest link', 'suggest category', 'edit link' and 'edit category' templates. These templates make use of the category selector, and so submitting a new link or subcategory to a category deeper than the number of levels you allow would not work (it would force you to select a higher level category). To fix this you will have to make the tradeoff of taking away the ability to select the category manually on these pages. 

These are the changes you will need to make if you limit the levels of the category selector:
In suggestlink.tpl, change <select name="catid">{CATSELECTOR}</select> to <input type="hidden" name="catid" value="{CATID}">. In suggestcat.tpl, change <select name="parent">{CATSELECTOR}</select> to <input type="hidden" name="parent" value="{PARENT}">. In edit.tpl, change <select name="catid">{CATSELECTOR}</select> to <input type="text" name="catid" value="{LINKCATID}">. In editcat.tpl, change <select name="parent">{CATSELECTOR}</select> to <input type="text" name="parent" value="{CATPARENT}">.

------------------------------
Note to very large directories
------------------------------

If you have many thousands of categories, you will notice an increasing lag on the 'suggest category' and 'edit category' pages after submitting. To avoid this, and avoid possible script timeouts, you will need to set the number of levels in your category selector to '1' (and note the above comments on changes needed).

----------------------
Other Documentation
----------------------

See the manual at http://scripts.webmastersite.net/wsnlinks/wsnmanual/ . Be sure to check there, as it may answer your question.

Failing that, ask around on the forum: http://forums.webmastersite.net/index.php?act=SF&f=19 ... be sure to do a search of previous posts as well. 


If you encounter a bug, please report it at http://forums.webmastersite.net/index.php?act=SF&f=19 to get a quick fix for yourself and also help improve the next WSN Links release. Be sure to read the pinned bug report procedure thread since without the information requested there it is impossible to know anything about the nature of the error. (The php/mysql warnings are not useful, since they always point to database.php regardless of where the real problem is.)


-------
Thanks for purchasing WSN Links