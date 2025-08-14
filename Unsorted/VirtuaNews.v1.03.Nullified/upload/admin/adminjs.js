
function updatetext(text) {
  if (document.form.insertinto[1].checked) {
    document.form.extendednews.value += text
    document.form.extendednews.focus()
  } else {
    document.form.mainnews.value += text
    document.form.mainnews.focus()
  }
}

function addicon(icon,text) {
  var linktext = prompt("Please enter the text you wish to appear on the page along with the icon","")

  if (linktext != null) {
  linkurl = prompt("Please enter the url of the link you wish to use","http://")
    if (linkurl != null) {
      updatetext("[img]images/news/icons/"+icon+"[/img] "+text+": [url="+linkurl+"]"+linktext+"[/url]")
    }
  }
}

function inserttag(tag) {
  if (tag == "img") {
    inserttext = prompt("Please enter the full address of the image you wish to add in your comment","http://");
  } else {
    inserttext = prompt("Please enter the text you wish to format in your comment\n["+tag+"]xxx[/"+tag+"]","");
  }

  if ((inserttext != null) && (inserttext != "")) {
    updatetext("["+tag+"]"+inserttext+"[/"+tag+"]");
  }
}

function insertlink(type) {
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
      updatetext("["+type+"="+linkurl+"]"+linktext+"[/"+type+"] ");
    } else {
      updatetext("["+type+"]"+linkurl+"[/"+type+"] ");
    }
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
  updatetext(thelist+listend);
}

function ca() {
  var i=0;
  for (i=0;i<document.form.elements.length;i++) {
    if ((document.form.elements[i].type=='radio') && (document.form.elements[i].value == 1)) {
      document.form.elements[i].checked = true;
    }
  }
}

function cn() {
  var i=0;
  for (i=0;i<form.elements.length;i++) {
    if ((form.elements[i].type=='radio') && (form.elements[i].value == 0)) {
      form.elements[i].checked = true;
    }
  }
}

function logoswap(newsrc,logo) {
  if (newsrc != "") {
    logo.src = "images/news/logos/"+newsrc;
  } else {
    logo.src = "images/news/logos/none.gif";
  }
}
