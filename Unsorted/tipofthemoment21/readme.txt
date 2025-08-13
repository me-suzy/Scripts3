#######################################################
#             Tip of the Moment V2.1	
#
# This program is distributed as freeware. We are not            	
# responsible for any damages that the program causes	
# to your system. It may be used and modified free of 
# charge, as long as the copyright notice
# in the program that give me credit remain intact.
# If you find any bugs in this program. It would be thankful
# if you can report it to us at cgifactory@cgi-factory.com.  
# However, that email address above is only for bugs reporting. 
# We will not  respond to the messages that are sent to that 
# address. If you have any trouble installing this program. 
# Please feel free to post a message on our CGI Support Forum.
# Selling this script is absolutely forbidden and illegal.
##################################################################
#
#               COPYRIGHT NOTICE:
#
#         Copyright 1999 The AHC CGI Factory 
#
#      Author:  Yutung Liu
#      web site: http://www.cgi-factory.com
#      E-Mail: cgifactory@cgi-factory.com
#
#   This program is protected by the U.S. and International Copyright Law
#
###################################################################


Installation:

1) Open the configuration script(cfg.pl) with a text editor.

Edit the variables in the script(cfg.pl).

(If the path to perl on your server is different from 
#!/usr/local/bin/perl then you will need to change it on the first line) 

2) Upload the scripts to your cgi-bin then chmod them to "755"

3) Create the directory, that you've set the path in cfg.pl. Chmod the directory to "777". This directory will 
be used for stroing the message data files. Usually, you don't want to create the directory inside the cgi-bin, because some 
cgi-bin do't allow you to read the files from a browser.

4) Run the admin script (tip-admin.pl). The script will ask you to set up your admin password and the first message for first time running this script.

5) Insert the following tag into your page. <!--#exec cgi="path to tip.pl" -->
example: <!--#exec cgi="/cgi-bin/tip.pl" -->

6) Done.

Important!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
"All of the files have to be uploaded in "ASCII" mode 
or you will always get a 500 server error while accessing the program.