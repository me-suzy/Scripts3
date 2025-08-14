
function inserttag(theform,code) {
  if (code == "img") {
    inserttext = prompt("Please enter the full address of the image you wish to add in your comment","http://");
  } else {
    inserttext = prompt("Please enter the text you wish to format in your comment\n["+code+"]xxx[/"+code+"]","");
  }

  if ((inserttext != null) && (inserttext != "")) {
    theform.content.value += "["+code+"]"+inserttext+"[/"+code+"]";
    theform.content.focus();
  }
}

function insertlink(theform,type) {
  linktext = prompt("Enter the text you wish to appear on the page in place of the link (optional)","");

  if (type == "url") {
    prompt_text = "Please enter the full address of the link";
    prompt_contents = "http://";
  } else {
    prompt_text = "Please enter the email address";
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

function dolist(theform) {

  thelist = "[list]\n";
  listend = "[/list] ";

  listentry = "initial";
  while ((listentry != "") && (listentry != null)) {

    listentry = prompt("Please enter a list item\nPress cancel or leave blank to finish the list","");

    if ((listentry != "") && (listentry != null)) {
      thelist = thelist+"[*]"+listentry+"[/*]\n";
    }
  }

  if (thelist != "[list]\n") {
    theform.content.value += thelist+listend;
    theform.content.focus();
  }
}

