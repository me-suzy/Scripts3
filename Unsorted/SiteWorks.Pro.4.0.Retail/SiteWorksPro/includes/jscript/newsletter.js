
    function setCookie(cookieName, cookieValue, cookiePath, cookieExpires) 
      { 
        cookieValue = escape(cookieValue); 
        if (cookieExpires == "") 
          { 
            var nowDate = new Date(); 
            nowDate.setMonth(nowDate.getMonth() + 6); 
            cookieExpires = nowDate.toGMTString(); 
          }

        if (cookiePath != "") 
          {
            cookiePath = ";Path=" + cookiePath; 
          } 

       document.cookie = cookieName + "=" + cookieValue + ";expires=" + cookieExpires + cookiePath; 
      } 

    function getCookieValue(name) 
      { 
        var cookieString = document.cookie; 
        var index = cookieString.indexOf(name + "="); 

        if (index == -1) return null; 

        index = cookieString.indexOf("=", index) + 1; 
        var endstr = cookieString.indexOf(";", index); 

        if (endstr == -1) endstr = cookieString.length; 

        return unescape(cookieString.substring(index, endstr)); 
      } 

	function OpenWin(URL, winName, width, height, scroll)
		{
			var winLeft = (screen.width - width) / 2;
			var winTop = (screen.height - height) / 2;
					
			winData = 'height='+height+',width='+width+',top='+winTop+',left='+winLeft+',scrollbars='+scroll+',resizable';
			win = window.open(URL, winName, winData);
					
			if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
		}


  function DoPopupNL()
    {
      var NL = getCookieValue('site_newsletter'); 

      if(NL == null)
        {
          // Show the newsletter signup popup
          setCookie('site_newsletter', 'true', '', '');
          OpenWin('popupnl.php', 'popupNL', 400, 150, 'no');          
        }
    }
