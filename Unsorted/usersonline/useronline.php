<? 
###########################################################################################
#  Project           : phpUseronline                                                      #
#  File name         : useronline.php                                                     #
#  Version           : 1.10                                                               #
#  Last Modified By  : Erich Fuchs                                                        #
#  Email             : erich.fuchs@netone.at                                              #
#  Purpose           : Main File                                                          #
#  Last Modified     : 17 Nov 2003 by eBuilders.ws (slight printing change)               #
#  Copyright         : (c) 2001 by NETonE High Quality Networking (http://www.netone.at)  #
#  Warning           : DO NOT REMOVE THIS HEADER OR THE CONTENT THEREIN!!!                #
###########################################################################################
#  Configuration Info                                                                     #
###########################################################################################                                                                                                           

$server         	= "localhost";  		// Your MySQL Server (usually "localhost")                 
$db_user        	= username_phpuser"; 		// Your MySQL Username                                        
$db_pass        	= "password";			// Your MySQL Password                                        
$database       	= "username_phpUseronline";	// Database Name                                              

$timeoutseconds 	= 300;			// Timeout Value in Seconds

###########################################################################################
#  End Configuration - DO NOT EDIT BEHIND THIS LINE!!!                                    #
###########################################################################################                                                                                                          

$timestamp=time();                                                                                            
$timeout=$timestamp-$timeoutseconds;  
mysql_connect($server, $db_user, $db_pass) or die ("Useronline Database CONNECT Error");                                                                   
mysql_db_query($database, "INSERT INTO useronline VALUES ('$timestamp','$REMOTE_ADDR','$PHP_SELF')") or die("Useronline Database INSERT Error"); 
mysql_db_query($database, "DELETE FROM useronline WHERE timestamp<$timeout") or die("Useronline Database DELETE Error");
$result=mysql_db_query($database, "SELECT DISTINCT ip FROM useronline WHERE file='$PHP_SELF'") or die("Useronline Database SELECT Error");
$user  =mysql_num_rows($result);                                                                              
mysql_close();                                                                                                
if ($user==1) {echo"<font size=1>There is curently $user Person online.</font>";} else {echo"<font size=1>There are currently $user people online.";}
?>