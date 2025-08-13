function isDefaultKey(form) {
	var myForm = window.document.premiumSearchForm;
	var matchFound = false;
	if(myForm.textToFind.value) { // if the user has entered something into the textToFind box
		while(matchFound != "true" ) { // as long as there are no matches
			for (var i = 0; i < myForm.primaryField.length ;i++ ) {
				if(myForm.primaryField[i].checked) {
					matchFound = "true";
				}else{
					matchFound = "false";
				}
			}			
		}	
	} // if a value has been entered
	if(matchFound == "false") {
		alert("You entered Text To Find.\nPlease choose a company or keyword search.");
		return false;
	}
return true;
}
function isKey(form) {
	var myForm = window.document.premiumSearchForm;
	for (var i = 0;i < myForm.primaryField.length ;i++ ) {
		return i;
	}
	if(myForm.primaryField[i].checked) {
				alert("There is a checked value");
				return false;
	}
return true;
}
// browser detection - load css file
function loadCSSFile() {
  	/* this script determines the user browser and loads an appropriate external cascading style sheet.
	   If the browser is an Internet Explorer browser then an IE compatible external css file is loaded.
	   If the browser is a Netscape browser then a Netscape compatible external css file is loaded.
	   If the browser is an Opera browser then an an Internet Explorer compatible external css file is loaded.
	   If the browser is anything else no css file is loaded.
	   You can add more browser types by adding another case and a related css file. Based on an analysis 
	   of website traffic most surfers use recent browser versions. You can add specific versioning if you want
	   but the traffic that will use it will be negligible.
	   Richard Creech info@dreamriver.com http://www.dreamriver.com
	*/
	var cssFileName = "";
	var browser = navigator.appName;
	switch(browser) {
		case "Microsoft Internet Explorer":
			cssFileName = "yellow.css";
			//cssFileName = "netscape.css";
			break;
		case "Netscape":
			cssFileName = "netscape.css";
			break;
		case "Opera":
			cssFileName = "yellow.css";		
			break;	
		default:
			/* if there is no switch(browser) match then no css file is loaded */
	}
	document.write("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + cssFileName + "\">");
  }



// phpYellow javascript by Dreamriver.com
// CHECK EMAIL AND PASSWORD fields from login.php3 and admin-login.php3
// Credit: This function by dannyg@dannyg.com
// all fields are required
function checkForm(form) {
for (var i=0; i < form.elements.length; i++) {
	if (form.elements[i].value == "") {
		alert("Fill out all fields please.")
		form.elements[i].focus()
		return false
	}
}
return true
}



// VALIDATE THE STANDARD FORM used for insert and edit of listings
// Credit: This function by info@dreamriver.com
function validate(form) {

	var myForm = document.forms[0];

	// Email
	if ( myForm.yemail.value.length < 6) {
		alert("You MUST submit a valid email address.");
		myForm.yemail.focus();
		return false;
		// needs better validation
	}

	// Password
	if ( myForm.ypassword.value.length < 1) {
		alert("You MUST enter a password.");
		myForm.ypassword.focus();
		return false;
	}
	// paste below this line

	// start of Url
	if ( myForm.yurl.value.substring(0,7) != "http://" ) {
		alert("You must enter a clickable link.\nThe link must begin with 'http://'");
		myForm.yurl.focus();
		return false;
	}
	// end of Url

	// paste above this line
/* 
	HOW TO MAKE DATA REQUIRED
	To require certain data to be entered by the user:
		1. save this file as yellowORIGINAL.js
		2. open yellow.js in your html editor
		3. find the data below: example: locate the // Phone section
		4. copy the phone section from the // start of ... to the // end of ... 
		5. paste the phone section in above where it says "paste below this line"
		6. save this file yellow.js and upload to your web server
		7. test by adding a new record
		8. [OPTIONAL] color code [red] the new fields you require in the file addForm.php
*/
/*
	// start of Company
	if ( myForm.ycompany.value.length < 3) {
		alert("You MUST enter a company name.");
		myForm.ycompany.focus();
		return false;
	}
	// end of Company

	// start of Phone
	if ( myForm.yphone.value.length < 7 ) {
		alert("Your phone number must be at least 7 numbers or longer.\nEnter 7 spaces if not applicable.");
		myForm.yphone.focus()
		return false;
		// needs validation for a number value, but allowing hyphens or dots
	}
	// end of Phone
	
	// start of Fax
	if ( myForm.yfax.value.length < 7 ) {
		alert("Your fax number must be at least 7 numbers or longer.");
		myForm.yfax.focus()
		return false;
	}
	// end of Fax
	
	// start of Street Address
	if ( myForm.yaddress.value.length < 6 ) {
		alert("Your street address appears invalid.\nEnter 6 spaces if not applicable.");
		myForm.yaddress.focus()
		return false;
	}
	// end of Street Address

	// start of City
	if ( myForm.ycity.value.length < 3 ) {
		alert("The city appears invalid.\nEnter 3 spaces if not applicable.");
		myForm.ycity.focus()
		return false;
	}
	// end of City

	// start of ZIP or Postal code
	if ( myForm.ypostalcode.value.length < 5 ) {
		alert("Your ZIP or postal code must contain at least 5 characters.\nEnter 5 spaces if not applicable.");
		myForm.ypostalcode.focus()
		return false;
	}
	// end of ZIP or Postal Code

	// start of Country
	var myCountryList = myForm.ycountry;
	var mySelectedCountryIndex= myCountryList.selectedIndex;
	countryItem = myCountryList.options[mySelectedCountryIndex].value;
	if(countryItem == "*") {
		alert("Choose a country.");
		myCountryList.focus();
		return false;
	}
	if(countryItem == "") {
		alert("Choose a country.");
		myCountryList.focus();
		return false;
	}
	// end of Country

	// start of Url
	if ( myForm.yurl.value.substring(0,7) != "http://" ) {
		alert("You must enter a clickable link.\nThe link must begin with 'http://'");
		myForm.yurl.focus();
		return false;
	}
	// end of Url
*/
	return true
}



function checkCategoryForm(form) {
var myForm = document.forms[0];
var goal = "<?php echo $goal;?>";
if (goal == "Add") {

// Category
	var mySelectList = myForm.category;
	var mySelectedIndex= mySelectList.selectedIndex;
	chosenItem = mySelectList.options[mySelectedIndex].value;
	if(chosenItem == "*") {
		alert("Choose a category.");
		mySelectList.focus();
		return false;
	}
	if(chosenItem == "") {
		alert("Choose a category.");
		mySelectList.focus();
		return false;
	}
} // if (goal == "Add"

// Description
if ( myForm.description.value.length <= 9 ) {
	alert("Make your description 10 characters or longer.");
	myForm.description.focus();
	return false;
	}
}