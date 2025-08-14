<script type="text/javascript">

var base64s="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

function encode(decStr){

  var bits, dual, i = 0, encOut = '';
  while(decStr.length >= i + 3){
    bits =
    (decStr.charCodeAt(i++) & 0xff) <<16 |
    (decStr.charCodeAt(i++) & 0xff) <<8  |
     decStr.charCodeAt(i++) & 0xff;
    encOut +=
     base64s.charAt((bits & 0x00fc0000) >>18) +
     base64s.charAt((bits & 0x0003f000) >>12) +
     base64s.charAt((bits & 0x00000fc0) >> 6) +
     base64s.charAt((bits & 0x0000003f));
    }
  if(decStr.length -i > 0 && decStr.length -i < 3){
    dual = Boolean(decStr.length -i -1);
    bits =
     ((decStr.charCodeAt(i++) & 0xff) <<16) |
     (dual ? (decStr.charCodeAt(i) & 0xff) <<8 : 0);
    encOut +=
      base64s.charAt((bits & 0x00fc0000) >>18) +
      base64s.charAt((bits & 0x0003f000) >>12) +
      (dual ? base64s.charAt((bits & 0x00000fc0) >>6) : '=') +
      '=';
    }
  return encOut
  }


function js_pixel_code(page,identifier){
	document.write("      <img alt=\"\" src=\"pixel.php?");
	document.write("h=");
	document.write(encode(document.domain));
	document.write("&u=");
	document.write(encode(document.URL));
	document.write("&r=");
	document.write(encode(document.referrer));

	document.write("&p=");
	document.write(encode(page));
	if(identifier){
		document.write("&i=");
		document.write(encode(identifier));
	}

	document.write('\" ');
	document.write(" width=\"1\" height=\"1\" ");
	document.write("/>\r\n");

	return true;
}


</script>