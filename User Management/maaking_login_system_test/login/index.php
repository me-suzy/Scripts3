<?PHP
###########################################
#-----------Users login system------------#
###########################################
/*=========================================\
Author      :  Mohammed Ahmed(M@@king)    \\
Version     :  1.0                        \\
Date Created:  Aug 20  2005               \\
----------------------------              \\
Last Update:   August 22 2005             \\
----------------------------              \\
Country    :   Palestine                  \\
City       :   Gaza                       \\
E-mail     :   m@maaking.com              \\
MSN        :   m@maaking.com              \\
AOL-IM     :   maa2pal                    \\
WWW        :   http://www.maaking.com     \\
Mobile/SMS :   00972-599-622235           \\
                                          \\
===========================================\
------------------------------------------*/

include ("config.php");

//if the user is not logged in, then redirect to login page.
if (!is_logged_in($user)) {
     header("Location: users.php");  die();
}else{
      include ("header.php");
      //put your code here (protected page).
      echo "Welcome to inex page.";



      include ("footer.php");
}



?>
