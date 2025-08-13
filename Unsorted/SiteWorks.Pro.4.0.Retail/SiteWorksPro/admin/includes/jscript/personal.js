
	<script language="JavaScript">
	
		/*
			Filename: personal
			Desc: Handles all of the client side form
			      validation and data manipulation routines
			      for personal content, etc.
		*/
		
		function CheckUpdate2Cents()
		{
			var thisForm = document.frmUpdate2Cents;

			if(document.all.toggleBut.alt != "Toggle Mode (to code)")
			{
				iText = iView.document.body.innerText;
				iView.document.body.innerHTML = iText;
				document.all.toggleBut.alt = "Toggle Mode (to code)";
						  
				// Show all controls
				tblCtrls.style.display = 'inline';
				document.all.selFont.style.display = 'inline';
				document.all.selSize.style.display = 'inline';
				document.all.selHeading.style.display = 'inline';

				iView.focus();
			}
			
			thisForm.strCents.value = iView.document.body.innerHTML;
			
			if(thisForm.strCents.value == '')
			{
				alert('You must enter content for the \'2 Cents\' field first.');
				iView.focus();
				return false;
			}
			
			return true;
		}
		
		function CheckUpdateTip()
		{
			var thisForm = document.frmUpdateTip;

			if(document.all.toggleBut.alt != "Toggle Mode (to code)")
			{
				iText = iView.document.body.innerText;
				iView.document.body.innerHTML = iText;
				document.all.toggleBut.alt = "Toggle Mode (to code)";
						  
				// Show all controls
				tblCtrls.style.display = 'inline';
				document.all.selFont.style.display = 'inline';
				document.all.selSize.style.display = 'inline';
				document.all.selHeading.style.display = 'inline';

				iView.focus();
			}
			
			thisForm.strTip.value = iView.document.body.innerHTML;
			
			if(thisForm.strTip.value == '')
			{
				alert('You must enter content for the \'handy tip\' field first.');
				iView.focus();
				return false;
			}
			
			return true;
		}
	
	</script>