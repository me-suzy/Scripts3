//<!-- This script and many more are available free online at -->
//<!-- The JavaScript Source!! http://javascript.internet.com -->
         // ID     parent ID
    AddMenu("1"  ,  "1"   ,  "<b>Products</b>"       ,  ""  ,  ""  , "showcaseIndex.php");
    AddMenu("2"  ,  "1"  ,   "Feature Product"     ,  ""  ,  ""  , "featureProduct.php");  
    AddMenu("3"  ,  "1"  ,   "Test Product #1"     ,  ""  ,  ""  , "showcase.php?productnumber=1");
		 AddMenu("4" ,  "3"   ,  "Sub A"    ,  ""  ,  ""  , "showcaseIndex.php");  
	    AddMenu("5",  "3"   ,  "Sub B"   ,  ""  ,  ""  , "showcaseIndex.php"); 
	    AddMenu("6",  "3"   ,  "Sample Site"   ,  ""  ,  ""  , "http://www.dreamriver.com"); 
    AddMenu("7" ,  "1"   ,  "Test Product #2"  ,  ""  ,  ""  , "showcaseIndex.php");
 

    AddMenu("20" ,  "20"  ,   "<b>Services</b>"    ,  ""  ,  ""  , "services.php");   
    AddMenu("21" ,  "20"  ,   "Next Item"    ,  ""  ,  ""  , "services.php");


    AddMenu("30" ,  "30" ,   "<b>Members</b>"       ,  ""  ,  ""  , "members.php");  
    AddMenu("31" ,  "30" ,   "Register"      ,  ""  ,  ""  , "register.php"); 	 
    AddMenu("32" ,  "30" ,   "Login"         ,  ""  ,  ""  , "login.php"); 
    AddMenu("33" ,  "30" ,   "Newsletter >>"      ,  ""  ,  ""  , "newsletter.php");
    AddMenu("34" ,  "33" ,   "Subscribe"      ,  ""  ,  ""  , "newsSubscribe.php?goal=Subscribe");
    AddMenu("35" ,  "33" ,   "Unsubscribe"      ,  ""  ,  ""  , "newsUnsubscribe.php?goal=Unsubscribe");
    AddMenu("36" ,  "30" ,   "Survey >>"      ,  ""  ,  ""  , "survey.php");
	    AddMenu("37" ,  "36" ,   "Take"      ,  ""  ,  ""  , "surveyTake.php");
	    AddMenu("38" ,  "36" ,   "Results"      ,  ""  ,  ""  , "surveyResults.php");
    AddMenu("39" ,  "30" ,   "Link To Us"      ,  ""  ,  ""  , "link2us.php");


    AddMenu("40" ,  "40" ,   "<b>Company</b>"    ,  ""  ,  ""  , "company.php");  	 
    AddMenu("41" ,  "40" ,   "Contact"      ,  ""  ,  ""  , "contact.php");	 
    AddMenu("42" ,  "40" ,   "Privacy Policy"      ,  ""  ,  ""  , "privacyPolicy.php");	 
    AddMenu("43" ,  "40" ,   "Shipping Policy"      ,  ""  ,  ""  , "shipping.php");
	 Build();
