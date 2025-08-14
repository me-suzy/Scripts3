
var preload = new Array();

preload[0] = new Image();
preload[0].src = 'admin/icons/plus.gif';
preload[1] = new Image();
preload[1].src = 'admin/icons/minus.gif';

function menutoggle(tableid,imageid) {

  if (tableid.style.display == 'none') {
    tableid.style.display = '';
    imageid.src = 'admin/icons/minus.gif';
  } else {
    tableid.style.display = 'none';
    imageid.src = 'admin/icons/plus.gif';
  }

}
