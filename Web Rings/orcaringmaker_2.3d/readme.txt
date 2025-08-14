************************************************************************
* Orca Ringmaker v2.3d                                                 *
*  A comprehensive webring creation and management script in PHP/MySQL *
* Copyright (C) 2004 GreyWyvern                                        *
*                                                                      *
* This program is free software; you can redistribute it and/or modify *
* it under the terms of the GNU General Public License as published by *
* the Free Software Foundation; either version 2 of the License, or    *
* (at your option) any later version.                                  *
*                                                                      *
* This program is distributed in the hope that it will be useful,      *
* but WITHOUT ANY WARRANTY; without even the implied warranty of       *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        *
* GNU General Public License for more details.                         *
*                                                                      *
* You should have received a copy of the GNU General Public License    *
* along with this program; if not, write to the Free Software          *
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 *
* USA                                                                  *
************************************************************************

Table of Contents:
  - Script Requirements
  - Special Thanks
  - Changelog
  - Upgrade Instructions
  - Installation Instructions



************************************************************************
- Script Requirements

PHP 4.2.0+
MySQL 3.23+

NOTE: Some functions required by this script may be disabled if your
installation of PHP has "safe_mode" turned on.  If you get error
messages such as the following:

Warning: some_function(): Cannot [do so-and-so] in safe mode

You'll need to enable these functions or talk to your host about
enabling them.



************************************************************************
- Changelog
2.3d - Patch for Secunia advisory #17803 - MySQL Injection
       http://secunia.com/advisories/17803/


Full 2.x changelog:
  http://www.greywyvern.com/orca/orm_changelog.txt



************************************************************************
- Upgrade Instructions

  Upgrading your Ringmaker installation is easy, just follow these
steps:

 a) Compare head.php files
  Open both the old and new orm_head.php files and compare the user
variables.  Make sure the variables in the new version match the old.
The number of variables sometimes differ between versions, however, the
newer version always trumps the old.  See "Setup" section "i" for more
information.

 b) Check for new files
  Some versions might add new required files.  If you are upgrading to a
new version that has new files not used by your old version, upload
these files first to their appropriate locations.  See "Included Files"
for more information.

 c) Save your old styles
  If you customized your Ringmaker stylesheet or Control Panel template
in your previous version, make copies of these files to a new location
so you can refer to them later.

 d) Overwrite remaining files
  Simply overwrite all existing files with the files from the new
version, then visit your ring with a web-browser and your Ringmaker
installation will be automatically upgraded.

 e) Re-customize
  If you made style or template changes to your previous version, you
can now work them back into the new version.  Note that changes to the
default CSS and HTML *may* happen between versions, so take care when
moving old styles/templates into the upgraded version.  When changes are
made to these default files, I usually make a changelog note about it.



************************************************************************
- Installation Instructions

Contents:
  1. Welcome to the Orca Ringmaker!
  2. Included Files
  3. Setup
  4. Ring Administration
  5. Statistics
  6. Editing the Help Text
  7. Control Panel template
  8. A Basic Scenario
  9. Embedding the Ring


***** 1. Welcome to the Orca Ringmaker!

  The Orca Ringmaker is a GPL PHP/MySQL script for hosting a single
webring from your website.  You can host more than one ring at the same
website simply by giving different MySQL table names to each one (more
details on how to do this below).  However, if you're looking for a
dedicated ring hosting system for managing multiple rings, I recommend
Ringlink ( http://www.ringlink.org/ ).

  The main features of the Orca Ringmaker are its compact file layout
(only eight necessary files!) and the ability to embed the Ring Hub into
any existing web page layout.  Installation is simple, so let's begin!

***** 2. Included Files

The Orca Ringmaker comes with eight (8) *required* files:

ringmaker.php           - Plain ring setup
orm_head.php            - Header + configuration PHP
orm_body.php            - Body PHP
orm_style.css           - Stylesheet
orm_lang_en.php         - English language file
orm_template_menu.txt   - Control Panel template file
o_stats.png             - Statistics icon
phpmailer.php           - PHPMailer library

Make sure you have all of them!  If you need a language file other than
English, you can take a peek in the "langpack" which contains all
contributed language files to date.  You can download the file from my
site using this link:
  http://www.greywyvern.com/code/php/orm_langpack.zip

You can see what files this zip contains by visiting here:
  http://www.greywyvern.com/orca/orm_langpack.txt

The Ringmaker also comes with an optional Help Text file, named
"orm_help_en.txt".  Any translated versions will also be placed in the
langpack zip.


***** 3. Setup

  The Orca Ringmaker is designed so that most of the customization is
accomplished via a web interface rather than by typing variables
directly into the code.  Nevertheless, it is necessary to input a few
variables into the PHP directly for configuration; they are explained
below:

 i. Open the "orm_head.php" file.

  At the top of the file you'll see a list of variables under the
heading "User Variables".  These are variables you'll set globally
within the script.

 - $rData['admin'] & $rData['password']
  The Administrator account for the Ringmaker will not have a site
associated with their login.  This is so you can log in and edit the
ring settings without actually adding a site to begin with.  The values
of these two variables are the Administrator's login name and password
respectively.  Anyone who knows these values will have control over the
settings of your ring, so change them to something hard to guess!

 - $dData series
  These five variables all have something to do with the MySQL setup of
your hosting account.  The first four variables should have predefined
values which your host can provide to you if you don't know them
already.  The fifth variable: $dData['tablename'] will be the prefix for
creating a set of MySQL tables which will store information about this
ring.  Make sure you don't give it a name which already exists in your
database!

  If you want to host more than one ring at your site using the Orca
Ringmaker, just copy and paste the head file with a different name, like
"orm_head2.php" and just change the tablename to something else.  Then
include *that* file instead of the original for the second ring.

- $rData['PHPMailer']
  Versions of the Ringmaker 2.3 and later use the PHPMailer library for
more reliable emailing.  Include the path to the "phpmailer.php" file
in the $rData['PHPMailer'] variable.

- $rData['templateDir']
  The Ringmaker needs to know where you're keeping the Control Panel
template file.  By default, this variable points to the same default
directory assumed for the other Ringmaker files: "orca/".  In future
versions, more elements of the Ringmaker script may be templated.

  Variable setup is complete; you can now close the "orm_head.php" file.

  If you are upgrading your ring from version 2.2 or earlier, you'll
notice many of the ini variables in the orm_head.php file are no longer
present.  This is because they've been moved, for your convenience, into
the Ring Setup area, accessible through the web admin GUI.
Unfortunately, because these variables have been removed completely,
you'll need to reset them from their default values in the Ring Setup
area as soon as you upgrade.


 ii. Connect to your web server.

  Choose where you want to place this script.  You can always move it
later, but most likely you'll want it in the root directory (the same
place where your index.html or index.php resides).  What follows is a
typical setup, but you can place the files however you like, as long as
the includes in "ringmaker.php" point to the correct files.

  Upload the "ringmaker.php" file to the directory you selected.  Once
that's done, in the same directory create a folder named "orca" and
upload all the other files there.  When you're done, your filesystem
will have the following layout:

/ringmaker.php
/orca/orm_head.php
/orca/orm_body.php
/orca/orm_style.css
/orca/orm_lang_en.php
/orca/orm_template_menu.txt
/orca/o_stats.png
/orca/phpmailer.php

And, if desired:

/orca/orm_help_en.txt


 iii. The moment of truth...

  Now visit ringmaker.php through your internet browser.  If you get
the welcome message, the script has successfully created the databases
it needs and is ready to accept new members!  Let's move on to basic
ring administration.


***** 4. Ring Administration

  Using the login form in the control panel, log into the admin account
with the admin name and password you entered in the "orm_head.php" file.
The login section of the Control Panel will change and you'll be
presented with three options.  We'll go through them one by one.


 i. Ring Setup

  This is where you'll set up the main features of your ring.  Click
this link to open the main Ring Setup page.

  a) Basic Setup items

  A form will appear where you can specify the name of your ring, how
many ring sites to show on the Ring Hub page before pagination, and how
many sites to list in the "Top x Sites" chart on the statistics page.

  b) Email and SMTP

  Next, input the email address of the administrator.  This email
address will be used as the Reply-To address when the ring sends out
emails for notification.

  As of version 2.3, the Ringmaker uses the PHPMailer library to send
out emails to members.  By default, when the SMTP field is blank, the
library will use the simple PHP mail() function to send email.  Instead
of this setup, however, you may wish to use an actual SMTP server to
send messages.  Sending messages via SMTP is somewhat faster and also
bypasses those hosts which have disabled the mail() function.

  To make the script use SMTP to send email, just type in the name of an
SMTP server in the SMTP field.  "localhost" is a common entry which
essentially means: "Use the server my website is hosted on to send email
via SMTP".  You can specify multiple SMTP servers by separating each
entry with a semi-colon, like so: "localhost;mail.example.com".  You can
also specify port numbers other than the default by adding it after the
server name and a colon like this: "localhost:80;mail.example.com" or
"mail.example.com:6787".

  If the SMTP server you wish to connect to requires authentication, you
will need to open up the "phpmailer.php" file and add your username and
password to the marked variables near the top of the file.  Don't forget
to switch $SMTPAuth to true!  Don't touch any of the other variables in
this file unless you know what you're doing ;)

  c) Timezone and Timezone Offset

  For statistics and logging purposes, the Ringmaker needs to know what
time zone and offset to use for this ring.  You probably want to give
values for the time zone where you, the Administrator, are.

  d) Help Text

  You may optionally enable a Help page for your ring here as well.
Make sure the file as listed in the Help Text File list exists on your
server!  Once you enable this feature, a "Help" link will appear in the
Control Panel.  Clicking this link will display the contents of the
Help Text file as plain HTML.  Feel free to change this file however you
wish to fit the needs and/or requirements of your ring.

  e) Add Site form Image Authentication

  For some popular rings, the Add Site form is vulnerable to form-filler
robots which fill out the form in order to send bogus "Confirmation"
emails.  Note that nothing concerning the site being added is included
in this email so it cannot be used to send out spam.  However, that
won't stop determined bots from trying anyway.

  To stop this behaviour, the Orca Ringmaker optionally adds an image
authentication field to the Add Site form.  The user will be required to
type in a number displayed on an image in order to submit a successful
application.  This functionality is disabled by default and you can
enable it by selecting a font setting using the drop down menu here.

  Using this function requires PHP version 4.3.x or greater.  If your
version of PHP is older than this, you will need to have the GD library
installed.

  f) Statistics Caching

  By default, statistics caching is disabled, which means that each time
the Ringmaker script is loaded, it reads *all* the stored stats and
outputs the processed results.  For small to medium sized rings running
on decent servers, this processing lag won't be noticable at all.  When
your ring starts getting up beyond 10,000 hits over 8 weeks however, you
might find that there is a small lag each time you request a page.

  To rectify this, you can cache your stats either Hourly or Daily by
selecting the appropriate entry from the drop down menu.  Usually the
"Hourly" setting should be more than enough for *any* ring, but if your
ring stats get truly GIGANTIC (like, more than 100,000 hits over 8
weeks), you can set the stats to cache "Daily" instead.

  If you enable either hourly or daily caching of statistics, a dialogue
will appear on the Statistics page displaying how long it's been since
the last cache.  You might notice on occasion that the time it displays
is greater than the interval at which you've set the script to cache.
For example, you might have set the script to cache hourly and when you
visit the stats page later on, it says it's been 01:35:27 since the last
cache!

  This is because the Orca Ringmaker caches "passively", meaning that it
will only trigger a new cache if someone loads the page *after* the
interval has expired, and not *exactly* when it expires.  So during
times when no one is accessing your ring, the script won't bother
wasting server resources to cache.  If you refresh the page soon after
seeing the message that a cache took place over an hour ago however,
you'll find a new set of freshly cached stats. 

  g) Ring Announcement

  Finally, set an announcement for your ring.  This text will appear in
the top box of the Control Panel.  Use it to tell people what your ring
is about and any requirements for joining you may have.  You may include
any HTML you wish in the announcement, even links and images, so get
creative!  HTML tags will not count towards the 500 character limit for
this field.

  h) Navigation Bar

  Below this is the Navigation Bar editing station.  The Orca Ringmaker
can serve two different types of Navigation Bars: one served dynamically
from your site via remote javascript (like Webring.com) and a fallback
in plain HTML.  See the following URL for a list of advantages and
disadvantages of each system (courtesy of World of Webrings):
http://tinyurl.com/4omvl

  Some ringmasters will elect to use the Javascript Navigation Bars so
they can more easily perform ring-wide updates to the bar style.  Other
ringmasters might choose to distribute only the HTML Navigation Bar to
minimize requests to their server, and allow ring members to more easily
edit and modify the code.  When you first come to the Ring Setup page,
the content of both systems will be populated with default HTML.  Read
on to see how to modify these bars.

    *** Serving Javascript:
  Serving a javascript navigation bar provides you, the ringmaster, with
an easy way to modify the appearance of the bar on all member sites
simultaneously.  When a new member signs up, they will receive a small
script tag which they can paste on their site wherever they want the bar
to appear.  Each time their page is loaded, the script will request the
Javascript Enabled Navigation Bar (JENB) HTML from *your* server and
display it on their page.  If you make changes to this HTML via the Ring
Setup interface, the Navigation Bar which appears on member sites will
change also.

  Study the default JENB HTML code to see how the existing bar is set
up.  The current form visible to ring members will be displayed right
above this text area.  Note that the JENB HTML also accepts ring codes
which will be substituted with Ringmaker variables before it is sent to
that member's page.  A list of supported codes is as follows:

--scriptURL--   The full URL of your ring
--id--          This site's Site ID, useful for creating proper links
--ringname--    The name of the ring as specified in Ring Setup
--ringemail--   The Ring Administrator's email address

  Be careful when inserting new code into the JENB area, since if the
HTML is invalid or malformed, it is quite possible that the ring will
serve garbage or nothing at all.  I recommend coding the HTML in a file
on your desktop, and making sure it works there before pasting it all
into the JENB text area.

  Made a mistake?  It's easy to revert to the default JENB code, just
erase *everything* in the JENB textarea (including any returns or
spaces) and submit it.  The original JENB code will be written in again.
This also works for the "Javascript Disabled" text area too!

  Along with the JENB code, new members will also receive a copy of the
Javascript Disabled Navigation Bar (JDNB) HTML within a set of
<noscript> tags.  This means that if Javascript is enabled, users will
only see the JENB bar, and the JDNB bar will be hidden.  When serving
Javascript, it is common practice to serve an intricate JENB bar along
with a minimal JDNB bar that just links to the Ring Hub.  In fact, this
is the default setup.

    *** Serving plain HTML:
  If you'd rather not serve a Javascript dependent Navigation Bar, you
can elect to distribute your ring code in plain HTML instead.  Doing
this means that you won't be able to modify the Navigation Bar once
you've given it to a new member; instead you're entirely dependent on
each ring member to update their code manually if you ever decide to
change it.  On the other hand, there will be no requests to your server
for the JENB which might be a good deal if you expect your ring to be
*really* popular.

  To disable the JENB, simply type an asterisk (*) as the very first
character in the JENB textarea.  Submit this and from then on, until you
remove the asterisk, the contents of the JDNB will be served as the
official Ring Code without the containing <noscript> tags.  For this
setup, you'll want to equip the JDNB with all the appropriate links as
in the JENB.  For a quick start, simply copy the default contents of the
JENB and paste it into the JDNB text area.  All the codes supported by
the JENB are also supported by the JDNB, so your new members will be
given the plain HTML representation of the default JENB.  When the JENB
is disabled, the JDNB will be written to the "Current Navigation Bar"
block above.

  Note that disabling the JENB in this fashion DOES NOT prevent your
ring from fulfilling javascript requests for the navigation bar HTML.
Therefore, unless you've disabled it before allowing anyone to join your
ring, it is recommended that you keep all the HTML after the asterisk so
the JENB can continue to function on the sites which received the JENB
ring code. Don't worry, the asterisk will be stripped from the JENB code
before being sent :)

  Moving on, and before we finally leave the Ring Setup area, there are
two more Setup items which will not be visible until there are actually
sites in the ring.  You might want to come back and read this section
once they appear, although their functions are pretty straightforward.

   i) Promoting other ring members

  The first item is an "Administrative Status" toggle.  This tool allows
you to promote other members of the ring to Administrators.  A normal
ring member only has the ability to log in and edit their own site
details, while a member with Admin status will have access to the "Send
Email" and "Administration" areas.  *Only* the main Administrator
account has access to the "Ring Setup" area, to prevent feisty ring
member admins from promoting others, or changing any other Ring Setup
details.

  Promoting other ring members can be a good way to let others help you
manage your ring by giving them the ability to send out emails to ring
members, edit other members' site details, and approve/deny new
applications.

   j) Reordering ring sites

  The other item you won't be able to see yet on the Ring Setup page, is
a ring reordering dialogue.  This feature is useful for keeping things
fair when certain sites in your ring generate a lot more hits than
others.  There are three options, Reorder the site list by either Site
ID or Site Title, or Randomize the site list.

  It's a good idea to randomize your site order every now and then so
that sites which generate a lot of hits aren't only benefitting the next
site down the chain.  Or, if this doesn't matter so much to you, you can
order the sites by Title to make things look nice and neat.

  It is important to note that these reordering functions are
instantaneous only.  If you reorder your ring by Site Title and then a
new site gets added, that new site will be added in the first available
open Ring slot, *not* inserted in alphabetical order.  You will need to
hit the Reordering function again to move new sites to the correct
positions.


 ii. Administration

  This is the control center of the script, and will only appear once
there are sites in the database.  Here you, and any members you may
promote, can approve or decline memberships and even edit the details of
existing memberships.  Sites can also be switched to one of the various
status levels.  The four status levels are as follows:

 Inactive:    Site has not yet been approved
 Active:      An active site in the ring
 Hibernating: A user may hibernate their site if they expect it to be
               down or under construction.  This prevents them breaking
               the ring
 Suspended:   Site has been removed from the active list because of some
               breach of guidelines (eg. navigation code was removed)

  When editing a member site's details, there is also an option here to
have the script automatically check for the existence of the Navigation
Bar code on the target page.  Currently the script only checks for the
JENB, so if you have disabled this, you will get a Not Found error.

  Using this function is for CONVENIENCE ONLY.  It is not intended as a
replacement for checking the site yourself.  Many ring members modify
the code or capture the HTML and discard the JENB which will prevent the
checker from finding the Navigation Bar code.  Before getting trigger-
happy and suspending sites because the checker says "Not Found" or
"Error" make sure to actually visit the site in question first. :)

  It is actually much more accurate to keep an eye on the stats for this
purpose instead.  If one of your member sites is getting hits, but
generating zero clicks, it's a good possibility that the site isn't
properly displaying a navigation bar of any kind.

  For this purpose a list of sites causing the most errors in your ring
will appear at the bottom of every Administration section page.  An
"error" occurs when the Ringmaker script tries to contact a member site,
but gets an HTTP error message in response, such as a 404: Not Found.
This list is sorted on the "Three Days" column by default, so make sure
to check it every now and then to see if any sites are causing errors.

  The numbers for this list are drawn directly from the statistics of
your ring.  If you are caching statistics, these error listings will be
cached as well, and will not update until the next statistics cache is
performed.


 iii. Send Email

  In this section you can send email to individual, subsets of, or all
ring members; useful for sending out ring announcements, for instance,
only to the active ring members.  Sending out an email to "All" members
will even send mail to members waiting to be approved.

  If the "Selected" radio button is checked, you can select ring members
from the list below to send email to.  You can select more than one
member by holding down CTRL (on Windows/Linux) when clicking here.


***** 5. Statistics

  The Ringmaker comes with a highly sophisticated stats system, which
reports stats down to the hour.  To find the Statistics section you can
click on "Statistics" in the Control Panel which will bring up a default
result set concerning your ring by itself, or you can click the stats
icon beside each displayed site on the Ring Hub.  The Ring Hub links
will bring up a detailed result set concerning the associated site with
many goodies for you to look at.

  In the Orca Ringmaker a "hit" is a user *arriving* at a site because
of a ring link, and a "click" is a user *leaving* a member site because
of a ring link.  On the detailed stats page, you'll see a breakdown of
all the hits and clicks associated with any site.  If javascript is
enabled, many of the stats become interactive where you can press a
button to switch from reporting hits to clicks, and even sort the Top x
Hits/Clicks list by three, fourteen or fifty-six days!

  If you are upgrading from a 2.0x version of the Ringmaker, the script
will take your old stats and redestribute them, as best as it is able,
into the new system.  You may notice a decrease in overall hits/clicks
when the transition is made.  This isn't because some hits/clicks are
being lost, but because they are now counted more accurately, with hits
from inactive sites to active sites (and vice versa) not counting in the
tally like they did before.  As well, the browser stats and tallies of
link type (Next, Previous, Random & Direct) will not be set for the
updated hits/clicks.  New hits and clicks will soon populate these
statistics.


***** 6. Editing the Help Text

  You may optionally enable a Help page for your ring in the Ring Setup
area.  If you've enabled the Help Text, a "Help" link will appear in the
ring Control Panel.  When someone clicks this link, the Ringmaker will
grab the contents of the file you specified and paste them *as-is* to
the page.  For security, PHP code is not executed in this file, so only
plain text and HTML is accepted.

  A default Help Text file is included with your installation.  Use this
file to build your own Help Text tailored specifically for your ring.
This is a great place to put join requirements or a detailed explanation
of what your ring is all about.

  Keep in mind that there is some CSS in the orm_style.css file which
affects the way the Help Text HTML appears.  Feel free to adjust the
contents of both files to match your own website layout.


***** 7. Control Panel template

  Because the Control Panel is the most restrictive element controlling
the Ringmaker default layout, it is now loaded from an easy-to-edit
template.  Before jumping in to configure the Control Panel layout, open
the orm_template_menu.txt file and examine the codes.  You'll find
four types of codes which are explained briefly below.

Note that all names referred to in these codes pertain to hash values of
the $_ORMPG array.  If you open the orm_head.php file and scroll down
almost to the bottom, you can see where many of these variables are
assigned.  You can add your own values simply by adding lines with new
hash names.

 - Template code types:

%{var}%
  This code simply prints the value of the variable within the curly
braces.

&{var}& ... &{}&
  A simple "if ... then" structure.  If "var" is true, non-zero or
contains text, then everything between the two bits is kept within the
template.  Otherwise it is removed.

!{var}! ... !{}!
  The opposite of the above.  If "var" is false, zero or is an empty
string, then the contents between the start and end bits is kept.
Otherwise it is removed.

={var : ~ : val}= ... ={}=
  A complex "if ... then" syntax allowing the use of specific comparison
expressions.  These expressions must always consist of a variable name,
a single-character comparison operator, and a number or text value, IN
THAT ORDER.  All values must be separated by a colon (:).  Thus the
following are legal expressions:

  ={var:>:0}= ... ={}=       // If var is greater than 0
  ={var:=:Google}= ... ={}=  // If var equals the string "Google"
  ={var:!:false}= ... ={}=   // If var does not equal the string "false"

  Expressions that evaluate to false will have their contents removed
from the template, while those evaluating to true will remain.  Once all
expressions have been evaluated, then all %{var}% instances are replaced
and the result is output to the client.

  It is important to note that all of the &{}&, !{}! and ={}= blocks can
be nested just like "if" blocks in real programming languages.

  Lastly, you can also insert comments by having a # character as the
first character on a line.  Everything on that line after the # will
then be ignored.


***** 8. A Basic Scenario

  Based on my own use of this script, here is, IMHO, the most efficient
scenario for Orca Ringmaker use:

  First create the ring, log in using the Administrator login, and edit
the Ring Setup details.  Once the ring is set up to your liking, logout
of the Administrator account and then add the first site, most likely
your own, using the Add Site form.  Once the application has been
confirmed (you'll get an email with a link to confirm the application),
log back into the Administrator account and approve the site for ring
membership.

  Now head to the Ring Setup area.  The Administrator toggle tool will
be available!  Select yourself from the dropdown and promote yourself to
Administrator status.  Log out of the Administrator account again.

  Now when you login to your site account, you'll have access to the
Email and Administration functions.  This setup is ideal because once
you've set up your ring using the Ring Setup tools, you shouldn't need
to access it very often afterwards.


***** 9. Embedding the Ring

  Already have an existing webpage layout?  Great!  The Orca Ringmaker
was designed to be easily embedded into almost any layout you can throw
at it.  Want to know how?

  First, let's open the "ringmaker.php" file and examine the code.  This
file is actually just a sample "bare-bones" setup for the ring.  You
can see the plain HTML layout with the PHP includes here and there that
activate this page as a Ring Hub.  To activate any other page, just
follow these four steps:

 i.   Include the "orm_lang_en.php" file (or your preferred language
      file) before any whitespace.

 ii.  Include the "orm_head.php" file before any whitespace and *after*
      the "orm_lang_en.php" file.

 iii. Include the "orm_style.css" stylesheet using a <link> tag in the
      <head> of your page HTML.  Tip: include this stylesheet *after*
      your existing stylesheets so that it's rules are applied last in
      the cascade.

 iv.  Include the "orm_body.php" file in the *content* area of your page
      layout.

  Those four steps, correctly followed, will activate any .php page as
a ring hub!  The script uses styles "passively" meaning most of your
existing page CSS, like link colours and font styles, should show
through.

Additionally the ring is almost 100% styleable using the CSS in the
orm_style.css file. Change your borders, colours and margins...
anything!  A little work at editing the CSS and you can make the script
look like it was built especially for your site!

************************************************************************
* Please send all questions/comments/bugs to orcaring@greywyvern.com   *
*                      -------------------------                       *
* Thanks for using my scripts!  I hope you enjoy them as much as I do  *
************************************************************************