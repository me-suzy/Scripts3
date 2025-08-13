
	<script language="JavaScript">

		/*
			Filename: books.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/
	
		function JumpToAddBook()
			{ document.location.href = 'books.php?strMethod=addnew'; }
			
		function CheckAddBook()
			{
				/*
					This function will make sure that all fields are
					filled in correctly and if not allow the user to
					complete the required fields.
				*/
				
				var thisForm = document.frmAddBook;
				
				if(thisForm.strTitle.value == '')
					{ alert('You must enter a title for this book.');
					  thisForm.strTitle.focus();
					  return false;
					}
					
				if(thisForm.strURL.value == '')
					{ alert('You must enter a URL for this book.');
					  thisForm.strURL.focus();
					  return false;
					}
					
				if(thisForm.strPic.value == '')
					{ alert('You must select a picture for this book.');
					  thisForm.strPic.focus();
					  return false;
					}
					
				thisForm.strTitle.value = ReplaceAllQuotes(thisForm.strTitle.value);
				thisForm.strURL.value = ReplaceAllQuotes(thisForm.strURL.value);
					
				return true;
			
			}
			
		function ConfirmDelBook()
			{
				if(confirm('WARNING: You are about to permanently delete this book from the database. Click OK to continue.'))
					return true;
				else
					return false;
			}

		function CheckUpdateBook()
			{
				/*
					This function will make sure that all fields are
					filled in correctly and if not allow the user to
					complete the required fields.
				*/
				
				var thisForm = document.frmUpdateBook;
				
				if(thisForm.strTitle.value == '')
					{ alert('You must enter a title for this book.');
					  thisForm.strTitle.focus();
					  return false;
					}
					
				if(thisForm.strURL.value == '')
					{ alert('You must enter a URL for this book.');
					  thisForm.strURL.focus();
					  return false;
					}
					
				if(thisForm.strPic.value == '' && thisForm.chkUseCurrentPic.checked == false)
					{ alert('You must select a picture for this book or choose to use the current picture.');
					  thisForm.strPic.focus();
					  return false;
					}
				
				if(thisForm.strPic.value != '' && thisForm.chkUseCurrentPic.checked == true)
					{ alert('You must select either a picture for this book or to choose to use the current picture, not both.');
					  thisForm.strPic.focus();
					  return false;
					}

				thisForm.strTitle.value = ReplaceAllQuotes(thisForm.strTitle.value);
				thisForm.strURL.value = ReplaceAllQuotes(thisForm.strURL.value);				
					
				return true;
			
			}

		function ReplaceAllQuotes(strQString)
			{
				/* Escapes all singe quotes with \' */
				out = "\'"; // replace this
				add = "&#39;"; // with this
				temp = "" + strQString; // temporary holder

				while (temp.indexOf(out)>-1) {
				pos= temp.indexOf(out);
				temp = "" + (temp.substring(0, pos) + add + 
				temp.substring((pos + out.length), temp.length));
				}

				out = "\""; // replace this
				add = "&quot;"; // with this

				while (temp.indexOf(out)>-1) {
				pos= temp.indexOf(out);
				temp = "" + (temp.substring(0, pos) + add + 
				temp.substring((pos + out.length), temp.length));
				}
				
				return temp;
			}	

	
	</script>