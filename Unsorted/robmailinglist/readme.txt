######################################
#                                    #
# RobMail v2.04b                     #
# Copyright 1998-99 by Rob Eisler    #
# rob@robplanet.com                  #
# http://www.robplanet.com           #
#                                    #
# Last modified on Jan 6, 1999       #
#                                    #
######################################
#
# Copyright Notice:
# Copyright 1998 - 99 Robert S. Eisler.  All Rights Reserved.
#
# This code may be used and modified by anyone so long as this header and
# copyright information remains intact.  By using this code you agree to 
# indemnify Robert S. Eisler from any liability that might arise from its 
# use.  You must obtain written consent before selling or redistributing 
# this code.
#
#####################################
#
# Thanks to Stuart Prescott for pointing out a few mistakes I'd made
# with v2.03 of this script.
#
#####################################

Here's the deal.  There are a couple things I'd like for you to do if you're
using my scripts, although I have no way of holding you to them, and I
probably wouldn't try to if I did.

-- Please keep my name and copyright info included in this stuff.

-- Tell me you're using my script.  I like to see where my programs are
   being implemented on the web, and I'll add a link to your page on 
   mine.  You can just e-mail me, or fill out the form at 
   http://www.robplanet.com/cgi/form.html

-- If it's at all possible, I'd appreciate a link to my page from yours.
   It's no big deal, though.  You can get a link from my page by filling
   out the form as mentioned in the last point.

-- If you make an improvement to the code, please let me know.  I won't
   include the code in my future distributions if you don't want me to,
   but I'll certainly give you full credit if you do allow your code to
   be included.

-- I'm starting up a mailing list to announce updates and new programs.
   You can add/remove yourself by sending me mail or by filling out that
   same form, or through the form at http://www.robplanet.com/cgi/

-- Thanks --

If you are having trouble getting this script to work, please read through this
file carefully before e-mailing me with questions.  I don't mean you should spend
hours trying to install this yourself before asking me for help - I don't mind
answering questions, I just get a little frustrated when I've given the answer in
the readme or an FAQ :).  If you're stuck, send mail to rob@robplanet.com - 
and please include as much detail as you can.  I won't be able to help you if I 
don't know what's going on. You can also try posting to the message board at
http://www.robplanet.com/messages - and that's probably your best bet,
since I sometimes get too much mail to read.

This is version 2.04b of RobMail.  It's compatible with version 2.02 - .04, 
with a few minor changes to your old data.

To update from 2.04, just edit the variables in the .cgi's and upload them
over your old ones, and put pend.txt in your $maildir with the other .txt
files and chmod 777 (you need this if you want to use this new confirm 
function; it's described below). Simple! The two fixes are the case sensitive 
e-mail address problem, and the ability to run a confirm procedure in order to 
keep people from signing up fake e-mail addresses.

To update from 2.03, just edit the robmail.cgi and adduser.cgi variables and
use them to overwrite the old versions. Also, you need (if you care about
the security of your list) to move your .txt files into a new directory outside
of your public_html, and change your $maildir and $mailurl appropriately. More 
on this below. Updating from 2.02 is the same as from 2.03, but you also need 
the overwrite your old pwd.txt with the new one from this distribution. 

This script REQUIRES a JavaScript-capable browser for the actual sending of
mass mail. You can do all the administrative stuff, and users can add/remove
themselves without JS. If you're really keen on using this script but can't
bring yourself to use JS, send me an e-mail and I'll tell you how to work
around it - but for moderately large lists this may cause the script to 
do weird and freaky things. It's much better to just use a JS browser. I hear
they're pretty cheap nowadays...

When you're sending mail, the browser will continually update the number of
messages sent. (this is where the JavaScript comes in, the Perl uses JS to call
itself to send all the mail). When the script is mailing DON'T STOP IT. If you
do, nothing drastic will happen, but the script will stop sending mail wherever
it happens to be. When the script is sending the mail, you'll see a message 
saying Don't Touch Anything - you shouldn't touch anything. :)

This script includes a password function; the default password is 'password' - 
all lower case letters.  You can change it from the HTML page.

------------

These are the files you should have:

  1.  robmail.cgi . . . . . . . the Perl script that runs everything
  2.  adduser.cgi . . . . . . . Perl that lets users add/remove themselves
  3.  index.html  . . . . . . . main HTML page
  4.  adduser.htm . . . . . . . HTML fragment for adduser.cgi
  5.  lists.txt . . . . . . . . data file
  6.  default.txt . . . . . . . data file
  7.  pend.txt  . . . . . . . . data file
  8.  pwd.txt . . . . . . . . . data file
  9.  sig.txt . . . . . . . . . data file
  10. link.gif  . . . . . . . . image
  11. robmail.jpg . . . . . . . RobMail button
  12. readme.txt  . . . . . . . this file


Here's what you need to do with these files:

- Put robmail.cgi, adduser.cgi in your cgi-bin and chmod them 755.

- Make a directory for RobMail somewhere and chmod it 777. It should NOT be in 
  your public html directory. The script will still function if this directory
  is in your public html, but then anyone can read your list of subscribers if they
  can figure out where to look.

  This is where the file locations will be different for people who have installed
  previous versions of this script. You should put your txt files in this new 
  directory, and chmod them 777. This new directory will make up your new $maildir
  in your cgi files. Also, your $mailurl is no longer a directory - it's just the
  location of index.html (or whatever you feel like naming it).

- Put lists.txt, default.txt, pend.txt, pwd.txt, sig.txt in this directory, and chmod
  them all 777.
- Put index.html somewhere in your public html, and chmod it 644.
- If you want to use the adduser stuff (which allows users to add/remove
  themselves to/from a mailing list) just insert the code from adduser.htm to one
  of your pages, and make the changes as mentioned for adduser.htm below.

-- Note --
The default password is 'password' - all lower case.
----------

Now, you need to make a few small edits:

--- robmail.cgi ---

--- Change the first line of this file to reflect the location of the perl
    interpreter on your system.  If the interpreter is at /bin/perl, then
    this line should be #!/bin/perl.  You can get this information with the
    command "which perl" or "where perl".  Check with the system admin
    if you're not sure.

--- $mailprog - change this to the location of the mail program on your
    system, followed by a -t.
    Ex: '/usr/bin/sendmail -t';
    Again, check with the sys admin if you don't know the location of your
    sendmail.

--- $maildir - change this to the location of your RobMail
    directory.  DO NOT include a trailing slash / !
    Ex: '/usr/people/eislerr/mail';
    Ex: '../mail';

--- $mailurl - change this to the URL of the RobMail HTML file.  Again,
    DO NOT include a trailing / !
    Ex: 'http://tdi.uregina.ca/~eislerr/mail/index.html';
    Ex: 'http://tdi.uregina.ca/~eislerr/robmail.html';

--- $yourname, $yourmail - these will go in the from: for your outgoing email.
    Ex: $yourname = 'Rob Eisler';
    Ex: $yourmail = 'rob@robplanet.com';

--- $cgi - this is the cgi call that goes in the <form action""> tag.
    Set this to call the robmail.cgi in your cgi-bin, exactly the same as what
    you will enter in the index.html (described below).
    Ex: 'http://tdi.uregina.ca/~eislerr/cgi-bin/robmail.cgi';
    Ex: 'http://tdi.uregina.ca/cgi-bin/cgiwrap?user=eislerr&script=robmail.cgi';

--- $queryswitch - this will be tacked on to the end of your $cgi when the script
    requires the use of query strings. Ok? :) It should either be '?' or '&'.

    Basically, if your cgi call already has a ? in it, then you need to use
    '&', otherwise it's '?'. Most people will want to use '?'.

    If your $cgi looks like this:
    'http://tdi.uregina.ca/~eislerr/cgi-bin/robmail.cgi';
    you should have $queryswitch = '?';
    If it's like this:                    *
    'http://tdi.uregina.ca/cgi-bin/cgiwrap?user=eislerr&script=robmail.cgi';
    use $queryswitch = '&';               *

--- adduser.cgi ---

--- as in robmail.cgi, change the first line to the location of your perl 
    interpreter, and change $maildir, $mailprog, $yourname, $yourmail as in
    robmail.cgi.

--- $confirm_additions - this is a switch for the confirm option. It should
    be either a 1 or a 0. If it's a 0, users will be added to the list as
    soon as they submit the adduser form. If it's a 1, users will first
    be sent an e-mail message with a URL they must visit. This will
    confirm their address and prevent people from signing up bad
    e-mail addresses.
    Ex: $confirm_additions = 1;
    Ex: $confirm_additions = 0;

--- $robmailcgi - this is the URL to robmail.cgi (NOT adduser.cgi) WITH A
    ? (or &) attached. This is the same as $cgi and $queryswitch from 
    robmail.cgi put together.
    Ex: 'http://tdi.uregina.ca/~eislerr/cgi-bin/robmail?';
    Ex: 'http://tdi.uregina.ca/cgi-bin/cgiwrap?user=eislerr&script=robmail.cgi&';


--- index.html ---

Change all 6 of the <form action=""> tags to point to the robmail.cgi on
your system.  These should be exactly the same as in the $cgi above. This is
the page from which you will run all the admin fuctions of the script.


--- adduser.htm --- 

--- Change the <form action=""> tag as in index.html above, but to 
    adduser.cgi rather than robmail.cgi .

--- In the line:
    <input type="hidden" name="list" value="testing">
    Change the word testing to the EXACT name of the list you want the
    users to be added to.  So, if you're using the default list, write:
    <input type="hidden" name="list" value="default">

-------

Ok!  That should be everything you need.  Good luck!  E-mail me with 
any trouble.

-------

Version Info

2.04b	Added e-mail confirmation to adduser.cgi
	Fixed case-sensitivity problem

2.04	Added function for the 'from:' field to adduser.cgi
	Added still more error checking
	Added file locking
	Fixed the browser times out during sendmail (for larger lists)
	Added optional forced word wrapping

2.03	Added more error checking
	Added function for the 'from:' field

2.02    Added adduser.cgi

2.0	First distributed version

-------

Rob Eisler
rob@robplanet.com
http://www.robplanet.com