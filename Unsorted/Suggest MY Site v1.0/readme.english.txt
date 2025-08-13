################################################################################
# Suggest My Site Version 1.00  , Released: 18-07-2001
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
##############################################################################

#########################################################
#                                                       #
# This script was provided by:                          #
#                                                       #
# PHPSelect Web Development Division.                   #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are © 2004                      #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated in the script.                       #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the authors at       #
# admin@phpselect.com or info@phpselect.com             #
#                                                       #
#########################################################

Features :
------------------------
* MYSuggest is licenced with GPL.
* MySuggest is written in PHP. It allows your visitors to suggest your site to a friend.
* Checks the empty fields and prompts the user for filling them. Also checks the e-mail addresses for validity.
* A default message is sent to the person that the site is suggested. (You can modify the default message from the language files).
* Also it allows user to attach their own message about your site.
* If you set the option "on" for sending a thank you message to the user, it does so...(you can modify the thank you message from the language files).
* The language packs support Turkish,English and German. (New language packs will be added).


Requirements :
------------------------
* This script is tested on Unix and PHP 4.0.2
* Uses the sendmail function to send the e-mails.
* So You need to have PHP and sendmail installed on your server.



Setup :
------------------------
It is very easy to set up MYSuggest.
You only need to modify the file "mysuggest.php" and modify the language files as your needs


Open mysuggest.php and modify the variables below;

select which language pack you want to use :
tur.lang.php      for Turkish
eng.lang.php      for English

include("eng.lang.php");

$sitename="enter your site's name here ";
will be displayed in the "From" field of the mail

$url="enter your site address here e.g. http://www.yoursite.com";
will be displayed in the body of the mail

$adminmail="enter your e-mail address here";

$mailtosender="1";
sends a thank you message to person who suggests the site
set to "1" if you want to send a thank you mail to the person who suggests your site
else set it to "0"


You may modify the language packs as you wish.
*** You really will make me glad if you do not remove the lines where the script name, my name are are written.

*** Upload all the files to your server in ASCII mode.

That is it...
