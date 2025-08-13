     I N S T A L L A T I O N    I N S T R U C T I O N S
##############################################################
#                                                            #
#                 Timed Autoresponse System                  #
#                     By Steve Miles                         #
#                http://www.groundbreak.com                  #
#                                                            #
#                 Copyright <c> 1999-2000                    #
#                                                            #
#     Selling or distributing this software or               #
#     modifications of this software without                 #
#     permission is expressly forbidden. Permission          #
#     to modify the script for personal use is granted.      #
#     In all cases this header and copyright                 #
#     must remain intact. All violators will be              #
#     PROSECUTED to the full extent.                         #
#                                                            #
##############################################################

----------------------------------------------------------------------------
BUG FIXES AND OTHER UPGRADE INFO CAN BE FOUND AT THE BOTTOM OF THIS DOCUMENT
----------------------------------------------------------------------------

Thank you for being a Groundbreak.com member!
This file will tell you how to install the 
Groundbreak.com Timed Autoresponse System.

TABLE OF CONTENTS:

1. Introduction
2. Installation of the system
3. Setting up a cron job

---------------
1. Introduction
---------------

The Groundbreak.com Timed Autoresponse System allows you to
set up a series of timed email responses to a request for more
information about your product. A perspective buyer/client
will visit your website and sign up for more information using
the simple form included in this zip file (of COURSE, you can
customize it any way you want). Then, from the next day to
years later you could send them follow up responses.

Cool Features:

* Quick Signup
* Quick Removal
* Removal links in the emails you send out
* Logs all of the emails sent out each day
* INFINITE emails can be set up in the system


-----------------------------
2. Installation of the system
-----------------------------

First you must download the autoresponder.zip file from
the Groundbreak.com website and extract it using a program
such as Win-Zip (www.winzip.com)

You should have all of the following files:
	1. first.txt
	2. autocron.cgi
	3. cron.txt
	4. autoresponder.cgi
	5. readme.txt
	6. config.cgi
	7. sample.HTML  (sample html signup form)

OK, here we go:

1. First, using a text editor, you need to edit config.cgi 
putting in the your system variables. An explanation of each 
variable is given in config.cgi.

HINT: use my CGIhelper script to help find all your system 
variables - you can find this script on my main site for free!

2. Create a "autoresponder" directory in your cgi-bin, then, 
within that directory create a "data" directory.

3. CHMOD the autoresponder directory 755 and the data directory 777.

4. Upload all of the contents of the zip file into the autoresponder
directory and CHMOD them all 755

5. Take the signup.html file and paste this code somewhere into your
website. Make sure to change the <form> tag to point to the full
URL of your autoresponder.cgi script.

6. Edit the first.txt file to create your first email a person receives
upon signup. Here's the structure of that file:

FIRST LINE: The subject of the email
ALL THE REST: The message of the email

7. Test out the system. Sign up, and make sure you get the first email.

8. NOW, you have to create your remaining emails. This is simple, just 
get out your text editor and create as many emails as you want. These files
must be named by the number of days you want them sent out AFTER the person
signs up and they must end in .txt. You must not use 0.txt. See below:

10.txt --> This email is sent out to the person 10 days after they signed up

365.txt --> This email is sent out a year after the person signed up

000.txt or 0.txt --> INVALID, you must use first.txt for your initial email.

0043.txt --> Not recommended, but would be sent out 43 days after a signup.

month.txt --> INVALID, won't even be recognized by the system.

* Special Tags: will be replaced with "personalized" information:
  <email>  <title>  <first_name>  <last_name>

* Sending HTML: Currently, if you set the script to send out html messages,
  ALL of your files must be HTML. Thus, after the first "subject" line the
  message must be composed just like an HTML page. And remember, in order 
  for graphics to show up you must reference the FULL URL to the graphic.
  Also, the person reading the email must be signed on to the internet to
  see the graphics...

9. So, you can create as many autoresponse emails as you want - just make sure
they are in the correct format as above. Upload them all to your autoresponder
cgi directory and CHMOD them 777. 

10. Now, the last step is to set up a cron job to execute the autocron.cgi 
script once a day to calculate and send out all the required emails. This
will be covered in the next section of the installation instructions:

11. BUT FIRST, you NEED to edit the "require config.cgi" line at the top
of autocron.cgi. You need to put in the FULL SERVER PATH to config.cgi or
the script will not work.

------------------------
3. Setting up a cron job
------------------------

This is the trickiest part of the installation for those of you who are
not familiar with cron jobs. On the UNIX system you use cron commands to
tell the system to execute a certain file at a certain time. Here's a 
simple rundown to the syntax:

0 1 * * * /path/to/cgi-bin/autocron.cgi
- - - - - -------------------------------
| | | | |          |_____ file to execute
| | | | |________________ day of week
| | | |__________________ month of year
| | |____________________ day of month
| |______________________ hour of day
|________________________ minute of hour


This particular example will execute the autocron.cgi file once a day
at 1 AM. This is pretty much how you need to do it, if you eventually 
have a pretty big collection of emails and responses, you might
want to have the script executed multiple times per day. But for
right now I suggest just once a day in the early morning.

SO, how do you set this up?

I've created a file called: cron.txt which has the proper cron command,
you just have to change the path to your autocron.cgi file and telnet
into your system to execute cron.txt.

So, right in the included cron.txt file you'll see:

# Execute autocron.cgi once a day at 1AM
0 1 * * * /path/to/cgi-bin/autocron.cgi

You'll need to change the path and the time variables if you want.

1. Upload cron.txt to your main web directory: the one you intially
telnet or FTP into. You should use an FTP program like WS-FTP and make
sure that the file is uploaded in "Ascii" format.

2. Telnet into your system. In Win9x or WinNT just go to:
	Start --> Run --> Type "telnet yoursite.com"
   and you will be prompted for your username and password
   which are the same ones you use for FTP.

3. At the prompt, type "ls" and it will list the files in your current
directory. Look for cron.txt. If it's not there you might have to find 
and change to the directory it's in. You can do this with the command:
 
cd /full/path/to/yoursite/web

or whatever the correct path to that file is.

4. Once you find the file, just type:

crontab cron.txt

5. You're done! To check to make sure that it worked, type:

crontab -l 

(that's a lowercase "L") and you should get a list of current
cron jobs running.

Or for more on crontab just type "crontab" and hit return...


----------------------------------------------------------------------------
BUG FIXES, ETC...  The most current date is the version in the zip file
----------------------------------------------------------------------------
June 27th, 2000:
* fixed bug in autocron.cgi that created blank emails sent out by crontab.

June 7th, 2000: 
* fixed bug in autocron.cgi and prevented emails going out by crontab.

May 30th, 2000: 
* made changes to autocron.cgi and autoresponder.cgi to fix some email formatting
  problems and also fix the "remove" function.
