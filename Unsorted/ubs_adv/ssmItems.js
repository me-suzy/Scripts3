<!--

/*
Configure menu styles below
NOTE: To edit the link colors, go to the STYLE tags and edit the ssm2Items colors
*/
YOffset=150; // no quotes!!
XOffset=0;
staticYOffset=30; // no quotes!!
slideSpeed=20 // no quotes!!
waitTime=100; // no quotes!! this sets the time the menu stays out for after the mouse goes off it.
menuBGColor="black";
menuIsStatic="yes"; //this sets whether menu should stay static on the screen
menuWidth=150; // Must be a multiple of 10! no quotes!!
menuCols=2;
hdrFontFamily="verdana";
hdrFontSize="2";
hdrFontColor="white";
hdrBGColor="#170088";
hdrAlign="left";
hdrVAlign="center";
hdrHeight="15";
linkFontFamily="Verdana";
linkFontSize="2";
linkBGColor="white";
linkOverBGColor="#FFFF99";
linkTarget="_top";
linkAlign="Left";
barBGColor="#444444";
barFontFamily="Verdana";
barFontSize="2";
barFontColor="white";
barVAlign="center";
barWidth=20; // no quotes!!
barText="RPG Battle menu"; // <IMG> tag supported. Put exact html for an image to show.

///////////////////////////

// ssmItems[...]=[name, link, target, colspan, endrow?] - leave 'link' and 'target' blank to make a header
ssmItems[0]=["Main"] //create header
ssmItems[1]=["log in", "misc.php?action=login", ""]
ssmItems[2]=["log out", "action.php?action=logout", ""]
ssmItems[3]=["Control Panel", "cp-index.php", ""]
ssmItems[4]=["Inventory", "cp-inventory.php", ""]
ssmItems[5]=["Shop", "shop-main.php", ""]
ssmItems[6]=["Members", "members.php?mode=view&by=username&order=DESC", ""]
ssmItems[7]=["Bank", "bank.php", ""]
ssmItems[8]=["Faq", "faq.php", ""]
ssmItems[9]=["Home", "index.php", ""]
ssmItems[10]=["Register", "misc.php?action=register", ""]
ssmItems[11]=["Battle", "battle.php", ""]

ssmItems[12]=["Buy the pro version now!", "", ""] //create header
ssmItems[13]=["UB systems HQ", "http://www.gaming-honor.com/hq", ""]

buildMenu();

//-->