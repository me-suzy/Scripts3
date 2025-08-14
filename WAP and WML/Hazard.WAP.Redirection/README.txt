Spitz WAP Redirection - Written by Joseph George Jacobs (joe@spitzbucket.com) Version 1.0

Based on code by Brad Derstine (brad@bizzarscripts.net)
Tesed with -- Internet Explorer 6.0 
	   -- Mozilla Firefox 1.0
	   -- Opera 7.2
	   -- Wapalizer(gelon.net)
	   -- TTemulator(tagtag.com)
	   -- Wapsilon(wapsilon.com)
	   -- ccWap Browser(ccwap.com)

====================================

HOW IT CAME ABOUT!

I requested help from Brad regarding WAP redirection. He came up with a script which he modified from 
http://www.alexking.org/blog/2003/05/04/mobile-browser-detection/ and passed to me for testing. I found out that it didn't 
work on certain WAP devices. He then modified the script to accept that WAP device(TTemulator). He also 
deviced a debugger to add other WAP devices. In the mean time, I modified the script and tested it with many other WAP 
Devices. That script is now Spitz WAP Redirection. The same script is also available at Brad's site.

====================================

HOW IT WORKS!

The redirect script detects your browser, then redirects you accordingly.

At first, the script requested the name of the WAP Device and did the necessary. With my modification, the script requested 
for the Web Browser name instead. I find it works better this way. This is because there are likely to be more WAP Devices 
than Web Browsers because Web Browsers are harder to make.

This script is neither complicated to write nor understand but it gets the job done. 

====================================

WHAT YOU NEED!

Basically, all you need is a web server with PHP installed. Your web server must also support the .wml extension. Most web 
servers do. Ask your hoster for more info.

This script was tested with RedHat(tm) Linux and Windows(tm) both with Apache as the webserver. The PHP version in use was
PHP 4.3.9

====================================

HOW TO INSTALL IT!

Just modify the $wmlredirect & $htmlredirect strings in the redirection file and upload the file to your server. Call it 
using either a web browser or a WAP device/WAP emulator.

To debug, upload debugger.php to your server, then call the file from the Web Browser to identify the name.

====================================

WHAT CAN I DO WITH THE SCRIPT?

Cell phones and PDA's are becoming popular. So to is the usage of WAP, internet for cell phones and PDA's. It is nice when 
your site is can be accessed from both protocols. It makes your site more, umm.....user friendly. This script redirects the 
most famous web browsers(Internet Explorer, Mozilla, Opera, Netscape). If you find any other web browser that it doesn't
redirect, please debug it. One you get the name of the browser, please add the name to the redirection file and also email 
the name to me so I can update the script. If the name is already there, it is a problem with the redirection. A list of 
browser names that are not in this version is available at http://www.spitzbucket.com/forum

====================================

LICENSE

This work is licensed under the Creative Commons Attribution-ShareAlike License. To view a copy of this license, visit
 
http://creativecommons.org/licenses/by-sa/2.0/ 

or send a letter to 

Creative Commons, 
559 Nathan Abbott Way, 
Stanford, 
California 94305, 
USA.

====================================

There are currently no questions associated with the script, however if you have a problem, feel free to stop by the site and 
let me know or post a question at the forum (http://www.spitzbucket.com/forum)

-- Joe