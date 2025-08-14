
function newsAdded(popup) {
	popup.close();
	window.location.reload();
}

function showPopup(URL, name, width, height) {
	var left = (screen.width/2) - (width/2);
	var top = (screen.height/2) - (height/2);

	var params = 'left='+left+', top='+top+', width='+width+', height='+height+', menubar=no, statusbar=no';

	var popup = window.open(URL, name, params);
	popup.focus();
}

