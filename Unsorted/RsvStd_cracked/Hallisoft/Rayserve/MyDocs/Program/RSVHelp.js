var message01 = "The following has been added to your booking list.";
var message02 = "Please enter";
var message03 = "is not a valid email address.";
var message04 = "Please enter the following data in the Address Details form.";
var message05 = "Do you wish to quit this application and return to the";
var message06 = "Cannot accept a card with expiry date of";
var message07 = "Cannot accept a booking before";
var message08 = "Cannot accept a booking after";
var message09 = "Arrival date is not valid.";
var message10 = "Departure date is not valid.";
var message11 = "Departure date must be later than arrival date.";
var message12 = "Maximum number of adults per room is:";
var message13 = "Maximum number of children per room is:";
var message14 = "Minimum number of occupants per room is:";
var message15 = "Maximum number of nights bookable is:";
var message16 = "Minimum number of nights bookable is:";

function dialog01() {alert("Number of rooms must be greater than zero.");}

function dialog02() {alert("Number of occupants must be greater than zero.");}

function dialog03() {alert("The booking list is already empty");}

function dialog04() {alert("Cannot continue because no booking has been made.");}

function dialog05() {alert("No bookings have beem made.\n"
+"Returning to booking form.");}

function dialog06() {alert("The list of your bookings is stored in your computers memory.\n"
+"If you press your browsers \"Reload\" or \"Refresh\" button during\n"
+"the booking procedure this may delete the booking list.\n"
+"\n"
+"Using your browsers \"Back\" or \"Forward\" buttons will not\n"
+"change the booking list.");}

function dialog07() {return (confirm("Delete this booking?"));}

function dialog08() {return (confirm("Are you sure you want to delete all bookings?"));}

function dialog09() {return (confirm("Do you want to cancel the booking procedure and delete all bookings?"));}

function dialog10() {return (confirm("Room(s) available. Would you like to make a booking?"));}

function dialog11() {alert("Enter Switch/Delta card issue number");}

function dialog12() {alert("Missing data cannot continue.\n"
+"Please ensure you have entered\n"
+"the following information:\n"
+"\n"
+"Card Type\n"
+"Card Number\n"
+"Expiry Date");}

function helpMessage01(){
alert("Using our online booking system you can:\n"
+"\n"
+"§  Check availability of rooms for the period of your proposed stay.\n"
+"§  Display a calendar showing the days on which the room you require\n"
+"    are availabile.\n"
+"§  Calculate room rates, extras, and the total cost of your stay.\n"
+"§  Book a room(s) including meals and any extra facilities that we offer.\n"
+"§  Review your booking(s) and make changes if required.\n"
+"§  Send your booking details with our reservation server.\n"
+"\n"
+"Context sensitive help is also available for each page in our online\n"
+"booking centre.\n"
+"\n"
+"Please note that reservations cannot be accepted unless supported\n"
+"by a valid credit card. You will be transferred to our secure server\n"
+"before being asked to enter your card number. All data is enctypted\n"
+"for safety."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

function helpMessage02(){
alert("Use the first form to display a calendar showing room availability:\n"
+"\n"
+"1. Select a room type.\n"
+"2. Enter the number of rooms you require.\n"
+"3. Press the \"Display Calendar\" button.\n"
+"\n"
+"Use the second form to check the availability of rooms for specific\n"
+"dates:\n"
+"\n"
+"1. Select a room type.\n"
+"2. Enter the number of rooms you require.\n"
+"3. Enter your arrival date.\n"
+"4. Enter your departure date.\n"
+"5. Press the \"Check Room Availability\" button."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

function helpMessage03(){
alert("1.   Select a room type.\n"
+"2.   Select any meals you would like included with the room.\n"
+"3.   Select any extras you would like included with the room.\n"
+"4.   Enter the number of rooms.\n"
+"5.   Enter the total number of adults occupying the room(s).\n"
+"6.   Enter the total number of children occupying the room(s).\n"
+"7.   Enter your arrival and departure dates.\n"
+"8.   List any comments/special requirements in the box provided.\n"
+"9.   Press \"Calculate\" to show the room rate per night, cost of\n"
+"      extras per night and the total cost for your stay etc.\n"
+"10. Press \"Book this Room\" to submit details of your stay to our\n"
+"      reservation server. Or press \"Add to List\" to add this room\n"
+"      requirement to your list of bookings. Or Press \"Confirm All\"\n"
+"      to submit your booking list to our reservation server.\n"
+"\n"
+"The total cost of your stay will include any meals and extras that\n"
+"you selected plus government taxes etc."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

function helpMessage04(){
alert("1. Make any changes you require to the booking details.\n"
+"2. Press the \"Calculate\" button to show the room rate per night,\n"
+"    cost of extras per night and the total cost for your stay etc.\n"
+"3. Press \"Save Changes\" to confirm the changes and return to\n"
+"    the previous page.\n"
+"\n"
+"The total cost of your stay will include any meals and extras that\n"
+"you selected plus government taxes etc.\n"
+"\n"
+"If you change the arrival date, departure date, or the number of\n"
+"rooms another check of room availability will be made."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

function helpMessage05(){
alert("Your booking details are shown in the table at the top of the page\n"
+"Use the buttons in the first column if you wish to modify or delete\n"
+"booking details.\n"
+"\n"
+"      §  The top button is used to change the booking.\n"
+"      §  The bottom button is used to delete the booking.\n"
+"\n"
+"Use the buttons at the bottom of the page to add a new booking(s)\n"
+"to the list, delete all booking details, or send booking details to our\n"
+"reservation server.\n"
+"\n"
+"The total shown at the bottom of the table includes all rooms, meals\n"
+"and extras that you selected plus government taxes etc."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

function helpMessage06(){
alert("Enter your name and address details in the form at the top of the\n"
+"page then press the \"Submit Bookings\" button to send booking\n"
+"details to our reservation server.\n"
+"\n"
+"Your booking details are shown in the table at the bottom of the\n"
+"page. Use the buttons in the first column if you wish to modify or\n"
+"delete booking details.\n"
+"\n"
+"      §  The top button is used to change the booking.\n"
+"      §  The bottom button is used to delete the booking.\n"
+"\n"
+"You can also cancel all bookings by pressing \"Cancel Bookings\"\n"
+"\n"
+"After you submit booking details you will be transferred to our\n"
+"secure server where you will be asked to enter your credit card\n"
+"number. All details are encrypted for safety."
+"\n\n\nPowered by Rayserve (c) Ray Halliwell    http://www.hallisoft.com");}

