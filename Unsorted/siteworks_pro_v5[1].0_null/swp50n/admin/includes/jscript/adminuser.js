
	<script language="JavaScript">

		/*
			Filename: adminuser.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/
	
		function CheckLogin()
			{
				if(document.frmLogin.struName.value == '')
					{ alert('You must enter a user name to login.');
					  document.frmLogin.struName.focus();
					  return false;
					}
					
				if(document.frmLogin.strPass.value == '')
					{ alert('You must enter a password to login.');
					  document.frmLogin.strPass.focus();
					  return false;
					}
					
				return true;			
			
			}
			
		function JumpToAddUser()
			{
				// This function will load the adminuser.php?strMethod=addnew page
				document.location.href = 'users.php?strMethod=addnew';
			}

		function JumpToAdminList()
			{
				// This function will load the adminuser.php?strMethod=addnew page
				document.location.href = 'users.php';
			}
			
		function ConfirmDelAdminUser()
			{
				/*
					This function will confirm that the user wants to delete the selected
					admin user by using a confirm box.
				*/
				
				if(confirm("WARINING: You are about to permanently delete the selected users."))
					return true;
				else
					return false;
			}
	
		function CheckAddAdminLogin()
			{
				/*
					Makes sure that the form for adding a new admin user is complete and then
					redirect the page to adminuser.php?strMethod=newfinal				
				*/
				
					var thisForm = document.frmAddAdminUser;
					
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
					
					thisForm.strBio.value = iView.document.body.innerHTML;
					
					if(thisForm.strFName.value == '')
						{ alert('You must enter the first name of this user.');
						  thisForm.strFName.focus();
						  return false;
						}
						
					if(thisForm.strLName.value == '')
						{ alert('You must enter the last name of this user.');
						  thisForm.strLName.focus();
						  return false;
						}

					if((thisForm.strEmail.value.indexOf('@') == -1) || (thisForm.strEmail.value.indexOf('.') == -1))
						{ alert('You must enter a valid email address for this user.');
						  thisForm.strEmail.focus();
						  thisForm.strEmail.select();
						  return false;
						}

					if(thisForm.strUserId.value == '')
						{ alert('You must enter a user id for this user.');
						  thisForm.strUserId.focus();
						  return false;
						}

					if(thisForm.strPass.value == '')
						{ alert('You must enter a password for this user.');
						  thisForm.strPass.focus();
						  return false;
						}
						
					if(thisForm.strPic.value == '')
						{ alert('You must upload a picture for this user.');
						  thisForm.strPic.focus();
						  return false;
						}
						
					// The biography isnt compulsory but if the user does enter one,
					// we will make sure it's under 5,000 characters
					if(thisForm.strBio.value.length > 5000)
						{ lngBioLength = thisForm.strBio.value.length - 5000;
						  alert('The biography that you have entered for this user is longer than 5,000 characters. Please reduce it by ' + lngBioLength + ' characters.');
						  iView.focus();
						  return false;
						}
						
					thisForm.strFName.value = ReplaceAllQuotes(thisForm.strFName.value);
					thisForm.strLName.value = ReplaceAllQuotes(thisForm.strLName.value);
					thisForm.strEmail.value = ReplaceAllQuotes(thisForm.strEmail.value);
					thisForm.strUserId.value = ReplaceAllQuotes(thisForm.strUserId.value);
					thisForm.strPass.value = ReplaceAllQuotes(thisForm.strPass.value);
					thisForm.strBio.value = ReplaceAllQuotes(thisForm.strBio.value);
					
					// All fields are filled in, process the form
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

		function CheckUpdateAdminLogin()
			{
				/*
					Makes sure that the form for updating an admin user is complete and then
					redirect the page to adminuser.php?strMethod=updatefinal				
				*/
				
					var thisForm = document.frmUpdateAdminUser;

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

					thisForm.strBio.value = iView.document.body.innerHTML;
										
					if(thisForm.strFName.value == '')
						{ alert('You must enter the first name of this user.');
						  thisForm.strFName.focus();
						  return false;
						}
						
					if(thisForm.strLName.value == '')
						{ alert('You must enter the last name of this user.');
						  thisForm.strLName.focus();
						  return false;
						}

					if((thisForm.strEmail.value.indexOf('@') == -1) || (thisForm.strEmail.value.indexOf('.') == -1))
						{ alert('You must enter a valid email address for this user.');
						  thisForm.strEmail.focus();
						  thisForm.strEmail.select();
						  return false;
						}

					if(thisForm.strUserId.value == '')
						{ alert('You must enter a user id for this user.');
						  thisForm.strUserId.focus();
						  return false;
						}

					if(thisForm.strPass.value == '')
						{ alert('You must enter a password for this user.');
						  thisForm.strPass.focus();
						  return false;
						}
						
					if(thisForm.strPic.value == '' && thisForm.chkUseCurrentPic.checked == false)
						{ alert('You must upload a picture for this user or select to use the current picture.');
						  thisForm.strPic.focus();
						  return false;
						}
						
					// The biography isnt compulsory but if the user does enter one,
					// we will make sure it's under 5,000 characters
					if(thisForm.strBio.value.length > 5000)
						{ lngBioLength = thisForm.strBio.value.length - 5000;
						  alert('The biography that you have entered for this user is longer than 5,000 characters. Please reduce it by ' + lngBioLength + ' characters.');
						  thisForm.strBio.focus();
						  thisForm.strBio.select();
						  return false;
						}

					thisForm.strFName.value = ReplaceAllQuotes(thisForm.strFName.value);
					thisForm.strLName.value = ReplaceAllQuotes(thisForm.strLName.value);
					thisForm.strEmail.value = ReplaceAllQuotes(thisForm.strEmail.value);
					thisForm.strUserId.value = ReplaceAllQuotes(thisForm.strUserId.value);
					thisForm.strPass.value = ReplaceAllQuotes(thisForm.strPass.value);
					thisForm.strBio.value = ReplaceAllQuotes(thisForm.strBio.value);
						
					// All fields are filled in, process the form
					return true;
			}
	
	
	</script>