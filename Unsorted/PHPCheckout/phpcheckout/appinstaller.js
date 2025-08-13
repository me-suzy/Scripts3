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
			cssFileName = "appinstaller.css";
			//cssFileName = "netscape.css";
			break;
		case "Netscape":
			cssFileName = "appinstaller.css";
			break;
		case "Opera":
			cssFileName = "appinstaller.css";		
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


