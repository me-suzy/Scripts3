######################################################################
#                        X-treme TGP v1.0
#                        Created by Relic
#                     webmaster@cyphonic.net
#		  http://www.cyphonic.net/script/
#####################################################################
X-treme TGP is a diverse cgi/c++ script which will aid you in running your tgp site.  Please note, that for the most part, installation of this script is done  by me, therefore this may or may not be relavent to you.  Anyway, I am going to try to make this is simple as possible, so that even the most clueless user, will be able to get this script up and going.

#INSTALATION#
##########################################################################
Currently Xtreme TGP will only work on unix servers, however for the creative ones, you could probably make it win2k/NT friendly with a little modification.  Follow these steps to get the script up and running.

1. Chmod all the .txt files to 666 and all the .cgi to 755

2. Open up all the cgi files in both the base directory and in the admin  directory.  You will need to edit the first line to point to your perl    interpreter.  If you don't know what this is, just leave it, it will most likely work regardless.  Edit xtreme_build.c to point to the correct directory of the script.

3. You will need to have telnet for this step as well as a c compiler on your unix box.

4. Log into telnet, and change into the base director for xtreme tgp.

5. Type in this command cpp xtreme_build.c -o xtreme_build.cgi

6. Delete the extreme_build.c file  

7. Next, open up xtreme_weekly.cgi, xtreme_submit.cgi, xtreme_out.cgi, xtreme_admin.cgi, xtreme_aprove.cgi, xtreme_ban.cgi,xtreme_delete2.cgi, xtreme_delete.cgi,xtreme_weekly2.cgi,xtreme_weekly2.cgi.  Edit all the variables listed at the top portion.

8. Open up Xtreme_update.cgi and edit the specified html, so that it looks the way you want it to.  Then edit submit.html so that it looks the way you want it too.

9. Add a .htaccess to the admin directory to keep our snoops.

10. Run xtreme_build.cgi and take it for a spin.

##########################################################################
This is the free version of the script, and therefore I am not providing technical support, however if you wish to buy the full version you can do so by emailing me at webmaster@cyphonic.net.  Good luck and enjor the script.