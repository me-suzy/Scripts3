
help_text = "Easily format your messages with these controls";

b_text = "Insert bold text";
i_text = "Insert italic text";
u_text = "Insert underlinded text";
quote_text = "Add a quote by someone";

size_text = "Alter the size of the text";
color_text = "Alter the color of the text";
font_text = "Alter the font of the text";
code_text = "Add a highlightted code";

url_text = "Add a web address";
email_text = "Add an email address";
img_text = "Add an image";
list_text = "Add a bulleted list";

norm_text = "Switch to normal code-editing mode";
enha_text = "Switch to enhanced mode with complex nesting of tags";

closecurrent_text = "Close the current qhtml tag";
closeall_text = "Close all open qhtml tags";

enhanced_only_text = "Error: This is only available in enhanced mode";
no_tags_text = "Error: No open qhtml tags detected";
already_open_text = "Error: You already have tag open of this type";

tag_prompt = "Please enter the text you wish to format in your comment";
img_prompt = "Please enter the full address of the image you wish to add in your comment";

link_text_prompt = "Enter the text you wish to appear on the page in place of the link (optional)";
link_url_prompt = "Enter the full URL for the link";
link_email_prompt = "Enter the email address for the link";

list_prompt = "Please enter a list item\nPress cancel or leave blank to finish the list";

tag_array = new Array();

function getarraysize(thearray) {

  for (i=0;i<thearray.length;i++) {
    if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null)) {
      return i;
    }
  }

  return thearray.length;
}

function arraypush(thearray,value) {

  thearraysize = getarraysize(thearray);
  thearray[thearraysize] = value;

}

function arraypop(thearray) {

  thearraysize = getarraysize(thearray);
  return_val = thearray[thearraysize - 1];
  delete thearray[thearraysize - 1];
  return return_val;

}

function updatehelp(theform,thevalue) {
  thefield = theform.help;

  thefield.value = eval(thevalue+"_text");

  // Reset the help text
  setTimeout("thefield.value=''",5000);
  setTimeout("eval('thefield.value=\"'+help_text+'\"')",10000);
}

function closecurrent(theform) {

  if (getmode(theform)) {
    if (tag_array[0]) {
      theform.content.value += "[/"+arraypop(tag_array)+"] ";
    } else {
      updatehelp(theform,"no_tags");
    }
  } else {
    updatehelp(theform,"enhanced_only");
  }

  theform.content.focus();
}

function closeall(theform) {

  if (getmode(theform)) {
    if (tag_array[0]) {
      while(tag_array[0]) {
        theform.content.value += "[/"+arraypop(tag_array)+"] ";
      }
    } else {
      updatehelp(theform,"no_tags");
    }
  } else {
    updatehelp(theform,"enhanced_only");
  }

  theform.content.focus();
}

function setmode(modevalue,theform) {
  if ((modevalue == 0) && tag_array[0]) {
    closeall(theform);
  }
  theform.mode[modevalue].checked = 1;
  document.cookie = "qhtmlcodemode="+modevalue+"; path=/; expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function getmode(theform) {
  if (theform.mode[1].checked) {
    return 1;
  } else {
    return 0;
  }
}

function inserttag(theform,code) {

  if ((code == "sql") || (code == "html") || (code == "php") || (code == "code")) {
    theform.code.selectedIndex = 0;
  }

  if (getmode(theform) && (code != "img")) {

    tag_open = false;

    for (i=0;i<tag_array.length;i++) {
      if (tag_array[i] == code) {
        tag_open = true;;
      }
    }

    if (tag_open) {
      updatehelp(theform,"already_open");
    } else {
      theform.content.value += "["+code+"]";
      arraypush(tag_array,code);
    }

    theform.content.focus();

  } else {

    if (code == "img") {
      inserttext = prompt(img_prompt,"http://");
    } else {
      inserttext = prompt(tag_prompt,"");
    }

    if ((inserttext != null) && (inserttext != "")) {
      theform.content.value += "["+code+"]"+inserttext+"[/"+code+"]";
      theform.content.focus();
    }
  }
}

function fontformat(theform,tag,option) {

  if (getmode(theform)) {

    tag_open = false;

    for (i=0;i<tag_array.length;i++) {
      if (tag_array[i] == tag) {
        tag_open = true;;
      }
    }

    if (tag_open) {
      updatehelp(theform,"already_open");
    } else {
      theform.content.value += "["+tag+"="+option+"]";
      arraypush(tag_array,tag);
    }

    theform.content.focus();

  } else {
    inserttext = prompt(tag_prompt,"");

    if ((inserttext != null) && (inserttext != "")) {
      theform.content.value += "["+tag+"="+option+"]"+inserttext+"[/"+tag+"]";
      theform.content.focus();
    }
  }
  theform.size.selectedIndex = 0;
  theform.font.selectedIndex = 0;
  theform.color.selectedIndex = 0;
}

function insertlink(theform,type) {
  linktext = prompt(link_text_prompt,"");

  if (type == "url") {
    prompt_text = link_url_prompt;
    prompt_contents = "http://";
  } else {
    prompt_text = link_email_prompt;
    prompt_contents = "";
  }

  linkurl = prompt(prompt_text,prompt_contents);

  if ((linkurl != null) && (linkurl != "")) {
    if ((linktext != null) && (linktext != "")) {
      theform.content.value += "["+type+"="+linkurl+"]"+linktext+"[/"+type+"] ";
    } else {
      theform.content.value += "["+type+"]"+linkurl+"[/"+type+"] ";
    }
    theform.content.focus();
  }
}

function addsmilie (smilie) {
  document.c_form.content.value += smilie + " ";
  document.c_form.content.focus();
}

function dolist(theform) {

  thelist = "[list]\n";
  listend = "[/list] ";

  listentry = "initial";
  while ((listentry != "") && (listentry != null)) {

    listentry = prompt(list_prompt,"");

    if ((listentry != "") && (listentry != null)) {
      thelist = thelist+"[*]"+listentry+"[/*]\n";
    }
  }

  if (thelist != "[list]\n") {
    theform.content.value += thelist+listend;
    theform.content.focus();
  }
}

var clickcount = 0;

function validateform(theform,maxlength) {

  clickcount ++;

  if (clickcount > 1) {
    alert("Please be patient, your comment has been submitted");
    return false;
  } else if (theform.content.value == "") {
    alert("You must enter a comment");
    clickcount = 0
    return false;
  } else if ((theform.content.value.length > maxlength) && (maxlength > 0)) {
    alert("Your comment is too long\n\nYour comment is "+theform.content.value.length+" characters long.\n The maximum permitted length is "+maxlength+".");
    clickcount = 0
    return false;
  } else {
    return true;
  }
}
