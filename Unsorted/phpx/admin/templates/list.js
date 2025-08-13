//# $Id: list.js,v 1.3 2003/09/22 14:46:43 ryan Exp $
function DynamicOptionList() {
	if (arguments.length < 2) { alert("Not enough arguments in DynamicOptionList()"); }
	// Name of the list containing dynamic values
	this.target = arguments[0];
	// Set the lists that this dynamic list depends on
	this.dependencies = new Array();
	for (var i=1; i<arguments.length; i++) {
		this.dependencies[this.dependencies.length] = arguments[i];
		}
	// The form this list belongs to
	this.form = null;
	// Place-holder for currently-selected values of dependent select lists
	this.dependentValues = new Object();
	// Hold default values to be selected for conditions
	this.defaultValues = new Object();
	// Storage for the dynamic values
	this.options = new Object();
	// Delimiter between dependent values
	this.delimiter = "|";
	// Logest string currently a potential options (for Netscape)
	this.longestString = "";
	// The total number of options that might be displayed, to build dummy options (for Netscape)
	this.numberOfOptions = 0;
	// Method mappings
	this.addOptions = DynamicOptionList_addOptions;
	this.populate = DynamicOptionList_populate;
	this.setDelimiter = DynamicOptionList_setDelimiter;
	this.setDefaultOption = DynamicOptionList_setDefaultOption;
	this.printOptions = DynamicOptionList_printOptions;
	this.init = DynamicOptionList_init;
	}

// Set the delimiter to something other than | when defining condition values
function DynamicOptionList_setDelimiter(val) {
	this.delimiter = val;
	}

// Set the default option to be selected when the list is painted
function DynamicOptionList_setDefaultOption(condition, val) {
	this.defaultValues[condition] = val;
	}

// Init call to map the form to the object and populate it
function DynamicOptionList_init(theform) {
	this.form = theform;
	this.populate();
	}

// Add options to the list.
// Pass the condition string, then the list of text/value pairs that populate the list
function DynamicOptionList_addOptions(dependentValue) {
	if (typeof this.options[dependentValue] != "object") { this.options[dependentValue] = new Array(); }
	for (var i=1; i<arguments.length; i+=2) {
		// Keep track of the longest potential string, to draw the option list
		if (arguments[i].length > this.longestString.length) {
			this.longestString = arguments[i];
			}
		this.numberOfOptions++;
		this.options[dependentValue][this.options[dependentValue].length] = arguments[i];
		this.options[dependentValue][this.options[dependentValue].length] = arguments[i+1];
		}
	}

// Print dummy options so Netscape behaves nicely
function DynamicOptionList_printOptions() {
	// Only need to write out "dummy" options for Netscape
    if ((navigator.appName == 'Netscape') && (parseInt(navigator.appVersion) <= 4)){
		var ret = "";
		for (var i=0; i<this.numberOfOptions; i++) {
			ret += "<OPTION>";
			}
		ret += "<OPTION>"
		for (var i=0; i<this.longestString.length; i++) {
			ret += "_";
			}
		document.writeln(ret);
		}
	}

// Populate the list
function DynamicOptionList_populate() {
	var theform = this.form;
	var i,j,obj,obj2;
	// Get the current value(s) of all select lists this list depends on
	this.dependentValues = new Object;
	var dependentValuesInitialized = false;
	for (i=0; i<this.dependencies.length;i++) {
		var sel = theform[this.dependencies[i]];
		var selName = sel.name;
		// If this is the first dependent list, just fill in the dependentValues
		if (!dependentValuesInitialized) {
			dependentValuesInitialized = true;
			for (j=0; j<sel.options.length; j++) {
				if (sel.options[j].selected) {
					this.dependentValues[sel.options[j].value] = true;
					}
				}
			}
		// Otherwise, add new options for every existing option
		else {
			var tmpList = new Object();
			var newList = new Object();
			for (j=0; j<sel.options.length; j++) {
				if (sel.options[j].selected) {
					tmpList[sel.options[j].value] = true;
					}
				}
			for (obj in this.dependentValues) {
				for (obj2 in tmpList) {
					newList[obj + this.delimiter + obj2] = true;
					}
				}
			this.dependentValues = newList;
			}
		}

	var targetSel = theform[this.target];

	// Store the currently-selected values of the target list to maintain them (in case of multiple select lists)
	var targetSelected = new Object();
	for (i=0; i<targetSel.options.length; i++) {
		if (targetSel.options[i].selected) {
			targetSelected[targetSel.options[i].value] = true;
			}
		}

	targetSel.options.length = 0; // Clear all target options

	for (i in this.dependentValues) {
		if (typeof this.options[i] == "object") {
			var o = this.options[i];
			for (j=0; j<o.length; j+=2) {
				var text = o[j];
				var val = o[j+1];
				targetSel.options[targetSel.options.length] = new Option(text, val, false, false);
				if (this.defaultValues[i] == val) {
					targetSelected[val] = true;
					}
				}
			}
		}
	targetSel.selectedIndex=-1;

	// Select the options that were selected before
	for (i=0; i<targetSel.options.length; i++) {
		if (targetSelected[targetSel.options[i].value] != null && targetSelected[targetSel.options[i].value]==true) {
			targetSel.options[i].selected = true;
			}
		}
	}
function submitForumForm1(){

    selectAllOptions(document.f.cat_id);
    document.f.list1.value = getSelectedValues(document.f.cat_id);
    document.f.save.value = 1;
    document.f.cat_id.value = '';
    document.f.submit();

}

function submitForumForm2(){

    selectAllOptions(document.f.forum_id);
    document.f.list1.value = getSelectedValues(document.f.forum_id);
    document.f.save.value = 2;
    document.f.forum_id.value = '';
    document.f.cat_id.value = '';
    document.f.submit();

}

function submitFaqForm1(){

    selectAllOptions(document.f.faq_cat_id);
    document.f.list1.value = getSelectedValues(document.f.faq_cat_id);
    document.f.save.value = 1;
    document.f.faq_cat_id.value = '';
    document.f.submit();

}

function submitFaqForm2(){

    selectAllOptions(document.f.faq_id);
    document.f.list1.value = getSelectedValues(document.f.faq_id);
    document.f.save.value = 2;
    document.f.faq_id.value = '';
    document.f.faq_cat_id.value = '';
    document.f.submit();

}



function submitMenuForm1(){

    selectAllOptions(document.f.cat_id);
    document.f.list1.value = getSelectedValues(document.f.cat_id);
    document.f.cat_id.value = '';
    document.f.submit();

}

function submitMenuForm2(){

    selectAllOptions(document.f.cat_id_sub);
    document.f.list1.value = getSelectedValues(document.f.cat_id_sub);
    document.f.cat_id_sub.value = '';
    document.f.submit();


}

function submitMenuForm3(){

    selectAllOptions(document.f.cat_id_sub_sub);
    document.f.list1.value = getSelectedValues(document.f.cat_id_sub_sub);
    document.f.cat_id_sub_sub.value = '';
    document.f.submit();



}

function selectAllOptions(obj) {
	for (var i=0; i<obj.options.length; i++) {
		obj.options[i].selected = true;
		}
	}

function swapOptions(obj,i,j) {
	var o = obj.options;
	var i_selected = o[i].selected;
	var j_selected = o[j].selected;
	var temp = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
	var temp2= new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);
	o[i] = temp2;
	o[j] = temp;
	o[i].selected = j_selected;
	o[j].selected = i_selected;
	}

function moveOptionUp(obj) {
	// If > 1 option selected, do nothing
	var selectedCount=0;
	for (i=0; i<obj.options.length; i++) {
		if (obj.options[i].selected) {
			selectedCount++;
			}
		}
	if (selectedCount > 1) {
		return;
		}
	// If this is the first item in the list, do nothing
	var i = obj.selectedIndex;
	if (i == 0) {
		return;
		}
	swapOptions(obj,i,i-1);
	obj.options[i-1].selected = true;
	}

function moveOptionDown(obj) {
	// If > 1 option selected, do nothing
	var selectedCount=0;
	for (i=0; i<obj.options.length; i++) {
		if (obj.options[i].selected) {
			selectedCount++;
			}
		}
	if (selectedCount > 1) {
		return;
		}
	// If this is the last item in the list, do nothing
	var i = obj.selectedIndex;
	if (i == (obj.options.length-1)) {
		return;
		}
	swapOptions(obj,i,i+1);
	obj.options[i+1].selected = true;
	}




function getSelectedValues (select) {
  var r = new Array();
  for (var i = 0; i < select.options.length; i++)
    if (select.options[i].selected)
      r[r.length] = select.options[i].value;
  return r;
}
