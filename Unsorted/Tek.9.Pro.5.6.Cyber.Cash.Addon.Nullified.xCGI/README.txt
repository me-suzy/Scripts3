If you will use the Cybercash service to process credit cards for your site, then you need to 
register this one .dll file. That file allows your webserver to talk in real-time with the 
Cybercash servers, and process the credit cards from your website. Without this .dll, (required 
from Cybercash) your server will have no idea how to send data from your server to theirs.


Step 1:
The \db\merchant_conf file is the file which you must enter all of your cybercash account info into.


Step 2:
Make sure you enter the proper path to your merchant_conf file in your Intranet Global Editor.


Step 3: Click Start, Run, and type: 

  regsvr32 C:\pathToYourDLLfile\cychmck.dll

or, if you are not running Windows NT or 2000, type this:

  regsvr C:\pathToYourDLLfile\cychmck.dll



You're done!

