function UserInit(tog) {
	if (tog) {
		if (document.all.richedit.src != L_WEBPATH) 
			document.all.richedit.src = L_WEBPATH;
		else
			UseEditor();
	} else {
		UseEditor();
	}
}

function DoOnLoad() {
if ( document.all.richedit.src=="about:blank" && document.composeform.richtext.checked && ("undefined"==typeof(window.richedit.g_state)) )
	UserInit(1);
				document.composeform.to.focus();
}

function ForceTheFocus() {
	iCount = 0;
	if (document.activeElement != document.composeform.to) {
		if (iCount >= 0 && iCount < 10) {
			document.composeform.to.focus();
			iCount++;
		}
		setTimeout("ForceTheFocus()",0)
	}
}
	        function window.document.composeform.to.onkeydown() {
				return SetFocus();
	        }
	        function window.document.composeform.cc.onkeydown() {
				return SetFocus();
	        }
	        function window.document.composeform.bcc.onkeydown() {
				return SetFocus();
	        }
	        function window.document.composeform.subject.onkeydown() {
				return SetFocus();
	        }	
	        function window.document.composeform.richtext.onkeydown() {
				return SetFocus();
	        }
			function SetFocus() {
				if (window.event.keyCode == "13")
					return false;
			}

// Begin Support RTE
	function onSubmitCompose(c) {
	if (document.composeform.richtext.checked) {	
			 if ((c == 1) && (window.richedit.getBGColor() != "")) {
				document.composeform.body.value = "<html>"
				+ "<div style='background-color:"  + window.richedit.getBGColor() + "'>"
				+ window.richedit.getHTML()
				+ "</div></html>"
			} else {
				document.composeform.body.value = "<html>" + window.richedit.getHTML() + "</html>";
			}
		document.composeform.plaintext.value = window.richedit.getText();
		}
	}



	function RTELoaded(w) {	
		w.setToolbar("tbmode",false)
		w.setToolbar("tbtable",false)
		w.setToolbar("tbbar5",false)
		w.setToolbar("tbimage",false)
		w.setSkin("#idToolbar {border: 1px black solid; background:#EEEEEE}")
		EditorLoaded();
	}
	function EditorLoaded()
	{
		if (document.composeform.richtext.checked) {
			UseEditor();
		}
	}
	
	function UseEditor() {
		if (document.composeform.richtext.checked) {
		// Show RTE
			plaintext =   document.composeform.body.value;
			if (plaintext.search(/<html>/) == 0)
				window.richedit.setHTML("<DIV>" + plaintext + "</DIV>");
			else
				window.richedit.setHTML("<DIV>" + plaintext.replace(/\n/g, "</DIV>"));
			document.composeform.body.style.visibility='hidden';
			document.all.richedit.style.visibility = 'visible';
			window.richedit.setFocus()	
		} else {
		// Show TEXTAREA
			if (confirm("Going Back to Plain Text will strip your HTML tags,\n                       Do it anyway?")) {
				document.composeform.body.value = window.richedit.getText();
				document.all.richedit.style.visibility = 'hidden';
				document.composeform.body.style.visibility='visible';
				document.composeform.body.focus();
			} else {
				document.composeform.richtext.checked = true;
			}
		}
	}
	//End Support RTE
