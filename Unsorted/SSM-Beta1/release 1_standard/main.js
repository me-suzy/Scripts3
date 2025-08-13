/*
Functions for the rollover buttons in  the Knowledge Base area.  
Additional styles are defined in the css files which control initial 
states.
*/
function down(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#324C69";
	whichButton.style.borderColor = "#003366 #CCDDDD #CCDDDD #003366";
}//end function

function up(src) {
	return false;
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#324C69";
}//end function

function over(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#324C69";
	whichButton.style.borderColor = "#CCDDDD #003366 #003366 #CCDDDD";
}//end function

function out(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#324C69";
	whichButton.style.borderColor = "#324C69 #324C69 #324C69 #324C69";
}//end function

function outSelected(src) {
	down(src);
}//end function