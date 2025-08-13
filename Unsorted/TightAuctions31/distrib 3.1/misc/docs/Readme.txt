
Readme for TightAuctions

------------------------------------------------------------------------

END-USER LICENSE AGREEMENT

NOTICE TO USER: PLEASE READ THIS CONTRACT CAREFULLY.  BY USING ALL OR ANY PORITION OF THE SOFTWARE YOU ACCEPT ALL THE TERMS AND CONDITIONS OF THIS AGREEMENT.

(i) The TightAuctions source code may be modified at the user's risk, but the software 
(altered or otherwise) may not be distributed to other entities with the explicit written 
permission of TightPrices. In other words, TightAuctions may be modified for your use only. 
Under no circumstances may any modified TightAuctions code be distributed unless an explicit 
written permission is granted by TightPrices.

(ii) All TightPrices copyright notices within the source code, design templates, software, 
etc. must not be modified and remain visible.

(iii) The Redistributable Code is the property of TightPrices and is protected by copyright 
law and international treaty provisions.  You are not authorized to reproduce and distribute 
the Redistributable Code.  TightPrices reserves all rights not expressly granted. THE 
REDISTRIBUTABLE CODE IS PROVIDED TO YOU "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS 
OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR 
FITNESS FOR A PARTICULAR PURPOSE.  YOU ASSUME THE ENTIRE RISK AS TO THE ACCURACY AND THE USE 
OF THE REDISTRIBUTABLE CODE.  TIGHTPRICES SHALL NOT BE LIABLE FOR ANY DAMAGES WHATSOEVER 
ARISING OUT OF THE USE OF OR INABILITY TO USE THE REDISTRIBUTABLE CODE, EVEN IF TIGHTPRICES 
HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


------------------------------------------------------------------------

Overview
--------
Welcome to TightAuctions!  The system does auctions and also classifieds.  
It provides bidding, placing ads, user account managment, etc. features.  It's 
written in PHP and currently uses mySQL database for the backend.  A Windows 
program called CategoryAdmin allows ease of category administration.


DIRECTORIES
-----------

/			- TightAuctions files
/admin			- administration files
/cat_admin		- category administration (used by the CategoryAdmin program)
/MyAccount		- user account management files
/misc/docs		- Readme and license agreement.
/misc/sql		- SQL table creation file.
/misc/CategoryAdmin  	- Windows category administration program

Place all of the files and directories retaining their structure in the 
/, /admin, /cat_admin onto your web server.  E.g. place everything besides
the stuff within the /misc folder.

Setup
-----

1) Copy all of the distributed files to your server except for the /misc directory.

2) Use the "create.sql" file in the /misc/sql directory to create the mySQL tables.

     e.g. cat create.sql | mysql [database name]

3) Appropriately modify the variables in the config.php file as described within.

4) Change the return key in usersession.inc for the function getUserSessionKey() to 
   your own private key for encryption.  It can be anything you'd like, but I'd
   recommend making it obfuscated to make it hard to guess.

5) Use CategoryAdmin to setup your categories.  Note that you'll have to enable
   the /cache directory to be writeable using chmod.  See the Readme.txt file within
   the /misc/CategoryAdmin directory for more information about this program.

6) You'll need to setup a cron tab and run the AuctionEndedNotify.pl PERL script that
   will notify users via e-mail when their auction has ended if they are the seller 
   and the highest bidder.


Feedback and/or Questions
-------------------------

Questions regarding TightAuctions?  Your question may have already been
answered, or you can post a new one if it hasn't already been answered.

  http://www.tightprices.com/forums

You can also send feedback, questions, etc. to tightauctions@tightprices.com


Cheers,

TightPrices Team



