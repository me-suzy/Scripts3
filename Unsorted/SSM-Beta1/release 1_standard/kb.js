/*
Functions for the rollover buttons in  the Knowledge Base area.  
Additional styles are defined in the css files which control initial 
states.
*/
function down(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#a6a6a6";
	whichButton.style.borderColor = "#5e5e5e #cacaca #cacaca #5e5e5e";
}//end function

function up(src) {
	return false;
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#a6a6a6";
}//end function

function over(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#a6a6a6";
	whichButton.style.borderColor = "#cacaca #5e5e5e #5e5e5e #cacaca";
}//end function

function out(src) {
	whichButton = eval(src);
	whichButton.style.backgroundColor = "#a6a6a6";
	whichButton.style.borderColor = "#a6a6a6 #a6a6a6 #a6a6a6 #a6a6a6";
}//end function

function outSelected(src) {
	down(src);
}//end function