/* PopUp Calendar v2.1
© PCI, Inc.,2000  Freeware
webmaster@personal-connections.com
+1 (925) 955 1624
Permission granted  for unlimited use so far
as the copyright notice above remains intact. */

/*
Modified for Relata by Jeremy Rempel
May 4, 2001
*/


var ppcDF = "Y-m-d";
var ppcMN = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
var ppcWN = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
var ppcML=new Array(31,28,31,30,31,30,31,31,30,31,30,31);

var ppcER = new Array(4);
ppcER[0] = "Required DHTML functions are not supported in this browser.";
ppcER[1] = "Target form field is not assigned or not accessible.";
ppcER[2] = "Sorry, the chosen date is not acceptable. Please read instructions on the page.";
ppcER[3] = "Unknown error occured while executing this script.";

var ppcUC = false;
var ppcUX = 4;
var ppcUY = 4;

/* Do not edit below this line unless you are sure what are you doing! */

// detect the browser
var ppcIE=(navigator.appName == "Microsoft Internet Explorer");
var ppcNN=(navigator.appName == "Netscape");

var ppcTT = '<table width="200" border="1">';
ppcTT += '<tr align="center" bgcolor="#CCCCCC">';
ppcTT += '<td width="20" bgcolor="#FFFFCC"><b><font face="MS Sans Serif, sans-serif" size="1">Su</font></b></td>';
ppcTT += '<td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Mo</font></b></td>';
ppcTT += '<td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Tu</font></b></td>';
ppcTT += '<td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">We</font></b></td>';
ppcTT += '<td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Th</font></b></td>';
ppcTT += '<td width="20"><b><font face="MS Sans Serif, sans-serif" size="1">Fr</font></b></td>';
ppcTT += '<td width="20" bgcolor="#FFFFCC"><b><font face="MS Sans Serif, sans-serif" size="1">Sa</font></b></td></tr>';

var ppcCD = ppcTT;

var ppcFT = '<font face="MS Sans Serif, sans-serif" size="1" color="#000000">';

var ppcFC=true;
var ppcTI=false;
var ppcSV=null;
var ppcRL=null;
var ppcXC=null;
var ppcYC=null;

var ppcNow = new Date();
var ppcPtr = new Date();

function makecalendar(e) 
{
	var ppcRL;

/*
	// input field target
	ppcSV = document.opportunity_form.close_date;	
*/

	if (ppcFC) 
	{
		setCalendar();
		ppcFC = false;
	}

	if(ppcIE) e = event;
		
	var obj = document.getElementById('PopUpCalendar');

	obj.style.position = "absolute";
	obj.style.left = e.clientX + "px";
	obj.style.top = e.clientY + "px";
	
	obj.style.visibility = "visible";
	obj.style.display = "inline";
}

function switchMonth(param) 
{
	var tmp = param.split("|");
	setCalendar(tmp[0],tmp[1]);
}

function moveMonth(dir) 
{
	var obj = null;
	var limit = false;
	var tmp,dptrYear,dptrMonth;
 
	obj = document.ppcMonthList.sItem;
	
	if (obj != null) 
	{
		if ((dir.toLowerCase() == "back") && (obj.selectedIndex > 0)) 
		{
			obj.selectedIndex--;
		}
		else if ((dir.toLowerCase() == "forward") && (obj.selectedIndex < 12)) 
		{
			obj.selectedIndex++;
		}
		else 
		{
			limit = true;
		}
	}

	if (!limit) 
	{
 		tmp = obj.options[obj.selectedIndex].value.split("|");
  		dptrYear  = tmp[0];
  		dptrMonth = tmp[1];
  		setCalendar(dptrYear,dptrMonth);
	}
	else 
	{
  		if (ppcIE) 
		{
   			obj.style.backgroundColor = "#FF0000";
   			window.setTimeout("document.ppcMonthList.sItem.style.backgroundColor = '#FFFFFF'",50);
		}
	}
}

function selectDate(param) 
{
	var arr   = param.split("|");
	var year  = arr[0];
 	var month = arr[1];
 	var date  = arr[2];
 	var ptr = parseInt(date);
	
 	ppcPtr.setDate(ptr);
		
	if ((ppcSV != null) && (ppcSV)) 
	{
  		if (validDate(date)) 
		{
			ppcSV.value = dateFormat(year,month,date);
			hideCalendar();
		}
		else 
		{
			showError(ppcER[2]); 
			if (ppcTI) 
			{
				clearTimeout(ppcTI); 
				ppcTI = false;
			}
		}
	}
 	else 
	{
  		showError(ppcER[1]);
  		hideCalendar();
	}
}

function setCalendar(year,month)
{
 	if (year  == null) 
	{
		year = ppcNow.getFullYear();
	}
 	if (month == null) 
	{
		month = ppcNow.getMonth();
		setSelectList(year,month);
	}
 	if (month == 1) 
	{
		ppcML[1]  = (isLeap(year)) ? 29 : 28;
	}
 
 	ppcPtr.setFullYear(year);
 	ppcPtr.setMonth(month);
 	ppcPtr.setDate(1);
 	updateContent();
}

function updateContent() 
{
	generateContent();
	
	document.getElementById('monthDays').innerHTML = ppcCD;
	//document.writeln(ppcCD);
		
	ppcCD = ppcTT;
}

// create the content for the calendar
function generateContent()
{
 	var year  = getFullYear(ppcPtr);
 	var month = ppcPtr.getMonth();
	var date  = 1;
 	var day   = ppcPtr.getDay();

	var len   = ppcML[month];
 	var bgr,cnt,tmp = "";
 	var j,i = 0;
 
 	// loop through the weeks
  	for (j = 0; j < 7; ++j)
	{
		// if we are at the end of the month
  		if (date > len) 
		{
			break;
		}
  		
		for (i = 0; i < 7; ++i) 
		{
   			bgr = ((i == 0)||(i == 6)) ? "#FFFFCC" : "#FFFFFF";
   
   			if (((j == 0) && (i < day)) || (date > len)) 
			{
				tmp  += makeCell(bgr,year,month,0);
			}
   			else 
			{
				tmp  += makeCell(bgr,year,month,date);
				++date;
			}
		}
  
  		// write the row
		ppcCD += "<tr align=\"center\">\n" + tmp + "</tr>\n";tmp = "";
	}
 	ppcCD += "</table>\n";
}

function makeCell(bgr,year,month,date) 
{
 	var param = "\'"+year+"|"+month+"|"+date+"\'";
	
 	var td1 = "<td width=\"20\" bgcolor=\"" + bgr+ "\" ";
 	var td2 = "</font></a></td>\n";
	
 	var evt = "onMouseOver=\"this.style.backgroundColor=\'red\' \" onMouseOut=\"this.style.backgroundColor=\'"+bgr+"\'\" onMouseUp=\"selectDate("+param+")\" ";
 	
	var ext = "<span Style=\"cursor: hand\">";
 	var lck = "<span Style=\"cursor: default\">";
	
 	var lnk = "<a href=\"javascript:selectDate("+param+")\" onMouseOver=\"window.status=\' \';return true;\">";
 	var cellValue = (date != 0) ? date+"" : "&nbsp;";
 
 	// today
 	if ((ppcNow.getDate() == date) && (ppcNow.getMonth() == month) && (getFullYear(ppcNow) == year)) 
	{
  		cellValue = "<b>"+cellValue+"</b>";
	}
	 
 	var cellCode = "";
 
 	if (date == 0) 
	{
		cellCode = td1 + ">" + ppcFT + cellValue + td2;
	}
 	else 
	{
		if (date < 10)
		{
			cellValue = "&nbsp;" + cellValue + "&nbsp;";
		}
 
		cellCode = td1 + evt + ">" + lnk + ppcFT + cellValue + td2;
	}
 		
	return cellCode;
}

function setSelectList(year,month) 
{
	var i = 0;
	var obj = null;
 
	obj = document.ppcMonthList.sItem;

	while (i < 13) 
	{
  		obj.options[i].value = year + "|" + month;
  		obj.options[i].text  = year + "  " + ppcMN[month];
  		
		i++;
  		month++;
  		
		if (month == 12) 
		{
			year++;
			month = 0;
		}
	}
}

function hideCalendar()
{
	document.getElementById('PopUpCalendar').style.visibility = "hidden";

	ppcTI = false;
 	
	setCalendar();
	var obj = document.ppcMonthList.sItem;
 	obj.selectedIndex = 0;
}

function showError(message) 
{
	window.alert("[ PopUp Calendar ]\n\n" + message);
}

// return true if it is a leap year, false if it isnt
function isLeap(year) 
{
	if ((year % 400 == 0) || ((year % 4 == 0) && (year % 100 != 0))) 
	{
		return true;
	}
 	else 
	{
		return false;
	}
}

// return the full year
function getFullYear(obj) 
{
	return obj.getFullYear();
}

function validDate(date) 
{
	var reply = true;
	
	if (ppcRL != null) 
	{
  		var arr = ppcRL.split(":");
  		var mode = arr[0];
  		var arg  = arr[1];
  		var key  = arr[2].charAt(0).toLowerCase();
  		
		if (key != "d") 
		{
   			var day = ppcPtr.getDay();
   			var orn = isEvenOrOdd(date);
   			reply = (mode == "[^]") ? !((day == arg)&&((orn == key)||(key == "a"))) : ((day == arg)&&((orn == key)||(key == "a")));
		}
  		else 
		{
			reply = (mode == "[^]") ? (date != arg) : (date == arg);
		}
	}
 
 	return reply;
}

// returns "e" if it is a even day, and "o" if it is odd
function isEvenOrOdd(date) 
{
	if (date - 21 > 0) 
	{
		return "e";
	}
 	else if (date - 14 > 0) 
	{
		return "o";
	}
 	else if (date - 7 > 0) 
	{
		return "e";
	}
 	else 
	{
		return "o";
	}
}

// format the date to the specified format
function dateFormat(year,month,date) 
{
	if (ppcDF == null) 
	{
		ppcDF = "m/d/Y";
	}
 
 	var day = ppcPtr.getDay();
 	var crt = "";
 	var str = "";
 	var chars = ppcDF.length;
 	
	for (var i = 0; i < chars; ++i) 
	{
  		crt = ppcDF.charAt(i);
  	
		switch (crt) 
		{
		   case "M": str += ppcMN[month]; break;
		   case "m": str += (month<9) ? ("0"+(++month)) : ++month; break;
		   case "Y": str += year; break;
		   case "y": str += year.substring(2); break;
		   case "d": str += ((ppcDF.indexOf("m")!=-1)&&(date<10)) ? ("0"+date) : date; break;
		   case "W": str += ppcWN[day]; break;
		    default: str += crt;
		}
	}
	
	return unescape(str);
}