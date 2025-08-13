//$Id: post.js,v 1.6 2003/11/20 17:13:42 ryan Exp $

// http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

xtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[left]','[/left]','[right]','[/right]','[center]','[/center]','[block]','[/block]', '[list]', '[/list]', '[img]', '[/img]', '[code]','[/code]');


function setXCode(code){
    var txtarea = document.forum.post;
    var caretPos = txtarea.caretPos;
    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = xtags[code] + ttt + xtags[code+1];
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + xtags[code] + xtags[code+1];
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, xtags[code], xtags[code+1]);
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + xtags[code] + xtags[code+1];
        txtarea.focus();
        return;
    }
}

function setXURLCode(){
    var txtarea = document.forum.post;
    var caretPos = txtarea.caretPos;
    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '[url=' + ttt + ']' + ttt + '[/url]';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  '[url=' + ttt + ']' + ttt + '[/url]';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '[url=]', '[/url]');
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  '[url=' + ttt + ']' + ttt + '[/url]';;
        txtarea.focus();
        return;
    }
}

function createLine(){
    var txtarea = document.forum.post;
    txtarea.focus();
    txtarea.value += '\r\n[line]\r\n';
    txtarea.focus();
}

// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret (textEl) {
    if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;

  	var s1 = (txtarea.value).substring(0,selStart);
  	var s2 = (txtarea.value).substring(selStart, selEnd)
  	var s3 = (txtarea.value).substring(selEnd, selLength);
  	txtarea.value = s1 + open + s2 + close + s3;
  	return;
}

function changeFontColor(form) {

    var txtarea = document.forum.post;
    var caretPos = txtarea.caretPos;
    var color = form.fontColor.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '[color=' + color + ']' + ttt + '[/color]';
            document.forum.fontColor.value = 'black';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '[color=' + color + ']' + '[/color]';
            document.forum.fontColor.value = 'black';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '[color=' + color + ']', '[/color]');
        document.forum.fontColor.value = 'black';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '[color=' + color + ']' + '[/color]';
        document.forum.fontColor.value = 'black';
        txtarea.focus();
        return;
    }
}

function changeFontType(form) {

    var txtarea = document.forum.post;
    var caretPos = txtarea.caretPos;
    var color = form.fontType.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '[font=' + color + ']' + ttt + '[/font]';
            document.forum.fontType.value = 'arial';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '[font=' + color + ']' + '[/font]';
            document.forum.fontType.value = 'arial';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '[font=' + color + ']', '[/font]');
        document.forum.fontType.value = 'arial';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '[font=' + color + ']' + '[/font]';
        document.forum.fontType.value = 'arial';
        txtarea.focus();
        return;
    }
}

function changeFontSize(form) {

    var txtarea = document.forum.post;
    var caretPos = txtarea.caretPos;
    var color = form.fontSize.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '[size=' + color + ']' + ttt + '[/size]';
            document.forum.fontSize.value = '11';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '[font=' + color + ']' + '[/size]';
            document.forum.fontSize.value = '11';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '[size=' + color + ']', '[/size]');
        document.forum.fontSize.value = '11';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '[size=' + color + ']' + '[/size]';
        document.forum.fontSize.value = '11';
        txtarea.focus();
        return;
    }
}

function icon(theicon) {
    var txtarea = document.forum.post;
    txtarea.value += ' ' + theicon + ' ';
    txtarea.focus();
}

//http://www.faqts.com/knowledge_base/view.phtml/aid/1159/fid/130
function setCaretAtEnd (field) {
  if (field.createTextRange) {
    var r = field.createTextRange();
    r.moveStart('character', field.value.length);
    r.collapse();
    r.select();
  }
}

function removeSelectedOptions(from) {
    for (var i=(from.options.length-1); i>=0; i--) {
        var o=from.options[i];
        if (o.selected) { from.options[i] = null; }
    }
    from.selectedIndex = -1;
}

function getSelectedValues (select) {
    var r = new Array();
    for (var i = 0; i < select.options.length; i++)
    if (select.options[i].selected){ r[r.length] = select.options[i].value; }
    return r;
}

function selectAllOptions(obj) {
    for (var i=0; i<obj.options.length; i++) {
        obj.options[i].selected = true;
    }
}

function autoComplete (field, select, property, forcematch) {
	var found = false;
	for (var i = 0; i < select.options.length; i++) {
	if (select.options[i][property].toUpperCase().indexOf(field.value.toUpperCase()) == 0) {
		found=true; break;
		}
	}
	if (found) { select.selectedIndex = i; }
	else { select.selectedIndex = -1; }
	if (field.createTextRange) {
		if (forcematch && !found) {
			field.value=field.value.substring(0,field.value.length-1);
			return;
			}
		var cursorKeys ="8;46;37;38;39;40;33;34;35;36;45;";
		if (cursorKeys.indexOf(event.keyCode+";") == -1) {
			var r1 = field.createTextRange();
			var oldValue = r1.text;
			var newValue = found ? select.options[i][property] : oldValue;
			if (newValue != field.value) {
				field.value = newValue;
				var rNew = field.createTextRange();
				rNew.moveStart('character', oldValue.length) ;
				rNew.select();
			}
		}
	}
}

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}

function onLoadShowSub(){

    sub = getCookie('ITEMVIEW');
    tab = getCookie('ITEMTAB');
    document.getElementById('Profile').style.display = "none";
    document.getElementById('Options').style.display = "none";
    document.getElementById('Avatars').style.display = "none";

    if (sub){ document.getElementById(sub).style.display = ""; }
    else { document.getElementById('Profile').style.display = ""; }
    if (tab){ document.getElementById(tab).className = "tab-on"; }
    else { document.getElementById('iProfile').className = "tab-on"; }
}

function showSub(sub){
    document.getElementById('Profile').style.display = "none";
    document.getElementById('Options').style.display = "none";
    document.getElementById('Avatars').style.display = "none";
    document.getElementById(sub).style.display = "";

    document.getElementById('iProfile').className = "tab";
    document.getElementById('iOptions').className = "tab";
    document.getElementById('iAvatars').className = "tab";

    tab = "i" + sub
    document.getElementById(tab).className = "tab-on";

    document.cookie = "ITEMVIEW=" + escape(sub);
    document.cookie = "ITEMTAB=i" + escape(sub);
}






