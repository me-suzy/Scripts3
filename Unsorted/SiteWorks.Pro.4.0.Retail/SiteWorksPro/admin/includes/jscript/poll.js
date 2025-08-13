
	<script language="JavaScript">

		/*
			Filename: poll.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/
	
		function JumpToAddPoll()
			{
				// This function will load the adminuser.php?strMethod=addnew page
				document.location.href = 'polls.php?strMethod=addnew';
			}

		function ConfirmDelPoll()
			{
				/*
					This function will confirm that the user wants to delete the selected
					poll by using a confirm box.
				*/
				
				if(confirm("WARNING: You are about to permanently delete this poll."))
					return true;
				else
					return false;
			}
	
		function CheckAddPoll()
			{
				/*
					Makes sure that the form for adding a new poll is complete			
				*/
				
					var thisForm = document.frmAddPoll;

					if(thisForm.strQuestion.value == '')
						{ alert('You must enter the question for this poll.');
						  thisForm.strQuestion.focus();
						  return false;
						}
					
					if(thisForm.strAnswer1.value == '')
						{ alert('You must enter the first answer for this poll.');
						  thisForm.strAnswer1.focus();
						  return false;
						}
					
					if(thisForm.strAnswer2.value == '')
						{ alert('You must enter the second answer for this poll.');
						  thisForm.strAnswer2.focus();
						  return false;
						}
						
					thisForm.strQuestion.value = ReplaceAllQuotes(thisForm.strQuestion.value);
					thisForm.strAnswer1.value = ReplaceAllQuotes(thisForm.strAnswer1.value);
					thisForm.strAnswer2.value = ReplaceAllQuotes(thisForm.strAnswer2.value);
					thisForm.strAnswer3.value = ReplaceAllQuotes(thisForm.strAnswer3.value);
					thisForm.strAnswer4.value = ReplaceAllQuotes(thisForm.strAnswer4.value);
					thisForm.strAnswer5.value = ReplaceAllQuotes(thisForm.strAnswer5.value);
					thisForm.strAnswer6.value = ReplaceAllQuotes(thisForm.strAnswer6.value);
					thisForm.strAnswer7.value = ReplaceAllQuotes(thisForm.strAnswer7.value);
					thisForm.strAnswer8.value = ReplaceAllQuotes(thisForm.strAnswer8.value);
					thisForm.strAnswer9.value = ReplaceAllQuotes(thisForm.strAnswer9.value);
					thisForm.strAnswer10.value = ReplaceAllQuotes(thisForm.strAnswer10.value);
					
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
						
					if(thisForm.intSecLevel.selectedIndex == 0)
						{ alert('You must select a security level for this user.');
						  thisForm.intSecLevel.focus();
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