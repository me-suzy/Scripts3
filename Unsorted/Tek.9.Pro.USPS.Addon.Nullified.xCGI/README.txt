If you ship your products via the U.S. Postal Service (USPS), then use this.
These two tiny .dll files will allow your website to communicate with the USPS 
servers in real-time to bring in rate information on your checkout page.

You must have a unique USPS username and password to use their service.
Your Intranet Global Editor provides 2 fields for you to enter this info once you receive it from USPS.

The ICCC is manned from 7:00AM to 11:00PM Eastern Time.
E-mail: icustomercare@usps.com
Telephone: 1-800-344-7779 (7:00AM to 11:00PM ET)

-----------------------------------------------

To install USPS on the server which is hosting your website, you must:

Step 1: Copy both .dll files into your \system32\ or \system\ sub-folder (or wherever you want them to reside)


Step 2: Click Start, Run, and type: 

	regsvr32 C:\pathToYourDLLfile\httpcom.dll

 ..OR, if you are not running Windows NT or 2000, type this:

	regsvr C:\pathToYourDLLfile\httpcom.dll


Step 3: You're done! That's all you have to do to setup USPS (US Postal Service).


