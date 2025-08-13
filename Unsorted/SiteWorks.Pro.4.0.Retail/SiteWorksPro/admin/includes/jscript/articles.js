
	<script language="JavaScript">
	
		/*
			This script will make sure that all of
			the required fields are completed for a new article		
		*/
		
		function CheckAddArticle()
			{
			
				var thisForm = document.frmAddArticle;
				
				if(thisForm.strTitle.value == '')
					{ alert('You must enter a title for this article.');
					  thisForm.strTitle.focus();
					  return false;
					}
					
				if(thisForm.intType.selectedIndex == 0)
					{ alert('You must select the type for this article.');
					  thisForm.intType.focus();
					  return false;
					}
					
				if(thisForm.intAuthorId.selectedIndex == 0)
					{ alert('You must select an author for this article.');
					  thisForm.intAuthorId.focus();
					  return false;
					}
					
				if(thisForm.strSummary.value == '')
					{ alert('You must enter a summary for this article.');
					  thisForm.strSummary.focus();
					  return false;
					}

				if(thisForm.strSummary.value.length > 500)
					{ alert('The summary that you have entered for this article is longer than 500 characters. Please reduce it by '+ (thisForm.strSummary.value.length - 500) + ' characters.');
					  thisForm.strSummary.focus();
					  thisForm.strSummary.select();
					  return false;
					}
					
				thisForm.strTitle.value = ReplaceAllQuotes(thisForm.strTitle.value);
				thisForm.strSummary.value = ReplaceAllQuotes(thisForm.strSummary.value);
				thisForm.strForumLink.value = ReplaceAllQuotes(thisForm.strForumLink.value);
				thisForm.strRelLink1.value = ReplaceAllQuotes(thisForm.strRelLink1.value);
				thisForm.strRelLink2.value = ReplaceAllQuotes(thisForm.strRelLink2.value);
				thisForm.strRelLink3.value = ReplaceAllQuotes(thisForm.strRelLink3.value);
					
				return true;				
			}

		function CheckUpdateArticle()
			{
							
				var thisForm = document.frmAddArticle;
				
				if(thisForm.strTitle.value == '')
					{ alert('You must enter a title for this article.');
					  thisForm.strTitle.focus();
					  return false;
					}
					
				if(thisForm.intType.selectedIndex == 0)
					{ alert('You must select the type for this article.');
					  thisForm.intType.focus();
					  return false;
					}
					
				if(thisForm.intAuthorId.selectedIndex == 0)
					{ alert('You must select an author for this article.');
					  thisForm.intAuthorId.focus();
					  return false;
					}
					
				if(thisForm.strSummary.value == '')
					{ alert('You must enter a summary for this article.');
					  thisForm.strSummary.focus();
					  return false;
					}

				if(thisForm.strSummary.value.length > 500)
					{ alert('The summary that you have entered for this article is longer than 500 characters. Please reduce it by '+ (thisForm.strSummary.value.length - 500) + ' characters.');
					  thisForm.strSummary.focus();
					  thisForm.strSummary.select();
					  return false;
					}
					
				if(thisForm.strZip.value == '' && thisForm.blnUseZip.checked == false)
					{ alert('You must choose to either upload a support file for this article or choose to use the current one on file.');
					  thisForm.strZip.focus();
					  return false;
					}
					
				thisForm.strTitle.value = ReplaceAllQuotes(thisForm.strTitle.value);
				thisForm.strSummary.value = ReplaceAllQuotes(thisForm.strSummary.value);
				thisForm.strForumLink.value = ReplaceAllQuotes(thisForm.strForumLink.value);
				thisForm.strRelLink1.value = ReplaceAllQuotes(thisForm.strRelLink1.value);
				thisForm.strRelLink2.value = ReplaceAllQuotes(thisForm.strRelLink2.value);
				thisForm.strRelLink3.value = ReplaceAllQuotes(thisForm.strRelLink3.value);

				return true;				
			}
			
			
		function AddBold()
			{
				var strSel = new String()
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{			
						var strBold = new String();
						strBold = prompt('Enter text to add as bolded:');
				
						if(strBold != '' && strBold != 'undefined' && strBold != null)
							{ document.frmAddContent.txtContent.focus();
							  document.selection.createRange().text = '<b>' + strBold + '</b>';
							}
					}
				else
					{ document.selection.createRange().text = '<b>' + strSel + '</b>'; }
					
				document.frmAddContent.txtContent.focus();
			}
			
		function AddItalic()
			{
				var strSel = new String()
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{			
						var strItalic = new String();
						strItalic = prompt('Enter text to add as italicised:');
				
						if(strItalic != '' && strItalic != 'undefined' && strItalic != null)
							{ document.frmAddContent.txtContent.focus();
							  document.selection.createRange().text = '<i>' + strItalic + '</i>';
							}
					}
				else
					{ document.selection.createRange().text = '<i>' + strSel + '</i>'; }
					
				document.frmAddContent.txtContent.focus();
			}
			
		function AddUnderline()
			{
				var strSel = new String()
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{			
						var strUnderline = new String();
						strUnderline = prompt('Enter text to add as underlined:');
				
						if(strUnderline != '' && strUnderline != 'undefined' && strUnderline != null)
							{ document.frmAddContent.txtContent.focus();
							  document.selection.createRange().text = '<u>' + strUnderline + '</u>';
							}
					}
				else
					{ document.selection.createRange().text = '<u>' + strSel + '</u>'; }
					
				document.frmAddContent.txtContent.focus();
			}
			
		function AddLPara()
			{
				var strSel = new String();
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{ document.frmAddContent.txtContent.focus();
					  document.selection.createRange().text = '<p align=\'left\'></p>';
					}
				else
					{ document.selection.createRange().text = '<p align=\'left\'>' + strSel + '</p>'; }

				document.frmAddContent.txtContent.focus();				
			}
			
		function AddMPara()
			{
				var strSel = new String();
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{ document.frmAddContent.txtContent.focus();
					  document.selection.createRange().text = '<p align=\'center\'></p>';
					}
				else
					{ document.selection.createRange().text = '<p align=\'center\'>' + strSel + '</p>'; }

				document.frmAddContent.txtContent.focus();				
			}
			
		function AddRPara()
			{
				var strSel = new String();
				strSel = document.selection.createRange().text;
				
				if(strSel.length == 0)
					{ document.frmAddContent.txtContent.focus();
					  document.selection.createRange().text = '<p align=\'right\'></p>';
					}
				else
					{ document.selection.createRange().text = '<p align=\'right\'>' + strSel + '</p>'; }

				document.frmAddContent.txtContent.focus();				
			}
			
		function AddNumList()
			{
				var strList = new String();
				var strListItem = new String();
				
				strList = '<ol>';
				strListItem = '[empty]';
				
				while(strListItem != '' && strListItem != 'undefined' && strListItem != null)
					{
						strListItem = prompt('Enter next item in list (Leave empty to complete list):');
						if(strListItem != '' && strListItem != 'undefined' && strListItem != null)
							{ strList = strList + '<li>' + strListItem + '</li>'; }
					}
				
				strList = strList + '</ol>';
				document.frmAddContent.txtContent.focus();
				document.selection.createRange().text = strList;
				document.frmAddContent.txtContent.focus();
			
			}
			
		function AddList()
			{
				var strList = new String();
				var strListItem = new String();
				
				strList = '<ul>';
				strListItem = '[empty]';
				
				while(strListItem != '' && strListItem != 'undefined' && strListItem != null)
					{
						strListItem = prompt('Enter next item in list (Leave empty to complete list):');
						if(strListItem != '' && strListItem != 'undefined' && strListItem != null)
							{ strList = strList + '<li>' + strListItem + '</li>'; }
					}
				
				strList = strList + '</ul>';
				document.frmAddContent.txtContent.focus();
				document.selection.createRange().text = strList;
				document.frmAddContent.txtContent.focus();			
			
			}
			
		function AddHyperLink()
			{
				var strLink = new String();
				var strText = new String();
				
				strLink = prompt('Enter URL for this hyperlink:');
				strText = prompt('Enter the text for this hyperlink:');
				
				document.frmAddContent.txtContent.focus();
				document.selection.createRange().text = '<a href=\'' + strLink + '\'>' + strText + '</a>';
				document.frmAddContent.txtContent.focus();
			}
			
		function AddPage()
			{
				var strTitle = new String();
				var strContent = new String();
				var acount = arrTitles.length;
				var thisForm = document.frmAddContent;

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
				
				thisForm.txtContent.value = iView.document.body.innerHTML;

				if(acount < 20)
					{			
						strTitle = document.frmAddContent.txtTitle.value;
						strContent = document.frmAddContent.txtContent.value;
				
						if(strTitle.length == 0)
							{ alert('You must enter a title for this page first.');
							  document.frmAddContent.txtTitle.focus();
							  return;
							}
				
						if(strContent.length == 0)
							{ alert('You must enter some content for this page first.');
							  document.frmAddContent.txtContent.focus();
							  return;
							}
							
						/*
							If this function has gotten this far it means that we can
							add the page details to the arrTitle and arrContent arrays.
						*/
							
						setTimeout("iView.document.body.innerHTML = '';", 0);
						
						arrTitles[acount] = strTitle;
						arrContents[acount] = strContent;
				
						document.frmAddContent.txtTitle.value = '';
						document.frmAddContent.txtContent.value = '';				
						ReloadPages();
						document.frmAddContent.txtTitle.focus();
					}
				else
					{ alert('This article can have a maximum of twenty pages. Adding this page would exceed the limit.');
					  document.frmAddContent.txtTitle.focus();
					  document.frmAddContent.txtTitle.select();
					}
			}
			
		function ReloadPages()
			{
				var thisForm = document.frmAddContent;
				thisForm.txtPages.length = 0;
				
				for(acount=0; acount<arrTitles.length; acount++)
					{
					    thisItem = arrTitles[acount];
						thisForm.txtPages.add (new Option(arrTitles[acount]));
					}
			}
			
		function ClearPage()
			{
				if(confirm('Warning: All content for the current page will be cleared. Click OK to continue.'))
					{ setTimeout("iView.document.body.innerHTML = '';", 0);	}
				iView.focus();
			}
			
		function LoadPage()
			{
				var thisForm = document.frmAddContent;
				var thisIndex = thisForm.txtPages.selectedIndex;
				
				if(thisIndex > -1)
					{
						// Load the details from the array
						document.all.txtTitle.value = arrTitles[thisIndex];
						document.all.cmdUpdate.disabled = false;
						document.all.cmdAdd.disabled = true;

						setTimeout("iView.document.body.innerHTML = '" + MakeNewLinesSafe(ReplaceAllQuotes(arrContents[thisIndex])) + "'", 0);
						iView.focus();
					}
			}
			
		function UpdatePage()
			{
				var thisForm = document.all;

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

				document.all.txtContent.value = iView.document.body.innerHTML;
				
				var strTitle = thisForm.txtTitle.value;
				var strContent = thisForm.txtContent.value;
				var thisIndex = thisForm.txtPages.selectedIndex;
				
				if(strTitle.length == 0)
					{ alert('You must enter a title for this page first.');
					  document.frmAddContent.txtTitle.focus();
					  return;
					}
				
				if(strContent.length == 0)
					{ alert('You must enter some content for this page first.');
					  document.frmAddContent.txtContent.focus();
					  return;
					}
					
				arrTitles[thisIndex] = strTitle;
				arrContents[thisIndex] = strContent;
								
				thisForm.txtTitle.value = '';
				thisForm.txtTitle.focus();
		
				ReloadPages();
				setTimeout("iView.document.body.innerHTML = '';", 0);
				//thisForm.txtPages[thisIndex].selected = true;
			}
			
		function Cancel()
			{
				var thisForm = document.frmAddContent;
				
				thisForm.txtPages.selectedIndex = -1;
				thisForm.txtTitle.value = '';
				thisForm.txtContent.value = '';
				thisForm.cmdAdd.disabled = false;
				thisForm.cmdUpdate.disabled = true;
				
				setTimeout("iView.document.body.innerHTML = '';", 0);
				thisForm.txtTitle.focus();
			}
			
		function ClearPages()
			{
				if(confirm('Warning: You are about to delete all of the pages from this article. Click OK to continue.'))
					{
						var thisForm = document.frmAddContent;
				
						arrTitles.length = 0;
						arrContents.length = 0;
						ReloadPages();

						for(counter=0; counter<thisForm.txtPages.length; counter++)
							{ thisForm.txtPages[counter].selected = false; }
						
						thisForm.txtTitle.value = '';
						thisForm.txtContent.value = '';
						thisForm.cmdAdd.disabled = false;
						thisForm.cmdUpdate.disabled = true;
						
						setTimeout("iView.document.body.innerHTML = '';", 0);
						thisForm.txtTitle.focus();
					}
			}
			
		function DelPage()
			{
				var thisForm = document.frmAddContent;
				var thisIndex = thisForm.txtPages.selectedIndex;
				
				if(thisIndex > -1)
				{				
					if(confirm('Warning: You are about to delete this page from the article. Click OK to continue.'))
						{
							
							var a = 0;
							
							//arrTitles.splice(thisIndex, 1);
							//arrContents.splice(thisIndex, 1);
							
							var newTitles = new Array();
							var newContents = new Array();
							
							for(i = 0; i < arrTitles.length; i++)
							{
								if(i != thisIndex)
								{
									newTitles[a] = arrTitles[i];
									newContents[a] = arrContents[i];
									a = a + 1;
								}
							}
							
							arrTitles = newTitles;
							arrContents = newContents;
							
							for(counter=0; counter<thisForm.txtPages.length; counter++)
								{ thisForm.txtPages[counter].selected = false; }
								
							ReloadPages();
							
							document.all.txtTitle.value = '';
							document.all.txtContent.value = '';
							document.all.cmdAdd.disabled = false;
							document.all.cmdUpdate.disabled = true;
							
							setTimeout("iView.document.body.innerHTML = '';", 0);
							thisForm.txtTitle.focus();
						}
					else
					{
					
					}
				}
				else
				{
					alert('Please select an article to delete.');
					thisForm.txtPages.focus();
				}
			}
			
		function MoveUp()
			{
				var thisForm = document.frmAddContent;
				
				if(thisForm.txtPages.selectedIndex > 0)
					{
						thisIndex = thisForm.txtPages.selectedIndex-1;
						tmp1 = arrTitles[thisForm.txtPages.selectedIndex-1];
						tmp2 = arrTitles[thisForm.txtPages.selectedIndex];
						tmp3 = arrContents[thisForm.txtPages.selectedIndex-1];
						tmp4 = arrContents[thisForm.txtPages.selectedIndex];
						arrTitles[thisForm.txtPages.selectedIndex] = tmp1;
						arrTitles[thisForm.txtPages.selectedIndex-1] = tmp2;
						arrContents[thisForm.txtPages.selectedIndex] = tmp3;
						arrContents[thisForm.txtPages.selectedIndex-1] = tmp4;
						ReloadPages();			
						thisForm.txtPages.selectedIndex = thisIndex;
					}
			}
			
		function MoveDown()
			{
				var thisForm = document.frmAddContent;
				
				if(thisForm.txtPages.selectedIndex < arrTitles.length-1)
					{
						thisIndex = thisForm.txtPages.selectedIndex+1;
						tmp1 = arrTitles[thisForm.txtPages.selectedIndex+1];
						tmp2 = arrTitles[thisForm.txtPages.selectedIndex];
						tmp3 = arrContents[thisForm.txtPages.selectedIndex+1];
						tmp4 = arrContents[thisForm.txtPages.selectedIndex];
						arrTitles[thisForm.txtPages.selectedIndex] = tmp1;
						arrTitles[thisForm.txtPages.selectedIndex+1] = tmp2;
						arrContents[thisForm.txtPages.selectedIndex] = tmp3;
						arrContents[thisForm.txtPages.selectedIndex+1] = tmp4;
						ReloadPages();			
						thisForm.txtPages.selectedIndex = thisIndex;
					}
			}
			
		function AddImage()
			{ var imageWin = window.open('imagebank.php', 'imageWin', 'top=100, left=100, height=290, width=700, toolbar=0, scrolling=0, statusbar=0'); }
			
		function AddStyle(strStyle)
			{
				if(strStyle != 'NULL')
					{
						document.frmAddContent.txtContent.focus();
						document.selection.createRange().text = '<span class="' + strStyle + '">' + document.selection.createRange().text + '</span>';						
					}			
			}
			
		function CheckAddContent()
			{
				/*
					This form will make sure that the user entered at least one page for this
					article and then it will convert all of the pages into their respective
					hidden form variables				
				*/
			
				var thisForm = document.frmAddContent;
				var docLength = arrTitles.length;
				
				if(docLength >= 1)
					{
						for(counter=0; counter < docLength; counter++)
							{
								switch(counter)
									{
										case 0:
											{
												thisForm.strTitle1.value = arrTitles[0];
												thisForm.strContent1.value = arrContents[0];											
											}
										case 1:
											{
												thisForm.strTitle2.value = arrTitles[1];
												thisForm.strContent2.value = arrContents[1];											
											}
										case 2:
											{
												thisForm.strTitle3.value = arrTitles[2];
												thisForm.strContent3.value = arrContents[2];											
											}
										case 3:
											{
												thisForm.strTitle4.value = arrTitles[3];
												thisForm.strContent4.value = arrContents[3];
											}
										case 4:
											{
												thisForm.strTitle5.value = arrTitles[4];
												thisForm.strContent5.value = arrContents[4];											
											}
										case 5:
											{
												thisForm.strTitle6.value = arrTitles[5];
												thisForm.strContent6.value = arrContents[5];											
											}
										case 6:
											{
												thisForm.strTitle7.value = arrTitles[6];
												thisForm.strContent7.value = arrContents[6];											
											}
										case 7:
											{
												thisForm.strTitle8.value = arrTitles[7];
												thisForm.strContent8.value = arrContents[7];											
											}
										case 8:
											{
												thisForm.strTitle9.value = arrTitles[8];
												thisForm.strContent9.value = arrContents[8];											
											}
										case 9:
											{
												thisForm.strTitle10.value = arrTitles[9];
												thisForm.strContent10.value = arrContents[9];											
											}
										case 10:
											{
												thisForm.strTitle11.value = arrTitles[10];
												thisForm.strContent11.value = arrContents[10];											
											}
										case 11:
											{
												thisForm.strTitle12.value = arrTitles[11];
												thisForm.strContent12.value = arrContents[11];
											}
										case 12:
											{
												thisForm.strTitle13.value = arrTitles[12];
												thisForm.strContent13.value = arrContents[12];
											}
										case 13:
											{
												thisForm.strTitle14.value = arrTitles[13];
												thisForm.strContent14.value = arrContents[13];
											}
										case 14:
											{
												thisForm.strTitle15.value = arrTitles[14];
												thisForm.strContent15.value = arrContents[14];
											}
										case 15:
											{
												thisForm.strTitle16.value = arrTitles[15];
												thisForm.strContent16.value = arrContents[15];
											}
										case 16:
											{
												thisForm.strTitle17.value = arrTitles[16];
												thisForm.strContent17.value = arrContents[16];
											}
										case 17:
											{
												thisForm.strTitle18.value = arrTitles[17];
												thisForm.strContent18.value = arrContents[17];
											}
										case 18:
											{
												thisForm.strTitle19.value = arrTitles[18];
												thisForm.strContent19.value = arrContents[18];
											}
										case 19:
											{
												thisForm.strTitle20.value = arrTitles[19];
												thisForm.strContent20.value = arrContents[19];
											}
									}
							}												
					}
				else
					{
						alert('You must enter the title and content for at least one page before this article can be added.');
						thisForm.txtTitle.focus();
						return false;
					}
					
				return true;
			}
			
		function ReplaceQuotes(strQString)
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
				
				return temp;
			}	

		function BR2NL(strQString)
			{
				/* Escapes all singe quotes with \' */
				out = "<br>"; // replace this
				add = "\n"; // with this
				temp = "" + strQString; // temporary holder

				while (temp.indexOf(out)>-1) {
				pos= temp.indexOf(out);
				temp = "" + (temp.substring(0, pos) + add + 
				temp.substring((pos + out.length), temp.length));
				}
				
				return temp;
			}	

		function MakeNewLinesSafe(strQString)
			{
				/* Escapes all new lines with \r\n */
				out = "\r\n"; // replace this
				add = "\\r\\n"; // with this
				temp = "" + strQString; // temporary holder

				while (temp.indexOf(out)>-1) {
				pos= temp.indexOf(out);
				temp = "" + (temp.substring(0, pos) + add + 
				temp.substring((pos + out.length), temp.length));
				}
				
				return temp;
			}	
			
		function JumpToAddArticle()
			{ document.location.href = 'articles.php?strMethod=addnew'; }
			
		function ConfirmDelArticle(intArticleId)
			{
				if(confirm('Warning: You are about to permanently delete this article from the database. Click OK to continue.'))
					return true;
				else
					return false;
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

				return temp;
			}	
	
	
	
	</script>