MEC STATS
Version 1.13
Matt Toigo - me@matt-toigo.com

MEC Stats Do All Sorts of Nice Things
Checkout http://www.mecstats.com for more info and a demo.

There are much more detailed instructions regarding installation and use at http://www.mecstats.com
under the support section.

BY INSTALLING MEC STATS YOU AGREE TO THE TERMS AND CONDITIONS IN EULA.txt

INSTALLATION INSTRUCTIONS

1. Unzip the file mec_v113.zip You should have a folder named mecstats that contains 27 files.
2. Upload the entire stats folder to your webserver. If the location of the pages you want to keep statistics for 
   was http://www.yoursite.com then the stats directory should be uploaded to http://www.yoursite.com/mecstats
3. CHMOD the files log.txt and arch.txt 666 (Read, Write, Execute for Owner and Group).
   This may be listed under properties of a file for your ftp client.
4. Add the following code to any pages that you wish to track stats for. If you use a layout with one main file that
   includes other files for content, you only have to put this code in the main file.
   THIS CODE MUST BE PLACED AT THE VERY TOP OF A WEBPAGE. Nothing can be before it.

   <?session_start();
   require('mecstats/hit.inc');?>

5. Everything should be set up. Browse around your website and then go to http://www.yoursite.com/stats

There are settings such as time offset that can be edited in the file settings.inc
 
Visit http://www.mecstats.com for full documentation and FAQs.