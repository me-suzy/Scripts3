#############################################################################
#############################################################################
##                                                                         ##
##                  ________   __         _     __      __                 ##
##                 / _______|  | |       |_|    \ \    / /                 ##
##                 | |         | |        _      \ \  / /                  ##
##                 | |         | |       | |      \ \/ /                   ##
##                 | |         | |       | |      / /\ \                   ##
##                 | |______   | |_____  | |     / /  \ \                  ##
##                 \________|  \_______| |_|    /_/    \_\                 ##
##                                                                         ##
##                             Script by CLiX                              ##
#############################################################################
#############################################################################
##  PHPHomeXchange                Version 2.0                              ##
##  Created 1/15/01               Created by CLiX                          ##
##  CopyRight © 2002 CLiX         clix@theclixnetwork.com                  ##
##  Get other scripts at:         theclixnetwork.com                       ##
#############################################################################
#############################################################################
##                                                                         ##
##  PHPHomeXchange Users are subject to the TOS at theclixnetwork.com. TOS ##
##  can change at any time. You MAY NOT redistribute or sell the script in ##
##  any way. If you intend on selling the site created from PHPHomeXchange ##
##  you must contact us for permission first!                              ##
##                                                                         ##
#############################################################################
#############################################################################

Thank you for purchasing the best script The CLiX Network has to offer! We 
hope you get it setup quickly, and find it very usefull. This version is MUCH
more stable and easier than the CGI version.

************************************************
Setting It Up

What you'll need to do first is setup your MySQL database and table. We reccomend
using phpMyAdmin because of its usefullness. If you dont know how to create a MySQL
database, ask your host. They may have some tool you aren't aware of, or they might
have to do it themselves. Just get it made. Then you need to create a table for user
data. To do so, create a table according to tablesetup.txt If you have phpMyAdmin
you can just click the database name, and where it says 'or Location of the textfile'
you can just find the tablesetup.txt and click Go. This will automatically setup the
table.

After setting up the MySQL part of it, you'll need to change the variables in config.inc
The most important variables are the MySQL variables. Make sure you get them right! The 
rest is just the exchange credit ratios, which you can leave like that or swap them around.
Just be sure not to edit below the line that says "DO NOT EDIT BELOW THIS LINE"

** Upgraders.. At this time, edit transfer.php to have the correct directory of your CGI version's data folder and then run it **
** This will convert your user's info to the MySQL database. They will however need to retype their urls **

Now that that is all setup, you'll need to upload all the files. Once that is done, CHMOD
the banip.txt, banemail.txt, banurl.txt files to 666.

Finally, You'll need to customize your exchange. To do so, put the page headers in header.inc,
and the footers in footer.inc. Then you'll want to make sure your TOS and INDEX [.php] pages
are good. These will automatically put the header and footers in for you, so dont worry about
that. They also will track referals and logged in users, so just put the html between the "?>"
and "<?php" tags. You'll also want to change firstframe.inc and secondframe.inc. These are your
start page exchange ad bars. You can put your banner html on them, and buttons to report users
and to goto the next site. The javascripts to do these are 'refr()' and 'repr()'. Please note
the proper way to link to javascript functions is:
<A HREF="javascript:refr()">Next Site</A>

Notice also that the refr() and repr() functions are automatically put in the frame pages. There is
an example in firstframe.inc and secondframe.inc of StartXchange.com's current frame pages.
Use these as examples, but change them..  Dont want to copy us now do you?

************************************************
Tips

1)  Dont over due credits given. If you add up all 5 levels [0-4] and it is less than 1, than
    your users credits will be used up slowly [the closest to 0, the faster they are used]. If
    it is over 1, then you'll end up owing lots of hits to users. Having a total of .9 is good
    because it gives you room to sell credits, and give bonuses for refering users.
    
2)  Keep the ad bars graphics friendly. Dont fill it up with slow loading things, make them as
    fast as possible. This lets users surf more faster, and gives time for the user pages to
    load. If you make the adbar too big, people wont like it.
    
3)  Not too fast, Not too slow. If you make your ad bar refresh too quickly, user pages wont load
    for everyone in time. If you make it too long, those with fast connections will get bored with
    it. Find the best time frame for your users!
    
4)  BE PREPARED. Make sure your site won't stop working because you use too much bandwidth. Keep
    the graphics down if you have a limit. A decent sized exchange with minimal graphics will get
    about 10 GB a month of bandwidth
    
5)  Make a TOS. True story: StartXchange was shut down because a member was spaming. StartXchange
    got back up because the abuse team saw the TOS, and notified them of the spammer. Moral of the
    story: The TOS can keep YOU from being shut down.

6)  If you block a users email address, ip, and sites, and they still sign up with that same username, you
      can lock their account by putting '2' as their 'ispaused' value.


Thats it. If you need any help, use the contact form in the clients area at
www.theclixnetwork.com

Thanks for purchasing PHP Home Xchange 2.0, Single Site License