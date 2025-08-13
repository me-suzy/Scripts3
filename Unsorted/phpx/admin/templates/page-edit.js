//$Id: page-edit.js,v 1.10 2003/10/14 19:49:14 ryan Exp $

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

function createTable(){
    var txtarea = document.doc_html.text;
    var c = 0;
    var r = 0;
    var title ='';
    rows = prompt("How many rows", 3);
    cols = prompt("How many Columns", 3);
    width = prompt("Table Width", "90%");
    align = prompt("Table Alignment", "center");
    border = prompt("Table Border (number)", "1");
    titleToggle = prompt("Do you want a title bar? (yes|no)", "yes");

    if (titleToggle != null && titleToggle != "no"){
        titleAlign = prompt("Title Alignment", "center");
        titleText = prompt("Title", "");
        title = '<tr><td class=title colspan=' + cols + ' align=' + titleAlign + '>' + titleText + '</td></tr>';
    }
    var table = '<table border=' + border + ' align=' + align + ' cellpadding=2 cellspacing=2 width=' + width + '>';
    table += title;
    while(r<rows){
        table += '<tr>';
        while(c<cols){
            inst = 'Cell ' + r + '-' + c;
            text = prompt(inst, "");
            table += '<td class=main>' + text + '</td>';
            c++;
        }
        table += '</tr>';
        c=0;
        r++
    }
    table += '</table>';
    txtarea.value += ' ' + table;
    txtarea.focus();
    return;
}

function uList(type){
    var g = 50;
    var x = 0;
    var txtarea = document.doc_html.text;
    if (type == 1){ var insert = '<ol>'; }
    else { var insert = '<ul>'; }

    var caretPos = txtarea.caretPos;

    while(x<g){
        var item = '';
        item = prompt("List Item", "");
        if (item == null){
            break
        }
        else {
            insert += '<li>' + item;
            g++;
        }
    }
    txtarea.value += ' ' + insert;
    txtarea.focus();
    return;
}

function StartImagePage() {
    OpenWin = this.open("images.php?insert=1", "CtrlWindow1", "toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=no,width=550,height=450");
}

function bodyLoad(){
    document.doc_html.text.focus();
}

function SubForm(parm) {
    displaySubs('actionResult');
    if (parm == 1){ var action = 'page.php?action=modify&create=1'; }
    else { var action = 'page.php?action=modify'; }
    document.doc_html.action = action;
    document.doc_html.submit();
}

function createNews(){
    document.doc_html.action = 'news.php?action=create';
    document.doc_html.submit();
}

function submitNews(){
    document.doc_html.action = 'news.php?action=sub';
    document.doc_html.submit();
}

function modifyNews(){
    displaySubs('actionResult');
    document.doc_html.action = 'news.php?action=modify';
    document.doc_html.submit();
}

function setPoll(){
    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    if ((clientVer >= 4) && is_ie && is_win){
        caretPos.text += ':POLL:' + document.doc_html.poll.value + ':';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += ':POLL:' + document.doc_html.poll.value + ':';
        txtarea.focus();
        return;
    }
}

function link(){
    ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
    var caretPos = document.doc_html.text.caretPos;
    box=prompt ("Where do you want this to link to?", "http://");
    caretPos.text = '<a href=' + box + '>' + ttt + '</a>';
}


htmlTags = new Array('<b>','</b>','<i>','</i>','<u>','</u>','<div align=left>','</div>','<div align=right>','</div>','<div align=center>','</center>','<blockquote>','</blockquote>');


function setHTMLCode(code){
    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = htmlTags[code] + ttt + htmlTags[code+1];
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + htmlTags[code] + htmlTags[code+1];
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, htmlTags[code], htmlTags[code+1]);
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + htmlTags[code] + htmlTags[code+1];
        txtarea.focus();
        return;
    }
}


htmlTags1 = new Array('<br>\r\n', '<hr width=100% width=2 border=0>', '&nbsp;');


function setHTMLCode1(code){
    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    if ((clientVer >= 4) && is_ie && is_win){
        caretPos.text += htmlTags1[code];
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += ' ' + htmlTags1[code];
        txtarea.focus();
        return;
    }
}

function changeFontColor(form) {

    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    var color = form.fontColor.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '<font color=' + color + '>' + ttt + '</font>';
            document.doc_html.fontColor.value = 'black';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '<font color=' + color + '>' + '</font>';
            document.doc_html.fontColor.value = 'black';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '<font color=' + color + '>', '</font>');
        document.doc_html.fontColor.value = 'black';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '<font color=' + color + '>' + '</font>';
        document.doc_html.fontColor.value = 'black';
        txtarea.focus();
        return;
    }
}

function changeFontType(form) {

    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    var color = form.fontType.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '<font face=' + color + '>' + ttt + '</font>';
            document.doc_html.fontType.value = 'arial';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '<font face=' + color + '>' + '</font>';
            document.doc_html.fontType.value = 'arial';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '<font face=' + color + '>', '</font>');
        document.doc_html.fontType.value = 'arial';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '<font face=' + color + '>' + '</font>';
        document.doc_html.fontType.value = 'arial';
        txtarea.focus();
        return;
    }
}

function changeFontSize(form) {

    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    var color = form.fontSize.value;

    if ((clientVer >= 4) && is_ie && is_win){
        ttt = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (ttt) {
            caretPos.text = '<span style=font-size:' + color + 'px;>' + ttt + '</span>';
            document.doc_html.fontSize.value = '11';
            txtarea.focus();
            return;
        }
        else {
            txtarea.value +=  ' ' + '<span style=font-size:' + color + 'px;>' + '</span>';
            document.doc_html.fontSize.value = '11';
            txtarea.focus();
            return;
        }
    }
    else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0)){
        mozWrap(txtarea, '<span style=font-size:' + color + 'px;>', '</span>');
        document.doc_html.fontSize.value = '11';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value +=  ' ' + '<span style=font-size:' + color + 'px;>' + '</span>';
        document.doc_html.fontSize.value = '11';
        txtarea.focus();
        return;
    }
}








function pagePreview(){
    msgWindow = window.open('','PrePane','width=750,height=450,scrollbars=yes,resizable=yes,menubar=yes');
    msgWindow.location.href = 'test.php';
    if (msgWindow.opener == null) msgWindow.opener = self;
}

function newsPreview(){
    msgWindow = window.open('','PrePane','width=750,height=450,scrollbars=yes,resizable=yes,menubar=yes');
    msgWindow.location.href = 'preview.php';
    if (msgWindow.opener == null) msgWindow.opener = self;
}


function setFeature(parm){
    document.doc_html.text.value = document.doc_html.feature.value;
}

function setSubFeature(){
    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    var subFeature = document.doc_html.subfeature.value;
    if ((clientVer >= 4) && is_ie && is_win && caretPos != null){
        caretPos.text += subFeature;
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += subFeature;
        txtarea.focus();
        return;
    }
}

function setFeature1(parm){
    var caretPos = document.doc_html.text.caretPos;
    caretPos.text = parm;

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

function insertInsertText(){

    var code = document.doc_html.insert.value;
    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;
    var insertCode = insertArray[code];

    for(i=0;i<insertCode.length;i++){
        insertCode = insertCode.replace("==rn==", '\r\n');
    }
    if ((clientVer >= 4) && is_ie && is_win){

        caretPos.text += insertCode;
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += ' ' + insertCode;
        txtarea.focus();
        return;
    }
}

function findReplace(){

    var txtarea = document.doc_html.text;
    replace = prompt ("Replace with?",'');
    if ((clientVer >= 4) && is_ie && is_win){
        var find = (document.all) ? document.selection.createRange().text : document.getSelection();
        if (find) {
            for(i=0;i<txtarea.value.length;i++){
                txtarea.value = txtarea.value.replace(find, replace);
            }
            txtarea.focus();
            return;
        }
        else {
            txtarea.focus();
            return;
        }
    }
}

function insertMoreSplit(){

    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;

    if ((clientVer >= 4) && is_ie && is_win){
        caretPos.text += '\r\n===SPLIT===\r\n';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += '\r\n===SPLIT===\r\n';
        txtarea.focus();
        return;
    }
}

function insertEditorSplit(){

    var txtarea = document.doc_html.text;
    var caretPos = txtarea.caretPos;

    if ((clientVer >= 4) && is_ie && is_win){
        caretPos.text += '\r\n===EDITOR===\r\n';
        txtarea.focus();
        return;
    }
    else {
        txtarea.value += '\r\n===EDITOR===\r\n';
        txtarea.focus();
        return;
    }
}

function insertMenuItem(){

    document.f.name.value = document.f.insertItem.options[document.f.insertItem.selectedIndex].text;
    document.f.link.value = document.f.insertItem.value;

}


