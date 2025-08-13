  var viewMode = 1;

  function Init()
  {
    iHtml.document.designMode = 'On';
  
  }
  
  function selOn(ctrl)
  {
	ctrl.style.borderColor = '#CCCCCC';
	ctrl.style.backgroundColor = '#B5BED6';
	ctrl.style.cursor = 'hand';	
  }
  
  function selOff(ctrl)
  {
	ctrl.style.borderColor = '#D6D3CE';  
	ctrl.style.backgroundColor = '#D6D3CE';
  }
  
  function selDown(ctrl)
  {
	ctrl.style.backgroundColor = '#8492B5';
  }
  
  function selUp(ctrl)
  {
    ctrl.style.backgroundColor = '#B5BED6';
  }
    
  function doBold()
  {
	iHtml.document.execCommand('bold', false, null);
  }

  function doItalic()
  {
	iHtml.document.execCommand('italic', false, null);
  }

  function doUnderline()
  {
	iHtml.document.execCommand('underline', false, null);
  }
  
  function doLeft()
  {
    iHtml.document.execCommand('justifyleft', false, null);
  }

  function doCenter()
  {
    iHtml.document.execCommand('justifycenter', false, null);
  }

  function doRight()
  {
    iHtml.document.execCommand('justifyright', false, null);
  }

  function doOrdList()
  {
    iHtml.document.execCommand('insertorderedlist', false, null);
  }

  function doBulList()
  {
    iHtml.document.execCommand('insertunorderedlist', false, null);
  }
  
   function doSave()
  {
    iHtml.document.execCommand('SaveAs', false, null);
  }

   function doOpen()
  {
    iHtml.document.execCommand('Open', false, null);
  }

   function doPrint()
  {
    iHtml.document.execCommand('Print', false, null);
  }
  
  function doForeCol()
  {
    var fCol = prompt('Write a color name or a color code to be used as the font color', '');
    
    if(fCol != null)
      iHtml.document.execCommand('forecolor', false, fCol);
  }

  function doBackCol()
  {
    var bCol = prompt('Write a color name or a color code to be used as the background color', '');
    
    if(bCol != null)
      iHtml.document.execCommand('backcolor', false, bCol);
  }

  function doLink()
  {
    iHtml.document.execCommand('createlink');
  }
  
 
  function doRule()
  {
    iHtml.document.execCommand('inserthorizontalrule', false, null);
  }
  
  function doFont(fName)
  {
    if(fName != '')
      iHtml.document.execCommand('fontname', false, fName);
  }
  
  function doSize(fSize)
  {
    if(fSize != '')
      iHtml.document.execCommand('fontsize', false, fSize);
  }
  
  function doHead(hType)
  {
    if(hType != '')
    {
      iHtml.document.execCommand('formatblock', false, hType);  
    }
  }
  
  
  
function SendForm() 
{ 
	var htmlCode = iHtml.document.body.innerHTML; 
	document.htmlform.newsPost.value = htmlCode; 
	return true; 
} 