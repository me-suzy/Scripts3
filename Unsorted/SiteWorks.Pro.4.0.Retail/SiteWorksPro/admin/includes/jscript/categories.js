
	<script language="JavaScript">
	
		function JumpToUpdateTopic(intCatId, strTopicName)
			{ if(strTopicName != '')
				{ document.location.href = 'topics.php?strMethod=update&tId='+intCatId+'&tName='+ReplaceAllQuotes(strTopicName); }
			  else
				{ alert('You must enter a value for this topic.');
				  eval('document.frmUpdateTopic.strTopic_'+intCatId+'.focus();');
				}
			}
			
		function JumpToAddCat()
			{ document.location.href = 'topics.php?strMethod=addnew'; }
			
		function ConfirmDelCat()
		{
			if(confirm('WARNING: You are about to permanently delete the selected categories.'))
				return true;
			else
				return false;
		}
			
		function CheckAddCat()
			{
				thisForm = document.frmAddNewCat;
				
				if(thisForm.strName.value == '')
					{ alert('You must enter the name of this topic.');
					  thisForm.strName.focus();
					  return false;
					}
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