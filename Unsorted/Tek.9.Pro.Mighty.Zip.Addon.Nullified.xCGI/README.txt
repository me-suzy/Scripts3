If you host your site with LINE9, you can enable the MightyZip object in your Intranet Global Editor, which will then automatically fill in the city/state for your visitors on your checkout page. Why ask your visitors for all that extra info, if your site can just look it up for them? :)

Note: Hosting with LINE9 is not required, but we subscribe to the USPS zip lookup engine.
If you are hosting your own site, and have a subscription to the USPS zip lookup, then go ahead and 
install this little .dll so that your server can communicate with the USPS servers..


Step 1: Click Start, Run, and type: 

  regsvr32 C:\pathToYourDLLfile\MightyZip.dll

or, if you are not running Windows NT or 2000, type this:

  regsvr C:\pathToYourDLLfile\MightyZip.dll


You're done! That's all you have to do to setup MightyZip on your server.


 