<script language="JavaScript1.2">

var scrollerwidth='120'
var scrollerheight='160'
var scrollerbgcolor=''
var scrollerbackground=''

var messages=new Array()

		messages[0] = "<span class='news'>Get married? ... Click here!</span>";
		messages[1] = "<span class='news'>Just take a look at this cool design!</span>";
		messages[2] = "<span class='news'>Please, register before make dating!</span>";
		messages[3] = "<span class='news'>Read about Virtual Kiss!</span>";
		messages[4] = "<span class='news'>Just next sample news</span>";
		
if (messages.length>1)
i=2
else
i=0

function move1(whichlayer){
	tlayer=eval(whichlayer)
if (tlayer.top>0&&tlayer.top<=5){
	tlayer.top=0
setTimeout("move1(tlayer)",3000)
setTimeout("move2(document.main.document.second)",3000)
return
}
if (tlayer.top>=tlayer.document.height*-1){
	tlayer.top-=5
setTimeout("move1(tlayer)",100)
}
else{
	tlayer.top=scrollerheight
	tlayer.document.write(messages[i])
	tlayer.document.close()
if (i==messages.length-1)
	i=0
else
	i++
}
}

function move2(whichlayer){
	tlayer2=eval(whichlayer)
if (tlayer2.top>0&&tlayer2.top<=5){
	tlayer2.top=0
setTimeout("move2(tlayer2)",3000)
setTimeout("move1(document.main.document.first)",3000)
return
}
if (tlayer2.top>=tlayer2.document.height*-1){
	tlayer2.top-=5
setTimeout("move2(tlayer2)",100)
}
else{
	tlayer2.top=scrollerheight
	tlayer2.document.write(messages[i])
	tlayer2.document.close()
if (i==messages.length-1)
	i=0
else
	i++
}
}

function move3(whichdiv){
	tdiv=eval(whichdiv)
if (tdiv.style.pixelTop>0&&tdiv.style.pixelTop<=5){
	tdiv.style.pixelTop=0
setTimeout("move3(tdiv)",3000)
setTimeout("move4(second2)",3000)
return
}
if (tdiv.style.pixelTop>=tdiv.offsetHeight*-1){
	tdiv.style.pixelTop-=5
setTimeout("move3(tdiv)",100)
}
else{
	tdiv.style.pixelTop=scrollerheight
	tdiv.innerHTML=messages[i]
if (i==messages.length-1)
	i=0
else
	i++
}
}

function move4(whichdiv){
	tdiv2=eval(whichdiv)
if (tdiv2.style.pixelTop>0&&tdiv2.style.pixelTop<=5){
	tdiv2.style.pixelTop=0
setTimeout("move4(tdiv2)",3000)
setTimeout("move3(first2)",3000)
return
}
if (tdiv2.style.pixelTop>=tdiv2.offsetHeight*-1){
	tdiv2.style.pixelTop-=5
setTimeout("move4(second2)",100)
}
else{
	tdiv2.style.pixelTop=scrollerheight
	tdiv2.innerHTML=messages[i]
if (i==messages.length-1)
	i=0
else
	i++
}
}

function startscroll(){
if (document.all){
	move3(first2)
	second2.style.top=scrollerheight
	second2.style.visibility='visible'
}
else if (document.layers){
	document.main.visibility='show'
	move1(document.main.document.first)
	document.main.document.second.top=scrollerheight+5
	document.main.document.second.visibility='show'
}
}

window.onload=startscroll
</script>


<ilayer id="main" left='10' width=&{scrollerwidth}; height=&{scrollerheight}; bgcolor=&{scrollerbgcolor}; background=&{scrollerbackground}; visibility='hide'>
<layer id="first" left='0' top='1' width=&{scrollerwidth};>
<script language="JavaScript1.2">
if (document.layers)
	document.write(messages[0])
</script>
</layer>
<layer id="second" left='0' top='0' width=&{scrollerwidth}; visibility='hide'>
<script language="JavaScript1.2">
if (document.layers)
	document.write(messages[1])
</script>
</layer>
</ilayer>

<script language="JavaScript1.2">
if (document.all){
	document.writeln('<span id="main2" style="position:relative;width:'+scrollerwidth+';height:'+scrollerheight+';overflow:hiden;background-color:'+scrollerbgcolor+' ;background-image:url('+scrollerbackground+')">')
	document.writeln('<div style="position:absolute;width:'+scrollerwidth+';height:'+scrollerheight+';clip:rect(0 '+scrollerwidth+' '+scrollerheight+' 0);left:0;top:0">')
	document.writeln('<div id="first2" style="position:absolute;width:'+scrollerwidth+';left:0;top:1;">')
	document.write(messages[0])
	document.writeln('</div>')
	document.writeln('<div id="second2" style="position:absolute;width:'+scrollerwidth+';left:0;top:0;visibility:hidden">')
	document.write(messages[1])
	document.writeln('</div>')
	document.writeln('</div>')
	document.writeln('</span>')
}

document.onSize = "sizer();";
</script>
