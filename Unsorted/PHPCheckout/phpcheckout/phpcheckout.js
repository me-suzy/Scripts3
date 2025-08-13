// browser detection : load css file
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
			cssFileName = "themeMuted.css"; // use any of phpcheckout.css, themeNetscape.css, themeGray.css, themeBright.css, themeNetscape.css or make your own custom .css file
			break;
		case "Netscape":
			cssFileName = "themeNetscape.css";
			break;
		case "Opera":
			cssFileName = "phpcheckout.css";		
			break;	
		default:
			/* if there is no switch(browser) match then no css file is loaded */
	}
	document.write("<link rel=stylesheet type=\"text/css\" href=\"" + cssFileName + "\">");
}




// CHECK empty fields - all are required
// Credit: This function by dannyg@dannyg.com
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
function validateTheCheckoutForm(form) {

var myForm = window.document.checkoutForm;

// Email
if ( myForm.email.value.length < 6) {
	alert("Your email address must be valid.");
	myForm.email.focus();
	return false;
	// better validation is enforced in the php parsing after submit
	}

// password
if ( myForm.password.value.length < 4) {
	alert("Your password must be at least 4 characters.");
	myForm.password.focus();
	return false;
	}

// firstname
if ( myForm.firstname.value.length < 2) {
	alert("Enter your first name.");
	myForm.firstname.focus();
	return false;
	}

// lastname
if ( myForm.lastname.value.length < 2) {
	alert("Enter your last name.");
	myForm.lastname.focus();
	return false;
	}

/*
// Organization 
if ( myForm.organization.value.length <= 2 ) {
	alert("Enter a complete organization name.\n");
	myForm.organization.focus();
	return false;
	}
*/

// address
if ( myForm.address.value.length < 6) {
	alert("Enter your address.");
	myForm.address.focus();
	return false;
	}

// city
if ( myForm.city.value.length < 3) {
	alert("Enter your city.");
	myForm.city.focus();
	return false;
	}

// stateprov
if ( myForm.stateprov.value.length < 2) {
	alert("Enter your state or province.\nEnter 'None' if not applicable.");
	myForm.stateprov.focus();
	return false;
	}

// Country
var myCountryList = myForm.country;
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

// postal code
if ( myForm.postalcode.value.length < 5) {
	alert("Enter your zip or postal code.\nEnter five (5) blank spaces if not applicable.");
	myForm.postalcode.focus();
	return false;
	}

/*
// Url
if ( myForm.website.value.substring(0,7) != "http://" ) {
	alert("You must enter a website which will be used for your software license. \nUse a domain name or an IP number. \nYour entry must begin with 'http://' \nExample: http://www.domain.com");
	myForm.website.focus();
	return false;
	}
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