//UDMv3.4.1
//**DO NOT EDIT THIS *****
if (!exclude) { //********
//************************



///////////////////////////////////////////////////////////////////////////
//
//  ULTIMATE DROPDOWN MENU VERSION 3.5 by Brothercake
//  http://www.brothercake.com/dropdown/ 
//
//  Link-wrapping routine by Brendan Armstrong
//  KDE modifications by David Joham
//  Opera reload/resize routine by Michael Wallner
//  http://www.wallner-software.com/
//
//  This script featured on Dynamic Drive (http://www.dynamicdrive.com)
///////////////////////////////////////////////////////////////////////////



// *** POSITIONING AND STYLES *********************************************



var menuALIGN = "left";		// alignment
var absLEFT = 	0;		// absolute left or right position (if menu is left or right aligned)
var absTOP = 	0; 		// absolute top position

var staticMENU = false;		// static positioning mode (ie5,ie6 and ns4 only)

var stretchMENU = true;		// show empty cells
var showBORDERS = true;		// show empty cell borders

var baseHREF = "";	// base path to .js files for the script (ie: resources/)
var zORDER = 	1000;		// base z-order of nav structure (not ns4)

var mCOLOR = 	"lightblue";	// main nav cell color
var rCOLOR = 	"lightgreen";	// main nav cell rollover color
var bSIZE = 	1;		// main nav border size
var bCOLOR = 	"black"	// main nav border color
var aLINK = 	"brown";	// main nav link color
var aHOVER = 	"";		// main nav link hover-color (dual purpose)
var aDEC = 	"none";		// main nav link decoration
var fFONT = 	"arial";	// main nav font face
var fSIZE = 	13;		// main nav font size (pixels)
var fWEIGHT = 	"bold"		// main nav font weight
var tINDENT = 	7;		// main nav text indent (if text is left or right aligned)
var vPADDING = 	7;		// main nav vertical cell padding
var vtOFFSET = 	0;		// main nav vertical text offset (+/- pixels from middle)

var keepLIT =	true;		// keep rollover color when browsing menu
var vOFFSET = 	5;		// shift the submenus vertically
var hOFFSET = 	4;		// shift the submenus horizontally

var smCOLOR = 	"lightblue";	// submenu cell color

var srCOLOR = 	"lightgreen";	// submenu cell rollover color
var sbSIZE = 	1;		// submenu border size
var sbCOLOR = 	"black"	// submenu border color
var saLINK = 	"black";	// submenu link color
var saHOVER = 	"";		// submenu link hover-color (dual purpose)
var saDEC = 	"none";		// submenu link decoration
var sfFONT = 	"comic sans ms,arial";// submenu font face
var sfSIZE = 	13;		// submenu font size (pixels)
var sfWEIGHT = 	"normal"	// submenu font weight
var stINDENT = 	5;		// submenu text indent (if text is left or right aligned)
var svPADDING = 1;		// submenu vertical cell padding
var svtOFFSET = 0;		// submenu vertical text offset (+/- pixels from middle)

var shSIZE =	2;		// submenu drop shadow size
var shCOLOR =	"cccccc";	// submenu drop shadow color
var shOPACITY = 75;		// submenu drop shadow opacity (not ie4,ns4 or opera)

var keepSubLIT = true;		// keep submenu rollover color when browsing child menu
var chvOFFSET = -12;		// shift the child menus vertically
var chhOFFSET = 7;		// shift the child menus horizontally

var closeTIMER = 330;		// menu closing delay time

var cellCLICK = true;		// links activate on TD click
var aCURSOR = "hand";		// cursor for active links (not ns4 or opera)

var altDISPLAY = "";		// where to display alt text
var allowRESIZE = true;		// allow resize/reload

var redGRID = false;		// show a red grid
var gridWIDTH = 0;		// override grid width
var gridHEIGHT = 0;		// override grid height
var documentWIDTH = 0;		// override document width

var hideSELECT = true;		// auto-hide select boxes when menus open (ie only)
var allowForSCALING = false;	// allow for text scaling in mozilla 5


//** LINKS ***********************************************************




// add main link item ("url","Link name",width,"text-alignment","_target","alt text",top position,left position,"key trigger")
addMainItem("http://www.dynamicdrive.com/","Hom<span class='u'>e</span>",80,"center","","",0,0,"e");

	// define submenu properties (width,"align to edge","text-alignment",v offset,h offset,"filter")
	defineSubmenuProperties(180,"left","left",-4,0,"");

	// add submenu link items ("url","Link name","_target","alt text")
	addSubmenuItem("http://www.dynamicdrive.com/new.htm","What\'s New","","");
	addSubmenuItem("http://www.dynamicdrive.com/hot.htm","What\'s Hot","","");
	addSubmenuItem("http://www.dynamicdrive.com/faqs.htm","FAQ","","");
	addSubmenuItem("http://www.dynamicdrive.com/submitscript.htm","Submit","","");
	addSubmenuItem("http://www.dynamicdrive.com/morezone/","More Zone","","");


addMainItem("","<span class='u'>W</span>ebmaster",100,"center","","",0,0,"w");

	defineSubmenuProperties(137,"right","right",-4,0,"");

	addSubmenuItem("http://www.dynamicdrive.com","Dynamic Drive","_blank","");
	addSubmenuItem("http://www.javascriptkit.com","JavaScript Kit","","");
	addSubmenuItem("http://www.freewarejava.com","Freewarejava","_blank","");
	addSubmenuItem("http://freewarejava.com/cgi-bin/Ultimate.cgi","JK Help Forum","_blank","");
	addSubmenuItem("http://active-x.com/","Active-X.com","_blank","");


addMainItem("","New<span class='u'>s</span>",65,"center","","",0,0,"s");

	defineSubmenuProperties(120,"left","center",-4,0,"");

	addSubmenuItem("http://www.cnn.com/","CNN","","");
	addSubmenuItem("http://www.msnbc.com","MSNBC","","");
	addSubmenuItem("http://news.bbc.co.uk","BBC","","");
	addSubmenuItem("","Local News >>","","");

		// define child menu properties (width,"align to edge","text-alignment",v offset,h offset,"filter")
		defineChildmenuProperties(142,"left","left",0,-20,"");

		// add child menu link items ("url","Link name","_target","alt text")
		addChildmenuItem("http://www.vancouversun.com","Vancouver Sun","","");
		addChildmenuItem("http://www.ctvnews.ca","CTV News","","");


addMainItem("","<span class='u'>T</span>echnology",120,"center","","",0,0,"t");

	defineSubmenuProperties(120,"left","center",-4,0,"");


	addSubmenuItem("http://www.space.com/","Space.com","","");
	addSubmenuItem("http://www.slashdot.org","Slashdot","","");
	addSubmenuItem("http://www.wired.com","Wired News","","");
	addSubmenuItem("http://www.techweb.com/","TechWeb","","");
	addSubmenuItem("http://www.brothercake.com/","BrotherCake","","");
	addSubmenuItem("http://www.cnet.com","Cnet","","");
	addSubmenuItem("javascript:openWindow('http://www.salon.com',640,400)","Salon","","");


addMainItem("","Ente<span class='u'>r</span>tainment",140,"center","","",0,0,"r");

	defineSubmenuProperties(120,"left","center",-4,0,"");

	addSubmenuItem("http://www.rottentomatoes.com","Rotten Tomatoes","_blank","");
	addSubmenuItem("http://www.etonline.com","ETOnline","","");
	addSubmenuItem("http://www.hollywood.com/","Hollywood","","");
	addSubmenuItem("","TV Networks >>","","");

		// define child menu properties (width,"align to edge","text-alignment",v offset,h offset,"filter")
		defineChildmenuProperties(142,"left","left",0,-20,"filter:progid:DXImageTransform.Microsoft.Wheel(duration=0.3,spokes=20)");

		// add child menu link items ("url","Link name","_target","alt text")
		addChildmenuItem("http://www.nbc.com","NBC","","");
		addChildmenuItem("http://www.cbs.com","CBS","","");
		addChildmenuItem("http://www.abc.com","ABC","","");
		addChildmenuItem("http://www.fox.com","Fox Network","","");



//**DO NOT EDIT THIS *****
}//***********************
//************************
