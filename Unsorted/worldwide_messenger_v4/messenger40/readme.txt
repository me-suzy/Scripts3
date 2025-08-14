World Wide Messenger 4.0

The World Wide Messenger 4.0 is the premiere Hotmail? type script.  With this script, 
and provided your server meets the necessary requirements, you will be able to emulate 
many of the features of Hotmail?.

The Messenger 4.0 works off of one or more POP boxes.  Only one POP box is required, 
but we have included support for multiple CATCH-ALL POP boxes.

A CATCH-ALL POP box is a normal POP3 box.  It is no different than any other POP 
box.  Why it is called a CATCH-ALL is because this box is designated to catch all mail 
for a given domain.  Some hosts, may also call this a master box.  

This is how it works.  When you set up your domain at your host, almost every host 
allows you to route all mail to the given domain, to one POP box.  In other words, if  you 
own the domain: domain.com.  In order to create a CATCH-ALL box for this domain, 
your host would have to route (or forward) all email with domain.com in the To: field, to 
arrive to the one POP box.  So, if someone sends mail to joe@domain.com, it will go to 
the CATCH-ALL box.  If someone sends mail to bob@domain.com, it will be delivered 
to the SAME CATCH-ALL box.  If someone sends mail to lucy@domain.com, it too, 
will be delivered to the exact same CATCH-ALL POP box.  Every host we have ever had 
experience with, offers this option.  If your host does not offer this option, we suggest 
you find another host.  The host we recommend the most, is Valueweb.net.

If you are going to set up the Messenger to give away email addresses with different 
domain names, we RECOMMEND you forward mail from each domain, to the same 
CATCH-ALL POP box.  For example, if you are going to give away email addresses for 
domain.com, yourdomain.com, and anydomain.com, you would set up a CATCH-ALL 
with one of the domains, lets say, domain.com.  Now have all mail going to domain.com 
to be delivered to the domain.com CATCH-ALL POP box, also have all mail going to 
yourdomain.com go to the CATCH-ALL POP box for DOMAIN.COM, NOT 
yourdomain.com.  Do the same thing for anydomain.com.  This way, all mail is being 
sent to the exact same CATCH-ALL box.  

You can setup the Messenger to use individual CATCH-ALL boxes for each domain, but 
it may slow down the Messenger considerably.

The Messenger uses a module called POP3.pm to access the POP boxes.  Used as 
NET::POP3.  This is a part of the libnet package, available (free) at cpan.org.  We have 
included a method to bypass POP3.pm by using Socket, but this will limit the size of 
attachments to 250kb, and may also be unstable, as Socket is a limited module, and has 
many pitfalls when used for this method.  POP3.pm utilizes Socket itself, but does so in a 
way where it will close any Timeouts, on universal platforms, that Socket alone, will not 
do.

We HIGHLY Recommend using NET::POP3 instead of Socket.

This script also uses the UNIX program called Sendmail, for the SENDING of mail.  We 
have also included a Socket routine to bypass Sendmail, if you cannot use Sendmail or 
you do not have it.  You will be required to use a SMTP server and have it's IP address 
handy.

We have not experienced any problems using Socket for sending mail, however, we still 
recommend Sendmail.
 
This software has had LIMITED testing on NT platforms, and may require special 
adjustments to run on NT, but from all the testing we have done, we have not noticed any 
problems using this software on Perl enabled, Windows platforms.

WorldWideCreations.com hereby assumes NO responsibility for any damage this 
software may cause to any equipment or software whether deliberate or accidental.  An 
Administrator, who is responsible for all security updates we may issue, must maintain 
this software.  We offer this software AS IS, and are not responsible for any customizing 
you wish to do to this software.

YOU MAY NOT REDISTRIBUTE THIS SOFTWARE IN ANY WAY!  We reserve 
ALL RIGHTS to this software.  By installing this software, you hereby agree to this.

 World Wide Messenger Version 4.0 by World Wide Creations (TM)
 Copyright  c1999-2001 World Wide Creations All Rights Reserved

 As part of the installation process, you will be asked
 to accept the terms of this Agreement. This Agreement is
 a legal contract, which specifies the terms of the license
 and warranty limitation between you and World Wide Creations
 You should carefully read the following terms and conditions before
 installing or using this software.  Unless you have a different license
 agreement obtained from World Wide Creations, installation or use of this software
 indicates your acceptance of the license and warranty limitation terms
 contained in this Agreement. If you do not agree to the terms of this
 Agreement, promptly delete and destroy all copies of the Software.

 VERSIONS OF SOFTWARE
-----------------------------------------------------------------------
 Only one copy of the registered version of The World Wide Messenger may used
 on one web site owned by one owner or an entity.

 LICENSE TO REDISTRIBUTE
-----------------------------------------------------------------------
 Distributing the software and/or documentation with other products
 (commercial or otherwise) or by other than electronic means without
 World Wide Creations prior written permission is forbidden.
 All rights to the World Wide Messenger software and documentation not expressly
 granted under this Agreement are reserved to World Wide Creations.

 DISCLAIMER OR WARRANTY
-----------------------------------------------------------------------
 THIS SOFTWARE AND ACCOMPANYING DOCUMENTATION ARE PROVIDED 
"AS IS" AND WITHOUT WARRANTIES AS TO PERFORMANCE OF 
MERCHANTABILITY OR ANY OTHER WARRANTIES WHETHER EXPRESSED 
OR IMPLIED.   BECAUSE OF THE VARIOUS HARDWARE AND SOFTWARE 
ENVIRONMENTS INTO WHICH THE WORLD WIDE MESSENGER MAY BE 
USED, NO WARRANTY OF FITNESS FOR A PARTICULAR PURPOSE IS 
OFFERED.  THE USER MUST ASSUME THE ENTIRE RISK OF USING THIS 
PROGRAM.  ANY LIABILITY OF WORLD WIDE CREATIONS WILL BE 
LIMITED EXCLUSIVELY TO PRODUCT REPLACEMENT OR REFUND OF 
PURCHASE PRICE. IN NO CASE SHALL WORLD WIDE CREATIONS BE 
LIABLE FOR ANY INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES 
OR LOSS, INCLUDING, WITHOUT LIMITATION, LOST PROFITS OR THE 
INABILITY TO USE EQUIPMENT OR ACCESS DATA, WHETHER SUCH 
DAMAGES ARE BASED UPON A BREACH OF EXPRESS OR IMPLIED 
WARRANTIES, BREACH OF CONTRACT, NEGLIGENCE, STRICT TORT, OR 
ANY OTHER LEGAL THEORY. THIS IS TRUE EVEN IF WORLD WIDE 
CREATIONS IS ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. IN NO 
CASE WILL WORLD WIDE CREATIONS'S LIABILITY EXCEED THE AMOUNT 
OF THE LICENSE FEE ACTUALLY PAID BY LICENSEE TO WORLD WIDE 
CREATIONS.

Installation:

Installation is fairly simple with this script, but if you are having difficulty, please go to 
our FORUM or CHAT room on www.worldwidecreations.com. Or you can try our 
installation service.

Unzip the software, and go to the folder Messenger folder.  Open each of the files in the 
MESSENGER folder, that ends with .cgi.  At the top of each of these, if you see a path to 
Perl (#!/usr/bin/perl), change this to reflect your servers path to Perl.  Generally, this is 
#!/usr/bin/perl, but may also be #!/usr/loca/bin/perl.  If you do not know your path to 
Perl, refer to your server/host documentation.  If you do NOT see a path to Perl in the 
given .cgi file, then DO NOT ADD IT!

Close and save all of these files.

Open your FTP program and login to the server you wish to install the software on.

Open your cgi-bin, and create a folder called messenger.  Open this folder and create 
FOUR more folders.  Name them:

att

templates

tmp

email

Upload all of the files, IN ASCII MODE, that are in the messenger directory, except 
do.htm, that was created when you unzipped the zip file, to the messenger directory you 
created on your server.  CHMOD each of these files 755 (-rwxr-xr-x).  Open the 
templates folder created when you unzipped the zip file, and open the templates folder 
you created on your server.  Upload all of these files, IN ASCII MODE, to the templates 
directory and CHMOD them 755.

PLEASE NOTE!  If you do not upload these files in ASCII MODE, you will get server 
500 errors.  THIS IS CRUCIAL!

Now, move your FTP program to the root directory of your website.  This is the same 
directory where your index.html file is located.  In other words, if your website is 
www.mywebsite.com and someone enters that into their browser, it will load up 
index.html or index.htm.  This is your main page of your site, and this is where you want 
your FTP program to be.

Remember the URL to this location, which should be http://www.yourwebsite.com.

From here, create TWO folders:

javascript

icon

Now open the do.htm file and find these lines:

	<SCRIPT SRC="http://www.yourserver.com/javascript/os.js"></SCRIPT>
	<SCRIPT SRC="http://www.yourserver.com/javascript/os2.js"></SCRIPT>

Change this to reflect the path to the javascript folder.  (Leave in os.js and os2.js).  You 
should only have to change the domain name.

Save and upload this to the root directory (where YOUR index.html file is).

Now open the javascript folder created by the zip file, and open the javascript folder you 
created on your server.

Open in your text editor, the file called os.js and find this line, right at the top of the file:

var L_EMOTICONPATH_TEXT = "http://www.yourserver.com/icon/";

Change this to reflect your path to the icon folder.  DON'T FORGET THE TRAILING 
SLASH!  You should only have to change the domain name.

Save this file, and then upload all of the files in the javascript folder to the javascript 
folder you created on your server.

Exit the javascript folder, and enter the icon folder that was created when you unzipped 
the zip file, and the icon folder you created on your server.  Upload all of the icons, to the 
icon directory in BINARY MODE!

From here, if you are UPGRADING the Messenger from 3.0 to 4.0, you will want to go 
back to the cgi-bin/messenger folder, and open the convert.cgi script.  Find these two 
lines:

$basepath = "/home/yoursite/web/messenger/";

###- Domain for the users in this folder

$domain = "yoursite.com";

These variables are the same variables in your Messenger 3.0 setup file, so 
copy and paste the variables from the setup.pl file, into the convert.cgi 
script.

Save, upload.

Now call the convert.cgi script through your browser, and it SHOULD create the 
necessary databases for your existing users.  If you get an error here, make 
sure your $basepath variable is correct and that you included the trailing 
slash.

INSTALLATION CONTINUED:

Now you need to know the basepath to the cgi-bin/messenger.  We have included a 
script called basepath.cgi, which MAY give you your servers path to the 
messenger directory, and may not.  If it returns something like /cgi/cgi-wrap/ 
then it is incorrect.  This usually works on 90% of servers.

Otherwise, you will have to consult your server/host documentation to get your 
basepath.

Open the setup.pl file that came with Messenger 4.0 and find this line:

$basepath = "/home/path/to/cgi-bin/messenger";

And insert your path WITH OUT A TRAILING SLASH.  Do NOT insert a trailing slash 
here.  There can also be NO SPACES.

Save, upload.

Now you are ready to run admin.cgi.  Call it in your browser like this: 
http://www.yourserver.com/cgi-bin/messenger/admin.cgi

For the username enter: Admin
For the password enter: default

Click Modify Settings.

Most of these are self explanatory, but here are some of the important settings you must 
note.  All fields MUST be defined!

Please enter your path to sendmail (UNIX) - Enter your path to Sendmail, which is 
normally something like /usr/lib/sendmail  Do NOT enter any spaces.

Use World Wide Creations Email Encryption? - This will allow users to send encrypted 
mail, as long as they use the World Wide Decryptor, available at 
www.worldwidecreations.com.  YOU MAY NOT REDISTRIBUTE the World Wide 
Decryptor!  

The URL path to messenger.cgi (i.e. http://www.yourserver.com/cgi-bin/messenger) no 
trailing slash! Include http:// - This is a MUST.  Do NOT include the trailing slash!

The URL path to the directory to do.htm (i.e. http://www.yourserver.com) no trailing 
slash! Include http:// - This is the path to your root directory where you put do.htm.  It 
should look like http://www.yourserver.com  NO trailing slashes, no filenames, just the 
URL path!  If you get Javascript errors when sending mail, it is most likely this setting 
that is incorrect.

NET::POP3 error messages on? (Only if you are having trouble receiving mail and you 
need to debug) - This may, on some servers, report errors, if you are having trouble 
receiving mail.  DO NOT LEAVE THIS ON, unless you are debugging.

Administrator Password - You MUST change this to your desired admin password!

Below are the ten questions that can be asked in the contact book the first question IS 
REQUIRED TO INDICATE THE CONTACTS EMAIL ADDRESS - These are the 
questions that will be asked when someone creates a contact on their contact list.  You 
can name these whatever you wish, BUT the first one must indicate the contacts email 
address!

Click submit, and then go back to the main menu.

ClickAdd/Modify Domains.

Here is where you setup the info for your CATCH-ALL box(s).

The first entry, should be the domain, that contains the CATCH-ALL.  Include the 
location, username and password.

Click submit.  If you wish to add more domains, feel free to do so.  We recommend they 
all use the same CATCH-ALL box, and if they do, do NOT re-enter the location, 
username and password for the other domains.  Only enter information in other domains, 
if they require you use a different POP box.  This will slow down the messenger 
somewhat, if you decide to use multiple POP boxes, but not if you have all mail for all of 
your domains, routed to ONE POP box.

Decide which one you want to be listed first, and click submit.

Now you can add names to the restricted database, that you don't want people to be able 
to register, for example, webmaster, admin, abuse, etc..

The other functions are self explanatory, and this is all that needs to be done to get 
started.

You should now be able to run messenger.cgi and register a user, and use the Messenger 
4.0.

TEMPLATES:

About 99% of the messenger can be customized, by the templates located in the 
templates folder.

These files contain straight HTML and Javascript.  You will see tags in the templates, 
like <!-URL? etc.  These are REQUIRED tags, so keep this in mind when editing.

Be careful when editing Javascript.

About 90% of all the error messages, and messages given in redirect screens, can be 
edited in the oops.cgi file located in the messenger folder.  All of the error messages call 
a template called oops.htm, which you can edit to look whatever way you wish.

We have included a set of plain templates that have little to no serious HTML in them 
along with the stock templates.  You may wish to use these, as it will be easier to edit 
them to the look of your site.

Javascript Errors:

If you have Javascript errors, this is most likely because of your do.htm file being setup 
incorrectly, or the os.js file being setup incorrectly, or the modify settings field for path to 
do.htm being incorrect.

SUPPORT:

All of our support is done via our Forum, or our Chat Room (when manned).  Generally, 
we do not do support via email, as it just is not possible, due to the popularity of the 
Messenger.  Please look over all of the information currently in the Forum, before 
posting, as it is likely your question has already been asked/answered.
