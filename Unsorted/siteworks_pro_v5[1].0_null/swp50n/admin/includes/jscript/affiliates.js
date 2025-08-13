
	<script language="JavaScript">
	
		function JumpToUpdateAff(intAffId, strAffName, strAffURL)
			{ if(strAffName != '' && strAffURL != '')
				{ document.location.href = 'affiliates.php?strMethod=update&aId='+intAffId+'&aName='+ReplaceAllQuotes(strAffName)+'&aURL='+ReplaceAllQuotes(strAffURL); }
			  else
				{ alert('You must enter both a name and URL for this affiliate.'); }
			}
			
		function ConfirmDelAff()
		{
			if(confirm('WARNING: You are about to permanently delete the selected affiliates.'))
				return true;
			else
				return false;
		}
			
		function JumpToAddAff()
			{ document.location.href = 'affiliates.php?strMethod=addnew'; }
			
		function ConfirmDelAff(intAffId)
			{ if(confirm('Warning: You are about to permanently delete this affiliate.'))
				{ document.location.href = 'affiliates.php?strMethod=delete&aId='+intAffId; }
			}
			
		function CheckAddAff()
			{
				thisForm = document.frmAddNewAff;
				
				if(thisForm.strName.value == '')
					{ alert('You must enter the name of this affiliate.');
					  thisForm.strName.focus();
					  return false;
					}
				
				if(thisForm.strURL.value == '')
					{ alert('You must enter the URL of this affiliate.');
					  thisForm.strURL.focus();
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