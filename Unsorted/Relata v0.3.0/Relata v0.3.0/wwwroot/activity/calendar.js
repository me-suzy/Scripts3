var ns6=document.getElementById;
var ie4=document.all;

var Selected_Month;
var Selected_Year;
var Current_Date = new Date();
var Current_Month = Current_Date.getMonth();

var Days_in_Month = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
// put in a blank element because we want the months to start at '1' not '0'
var Month_Label = new Array('','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var day_label = new Array('Sun','Mon','Tue','Wed','Thur','Fri','Sat');

var Current_Year = Current_Date.getYear();
if (Current_Year < 1000)
	Current_Year+=1900

var Today = Current_Date.getDate();

// goto this day
function gotocalendarday(e)
{
	if(document.all) id = event.srcElement.id;
	else id = e.currentTarget.id;
	
	changerightframe(parent.www_dir + 'activity/main.php?date=' + id + '&session_id=' + session_id);
}

// print the list of months in the dropdown select menu
function print_months()
{
	if (ie4||ns6)
	{
	   for (j=1;j<Month_Label.length;j++) 
	   {
	      document.writeln('<option value=' + j + '>' + Month_Label[j]);
	   }
	}
}

// print the header at the top of the calendar, returns the header string
function Header(Year, Month) 
{
	if (Month == 1) 
   	{
   		Days_in_Month[1] = ((Year % 400 == 0) || ((Year % 4 == 0) && (Year % 100 !=0))) ? 29 : 28;
   	}
   
   	var Header_String = Month_Label[Month+1] + ' ' + Year;
   	return Header_String;
}

// create the calendar
function Make_Calendar(Year, Month) 
{
	var First_Date = new Date(Year, Month, 1);
	var heading = document.createTextNode(Header(Year, Month));
	
	var First_Day = First_Date.getDay() + 1;
	
	if (((Days_in_Month[Month] == 31) && (First_Day >= 6)) || ((Days_in_Month[Month] == 30) && (First_Day == 7))) 
	{
	   var Rows = 6;
	}
	else if ((Days_in_Month[Month] == 28) && (First_Day == 1)) {
	   var Rows = 4;
	}
	else 
	{
	   var Rows = 5;
	}

	// make the main table
	cal_table = document.createElement("TABLE");
	cal_table.id = "cal_table";
	
	cal_body = document.createElement("TBODY");
	cal_table.appendChild(cal_body);
		
	// make the header
	cal_row = document.createElement("TR");
	cal_body.appendChild(cal_row);
	
 	cal_cell = document.createElement("TH");
	if(document.all) cal_cell.colSpan = "7";
	else cal_cell.setAttribute("colspan","7");
	cal_cell.className = "calendar";
	cal_cell.appendChild(heading);
	cal_row.appendChild(cal_cell);
	
	// make the days of the week ( sun,mon,tues...)
	cal_row = document.createElement("tr");
	cal_body.appendChild(cal_row);
	
	for(i = 0; i < 7; i++)
	{
		cal_cell = document.createElement("TH");
		cal_cell.className = "calendar";
		dayofweek = document.createTextNode(day_label[i]);
		
		cal_cell.appendChild(dayofweek);
		
		cal_row.appendChild(cal_cell);
	}
	
	var Day_Counter = 1;
	var Loop_Counter = 1;
	
	// loop through however many weeks there are in the month
	for (var j = 1; j <= Rows; j++)
	{
		cal_row = document.createElement("tr");
		cal_body.appendChild(cal_row);
	     	
		// loop through every day of the week
		for (var i = 1; i < 8; i++) 
		{
			cal_cell = document.createElement("td");
			
			// if it is a valid day of the month
			if ((Loop_Counter >= First_Day) && (Day_Counter <= Days_in_Month[Month])) 
		 	{
				// id used for event listeners ... gets sent to main php page through url
				cal_cell.id = Year + "-" + (Month+1) + "-" + Day_Counter;
				addevent(cal_cell,"click",gotocalendarday,false);
			
				// make the cell and add the label
				day_num = document.createTextNode(Day_Counter);
				
				cal_cell.className = "calendar";
				
				cal_cell.appendChild(day_num);
				cal_row.appendChild(cal_cell);
			
				// if the counter is on today
				if ((Day_Counter == Today) && (Year == Current_Year) && (Month == Current_Month)) 
				{
					cal_cell.style.color = "white";
					cal_cell.style.backgroundColor = "#c0c0c0";
	           	}	
          
				Day_Counter++;
			}
			else
			{
				// not a valid day, make a blank cell
				cal_cell = document.createElement("td");
				cal_row.appendChild(cal_cell);
	 		}
			
			Loop_Counter++;
		}
	}
	
	// add the calendar to the page
	if(document.getElementById("calendar").childNodes.length != 0)
	{
		old_cal = document.getElementById("calendar").childNodes.item(0);
		document.getElementById("calendar").replaceChild(cal_table,old_cal);
	}
	else
	{
		document.getElementById("calendar").appendChild(cal_table);
	}
}

function Check_Nums() 
{
   if ((event.keyCode < 48) || (event.keyCode > 57)) 
   {
      return false;
   }
}

// change the year
function On_Year()
{
   var Year = document.when.year.value;
   if (Year.length == 4) 
   {
      Selected_Month = document.when.month.selectedIndex;
      Selected_Year = Year;
      Make_Calendar(Selected_Year, Selected_Month);
   }
}

// change the month
function On_Month() 
{
   var Year = document.when.year.value;
   if (Year.length == 4) 
   {
      Selected_Month = document.when.month.selectedIndex;
      Selected_Year = Year;
      Make_Calendar(Selected_Year, Selected_Month);
   }
   else
   {
      alert('Please enter a valid year.');
      document.when.year.focus();
   }
}

// set the calendar to the current month
function Defaults() 
{
   if (!ie4&&!ns6)
   return
   var Mid_Screen = Math.round(document.body.clientWidth / 2);
   document.when.month.selectedIndex = Current_Month;
   document.when.year.value = Current_Year;
   Selected_Month = Current_Month;
   Selected_Year = Current_Year;
   Make_Calendar(Current_Year, Current_Month);
}

// change the month forwards or backwards
function Skip(Direction) 
{
   if (Direction == '+') 
   {
      if (Selected_Month == 11) {
         Selected_Month = 0;
         Selected_Year++;
      }
      else 
	  {
         Selected_Month++;
      }
   }
   else 
   {
      if (Selected_Month == 0) 
	  {
         Selected_Month = 11;
         Selected_Year--;
      }
      else 
	  {
         Selected_Month--;
      }
   }
   Make_Calendar(Selected_Year, Selected_Month);
   document.when.month.selectedIndex = Selected_Month;
   document.when.year.value = Selected_Year;
}