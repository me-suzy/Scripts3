BENRUTH INSTANT RSS/XML FEED READER README NOTES
Copyright (c) 2005, BENRUTH SOFTWARE CONSULTANCY ( www.benruth.com )
All rights reserved.

Version Stable Build 1.5
Last Updated 08 Jun 2005
Released 15 Jul 2004
=======================

Reads RSS/XML compliant news feeds via a client-side Javascript Script Include in your web pages! 

Configure the source of the news feed via a single configuration file. 

Simple, free and easy to use!


Features-At-A-Glance
=======================

- Use a client-side Javascript Script Include to retrieve RSS/XML News Feeds.

- Change the News Feed Source via a Configuration script.

- Allows Caching of the RSS Feeds.


Simple Installation!
=======================

1. Download all the files to a single location in your web server.

2. To Use, include this small snippet in the portion of the HTML file that you would like the news feed to appear:

<SCRIPT language=JavaScript src="http://yourdomain.com/instantfeedreader.php"></SCRIPT>

3. Open up instantfeedreader.confic.inc.php  and change the location of the news feed URL to your desired one.


MULTIPLE News Feeds OPTION ONE : 

This is for those who wants to display specific news feeds.

You can display MULTIPLE News Feeds on your website if you have a few installations of this in seperate folders.

eg. 
/newsfeed1/instantfeedreader.php
/newsfeed2/instantfeedreader.php
/newsfeed3/instantfeedreader.php
/newsfeed4/instantfeedreader.php

And your javascript in your website would look something like

<SCRIPT language=JavaScript src="http://benruth.com/newsfeed1/instantfeedreader.php"></ SCRIPT>
<SCRIPT language=JavaScript src="http://benruth.com/newsfeed2/instantfeedreader.php"></ SCRIPT>
<SCRIPT language=JavaScript src="http://benruth.com/newsfeed3/instantfeedreader.php"></ SCRIPT>
<SCRIPT language=JavaScript src="http://benruth.com/newsfeed4/instantfeedreader.php"></ SCRIPT>


MULTIPLE News Feeds OPTION TWO : 

Leave $sFilename (in the configuration file) as "" to use the URL version
example

In config file
$sFilename = "";

In the javascript
<SCRIPT language=JavaScript src="http://benruth.com/instantfeedreader/instantfeedreader.php?feed=http://www.benruth.com/feed.xml"></ SCRIPT>

The reasoning behind this is to prevent other people from supplying any feed in the url and use up your precious server
resources, if you only want specific news feeds to be read.

So, either supply a filename in the config file, and ENSURE that it is the only feed that you serve

OR, leave the filename blank in the config file, and allow possibly anyone to type in any feed url.


There would not be any technical assistance on this script. However you can pop a question or so to me at my
email ( ben AT benruth DOT com ). It is good to say hi!

You could visit my website at www.benruth.com

Thank you once again!


Donation-Ware
=======================

If somehow you think this script has helped you in some way, why not support the developer by donating any amount
to his paypal account ( ben AT benruth DOT com ). It will be very appreciated! :-)

