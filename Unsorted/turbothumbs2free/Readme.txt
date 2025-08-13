REMEMBER to upload ALL files in BINARY mode.
Open settings.php and enter the correct settings.

$dbname ="DATEBASE_NAME";
Enter the name for the database your wish to use

$dbuser ="DATABASE_USERNAME";
Enter the username for the database your wish to use

$dbpass ="DATABASE_PASSWORD";
Enter the password for the database your wish to use

$dbhost ="localhost";
The hostname for the database. Usually localhost. If not ask your server admin / host

$path ="/FULL_PATH_TO_YOUR_ADMIN_FOLDER/";
path is the FULL path to your admin folder. Chmod 777

$import ="/FULL_PATH_TO_YOUR_ADMIN_FOLDER/import/";
import is the path to the folder that the importer use to store the temp files while bulk 
importing the galleries. Put it in /admin/import/ but write the full path. Chmod 777

$files ="/FULL_PATH_TO_YOUR_ADMIN_FOLDER/files/";
files is where your mainpages is stored. Put that folder in your admin folder as well. Chmod 777

$dest ="/FULL_PATH_TO_WHERE_YOU_WANT_THE_SCRIPT_TO_PUT_THE_THUMBNAILS/";
the full server path to where the script should create and save thumbnails. Chmod 777

$thumbnaills ="http://www.YOURDOMAIN.com/thumbs/";
The URL to where the script created your thumbnails.

$convert_path = 'convert';
The full path to convert. 
This is needed if you want turbothumbs to make thumbnails

$wget ="/usr/bin/wget";
The path to wget on your server.  
This is needed if you want turbothumbs to make thumbnails or download thumbnails

$yourdomain="YOUR_DOMAIN.com";
Your domain name. turbothumbs need that if you use the "block fake traffic feature"


remember to password protect your admin folder.
Read the documents about link codes, templates and cron jobs in the admin area.
For support issues please use the board here http://www.mdue.com/bbs/
If you upgrade to the full version (paid version) you get full personal support
This free version take ~3% of your traffic
The paid version cost $199 or you can get it for free with the hosted option.
after you have set all the correct settings run setup.php in your admin folder.
please report bugs to info@mdue.com / ICQ 7694991.
