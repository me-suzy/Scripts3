
  // Miscellaneous site functions, etc

  function OpenWin(URL, winName, width, height, scroll)
  {
		var winLeft = (screen.width - width) / 2;
		var winTop = (screen.height - height) / 2;
													
		winData = 'height='+height+',width='+width+',top='+winTop+',left='+winLeft+',scrollbars='+scroll+',resizable';
		win = window.open(URL, winName, winData);
													
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
  }
