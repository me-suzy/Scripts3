
	<script language="JavaScript">

		/*
			Filename: devnews.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/

		function CheckAddNews()
			{
				var frm = document.frmAddNews;

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
				
				frm.strContent.value = iView.document.body.innerHTML;
				
				if(frm.strTitle.value == '')
					{
						alert('Please enter a title for this news post');
						frm.strTitle.focus();
						return false;
					}
				if(frm.strSource.value == '')
					{
						alert('Please enter the source for this news post');
						frm.strSource.focus();
						return false;
					}
				if(frm.strURL.value == '')
					{
						alert('Please enter the URL for this news post');
						frm.strURL.focus();
						return false;
					}
				if(frm.intAuthorId.selectedIndex == 0)
					{
						alert('Please select the author for this news post');
						frm.intAuthorId.focus();
						return false;					
					}
				if(frm.strContent.value == '')
					{
						alert('Please enter the content for this news post');
						iView.focus();
						return false;					
					}
				if(frm.strContent.value.length > 1000)
					{
						alert('Please trim this news post to 1000 characters');
						frm.strContent.focus();
						return false;					
					}
					
				return true;
			}
			
		function ConfirmDelNews()
			{
				if(confirm('WARNING: This will permanently remove this news item from the database. Click OK to continue'))
					return true;
				else
					return false;
			}
			
	</script>