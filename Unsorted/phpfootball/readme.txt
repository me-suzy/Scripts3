					PHPFootball readme file



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


Description :

PHPFootball is a footbal management software written in PHP that use a MySQL database.
It has a modular design and by default comes with two free modules.

The PHPFootball league module :
Features wizards for managing a football league : fixtures , games , teams , results , divisions .. etc
League team statistics are automatically calculated and updated every time a game is played/entered.

The PHPFootball news module :
Features a HTML news creator/editor that can be used to post news
It has a email feature acesible only by admins that cen be used to mail news to specific users


Requirements :

1- Make sure you have a working Operating System
2- Make sure you have working Apache , Php and MySQL instalations

Instalation :

1- Copy the PHPFootball dir from the zip file under the webserver root directory
2- Open thie script install.php with your browser from the scripts directory of your PHPFootball instalation
3- Use install.php to create the main administrator account , to configure and setup PHPFootball. 
4- Delete install.php from your server.
5- Open the dir where you installed PHPFootball in your browser and login with the admin account you created.


Usage :

Administrators can manage everything in the installed modules and the general configuration
Users are allowed to see all the league data that administrators enter
Registered users also have the ability to post news under their username
For more specific information see the help page link on the PHPFootball instalation

Configuration :

Personalization of the the color theme / look of the pages can be done via stylesheets
You just have to edit scripts/style.css to match your color theme

Known Problems :
cant login as cookie is sometimes not set on windows98 depending of timezone
league positions are calculated between all teams not only the teams in a league